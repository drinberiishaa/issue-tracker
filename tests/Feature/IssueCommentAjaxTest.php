<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueCommentAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_comments_can_be_listed_via_ajax_and_are_paginated(): void
    {
        $issue = Issue::factory()->create();
        Comment::factory()->count(7)->create(['issue_id' => $issue->id]);

        $response = $this->getJson(route('issues.comments.index', $issue));

        $response->assertOk();
        $response->assertJsonCount(5, 'data'); // default per-page is 5
        $response->assertJsonPath('total', 7);
    }

    public function test_comments_are_ordered_newest_first(): void
    {
        $issue = Issue::factory()->create();
        $older = Comment::factory()->create(['issue_id' => $issue->id, 'created_at' => now()->subDay()]);
        $newer = Comment::factory()->create(['issue_id' => $issue->id, 'created_at' => now()]);

        $response = $this->getJson(route('issues.comments.index', $issue));

        $response->assertJsonPath('data.0.id', $newer->id);
        $response->assertJsonPath('data.1.id', $older->id);
    }

    public function test_authenticated_user_can_post_a_comment_via_ajax(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();

        $response = $this->actingAs($user)->postJson(route('issues.comments.store', $issue), [
            'author_name' => 'Jane Doe',
            'body' => 'This is a test comment.',
        ]);

        $response->assertCreated();
        $response->assertJsonFragment(['author_name' => 'Jane Doe', 'body' => 'This is a test comment.']);
        $this->assertDatabaseHas('comments', [
            'issue_id' => $issue->id,
            'author_name' => 'Jane Doe',
        ]);
    }

    public function test_comment_requires_author_name_and_body(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();

        $response = $this->actingAs($user)->postJson(route('issues.comments.store', $issue), [
            'author_name' => '',
            'body' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['author_name', 'body']);
    }

    public function test_guests_cannot_post_a_comment(): void
    {
        $issue = Issue::factory()->create();

        $response = $this->postJson(route('issues.comments.store', $issue), [
            'author_name' => 'Anonymous',
            'body' => 'Should not be allowed.',
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseMissing('comments', ['body' => 'Should not be allowed.']);
    }
}
