<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueMemberAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_assign_a_member_to_an_issue_via_ajax(): void
    {
        $actor = User::factory()->create();
        $issue = Issue::factory()->create();
        $member = User::factory()->create(['name' => 'Carlos Dev']);

        $response = $this->actingAs($actor)->postJson(route('issues.members.store', $issue), [
            'user_id' => $member->id,
        ]);

        $response->assertOk();
        $response->assertJsonFragment(['name' => 'Carlos Dev']);
        $this->assertTrue($issue->members()->where('users.id', $member->id)->exists());
    }

    public function test_authenticated_user_can_unassign_a_member_via_ajax(): void
    {
        $actor = User::factory()->create();
        $issue = Issue::factory()->create();
        $member = User::factory()->create();
        $issue->members()->attach($member->id);

        $response = $this->actingAs($actor)->deleteJson(route('issues.members.destroy', [$issue, $member]));

        $response->assertOk();
        $this->assertFalse($issue->members()->where('users.id', $member->id)->exists());
    }

    public function test_guests_cannot_assign_members(): void
    {
        $issue = Issue::factory()->create();
        $member = User::factory()->create();

        $response = $this->postJson(route('issues.members.store', $issue), [
            'user_id' => $member->id,
        ]);

        $response->assertUnauthorized();
    }
}
