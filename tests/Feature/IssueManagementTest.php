<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_issue_list(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->count(3)->create(['project_id' => $project->id]);

        $response = $this->get(route('issues.index'));

        $response->assertOk();
    }

    public function test_issue_list_can_be_filtered_by_status(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->create(['project_id' => $project->id, 'status' => 'open', 'title' => 'Open One']);
        Issue::factory()->create(['project_id' => $project->id, 'status' => 'closed', 'title' => 'Closed One']);

        $response = $this->get(route('issues.index', ['status' => 'open']));

        $response->assertOk();
        $response->assertSee('Open One');
        $response->assertDontSee('Closed One');
    }

    public function test_issue_list_can_be_filtered_by_priority(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->create(['project_id' => $project->id, 'priority' => 'high', 'title' => 'Urgent Bug']);
        Issue::factory()->create(['project_id' => $project->id, 'priority' => 'low', 'title' => 'Minor Tweak']);

        $response = $this->get(route('issues.index', ['priority' => 'high']));

        $response->assertSee('Urgent Bug');
        $response->assertDontSee('Minor Tweak');
    }

    public function test_issue_list_can_be_filtered_by_tag(): void
    {
        $project = Project::factory()->create();
        $tag = Tag::factory()->create(['name' => 'backend']);
        $taggedIssue = Issue::factory()->create(['project_id' => $project->id, 'title' => 'Tagged Issue']);
        $untaggedIssue = Issue::factory()->create(['project_id' => $project->id, 'title' => 'Untagged Issue']);
        $taggedIssue->tags()->attach($tag->id);

        $response = $this->get(route('issues.index', ['tag' => $tag->id]));

        $response->assertSee('Tagged Issue');
        $response->assertDontSee('Untagged Issue');
    }

    public function test_ajax_search_endpoint_filters_by_title(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->create(['project_id' => $project->id, 'title' => 'Login button broken']);
        Issue::factory()->create(['project_id' => $project->id, 'title' => 'Add dark mode']);

        $response = $this->get(route('issues.search', ['q' => 'login']));

        $response->assertOk();
        $response->assertSee('Login button broken');
        $response->assertDontSee('Add dark mode');
    }

    public function test_guests_cannot_create_an_issue(): void
    {
        $project = Project::factory()->create();

        $response = $this->post(route('issues.store'), [
            'project_id' => $project->id,
            'title' => 'New Issue',
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_an_issue_with_tags(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->actingAs($user)->post(route('issues.store'), [
            'project_id' => $project->id,
            'title' => 'Fix the login bug',
            'description' => 'Users cannot log in on Safari.',
            'status' => 'open',
            'priority' => 'high',
            'due_date' => '2026-08-01',
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        $issue = Issue::where('title', 'Fix the login bug')->first();
        $this->assertNotNull($issue);
        $response->assertRedirect(route('issues.show', $issue));
        $this->assertCount(2, $issue->tags);
    }

    public function test_issue_creation_requires_valid_status_and_priority(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post(route('issues.store'), [
            'project_id' => $project->id,
            'title' => 'Bad Enum Issue',
            'status' => 'not-a-real-status',
            'priority' => 'not-a-real-priority',
        ]);

        $response->assertSessionHasErrors(['status', 'priority']);
    }

    public function test_issue_show_page_displays_tags_and_comments(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);
        $tag = Tag::factory()->create(['name' => 'urgent-fix']);
        $issue->tags()->attach($tag->id);
        $issue->comments()->create(['author_name' => 'Jane', 'body' => 'Looking into this now.']);

        $response = $this->get(route('issues.show', $issue));

        $response->assertOk();
        $response->assertSee('urgent-fix');
    }

    public function test_authenticated_user_can_update_an_issue(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id, 'status' => 'open']);

        $response = $this->actingAs($user)->put(route('issues.update', $issue), [
            'title' => $issue->title,
            'status' => 'closed',
            'priority' => $issue->priority,
        ]);

        $response->assertRedirect(route('issues.show', $issue));
        $this->assertDatabaseHas('issues', ['id' => $issue->id, 'status' => 'closed']);
    }

    public function test_authenticated_user_can_delete_an_issue(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->delete(route('issues.destroy', $issue));

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseMissing('issues', ['id' => $issue->id]);
    }
}
