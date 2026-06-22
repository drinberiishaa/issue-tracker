<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTagAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_attach_a_tag_to_an_issue_via_ajax(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);
        $tag = Tag::factory()->create(['name' => 'bug']);

        $response = $this->actingAs($user)->postJson(route('issues.tags.store', $issue), [
            'tag_id' => $tag->id,
        ]);

        $response->assertOk();
        $response->assertJsonFragment(['name' => 'bug']);
        $this->assertTrue($issue->tags()->where('tags.id', $tag->id)->exists());
    }

    public function test_attaching_the_same_tag_twice_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();

        $this->actingAs($user)->postJson(route('issues.tags.store', $issue), ['tag_id' => $tag->id]);
        $this->actingAs($user)->postJson(route('issues.tags.store', $issue), ['tag_id' => $tag->id]);

        $this->assertEquals(1, $issue->tags()->count());
    }

    public function test_authenticated_user_can_detach_a_tag_from_an_issue_via_ajax(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();
        $issue->tags()->attach($tag->id);

        $response = $this->actingAs($user)->deleteJson(route('issues.tags.destroy', [$issue, $tag]));

        $response->assertOk();
        $this->assertFalse($issue->tags()->where('tags.id', $tag->id)->exists());
    }

    public function test_attaching_a_nonexistent_tag_fails_validation(): void
    {
        $user = User::factory()->create();
        $issue = Issue::factory()->create();

        $response = $this->actingAs($user)->postJson(route('issues.tags.store', $issue), [
            'tag_id' => 99999,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('tag_id');
    }

    public function test_guests_cannot_attach_tags(): void
    {
        $issue = Issue::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->postJson(route('issues.tags.store', $issue), [
            'tag_id' => $tag->id,
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_a_tag_with_unique_name(): void
    {
        $user = User::factory()->create();
        Tag::factory()->create(['name' => 'duplicate']);

        $response = $this->actingAs($user)->postJson(route('tags.store'), [
            'name' => 'duplicate',
            'color' => '#FF0000',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_authenticated_user_can_create_a_new_unique_tag(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('tags.store'), [
            'name' => 'brand-new-tag',
            'color' => '#00FF00',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tags', ['name' => 'brand-new-tag']);
    }
}
