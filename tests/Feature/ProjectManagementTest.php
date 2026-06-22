<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_project_list(): void
    {
        Project::factory()->count(3)->create();

        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertViewIs('projects.index');
    }

    public function test_guests_can_view_a_single_project_with_its_issues(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);

        $response = $this->get(route('projects.show', $project));

        $response->assertOk();
        $response->assertSee($project->name);
        $response->assertSee($issue->title);
    }

    public function test_guests_cannot_create_a_project(): void
    {
        $response = $this->post(route('projects.store'), [
            'name' => 'New Project',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_authenticated_user_can_create_a_project_and_becomes_owner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Website Revamp',
            'description' => 'Redesign the marketing site.',
            'start_date' => '2026-01-01',
            'deadline' => '2026-06-01',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Website Revamp',
            'owner_id' => $user->id,
        ]);

        $project = Project::where('name', 'Website Revamp')->first();
        $response->assertRedirect(route('projects.show', $project));
    }

    public function test_project_creation_requires_a_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_deadline_must_be_on_or_after_start_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Bad Dates',
            'start_date' => '2026-06-01',
            'deadline' => '2026-01-01',
        ]);

        $response->assertSessionHasErrors('deadline');
    }

    public function test_only_the_owner_can_update_their_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->put(route('projects.update', $project), [
            'name' => 'Hijacked Name',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('projects', ['name' => 'Hijacked Name']);
    }

    public function test_owner_can_update_their_own_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($owner)->put(route('projects.update', $project), [
            'name' => 'Updated Name',
            'description' => $project->description,
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Updated Name']);
    }

    public function test_only_the_owner_can_delete_their_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->delete(route('projects.destroy', $project));

        $response->assertForbidden();
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_deleting_a_project_cascades_to_its_issues(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $issue = Issue::factory()->create(['project_id' => $project->id]);

        $this->actingAs($owner)->delete(route('projects.destroy', $project));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('issues', ['id' => $issue->id]);
    }
}
