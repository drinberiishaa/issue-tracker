<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression coverage for a route-ordering bug: Route::resource registers a
 * wildcard GET /{resource}/{id} (show) route. If a literal route like
 * GET /projects/create or GET /issues/search/ajax is declared *after* that
 * wildcard, it gets shadowed — the wildcard matches first and Laravel tries
 * to model-bind "create" or "search" as if it were a record ID, returning a
 * 404 instead of reaching the intended controller action.
 */
class RouteOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_create_form_is_reachable_and_not_shadowed_by_show_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('projects.create'));

        $response->assertOk();
        $response->assertViewIs('projects.create');
    }

    public function test_projects_edit_form_is_reachable_and_not_shadowed_by_show_route(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $response = $this->actingAs($owner)->get(route('projects.edit', $project));

        $response->assertOk();
        $response->assertViewIs('projects.edit');
    }

    public function test_issues_create_form_is_reachable_and_not_shadowed_by_show_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('issues.create'));

        $response->assertOk();
        $response->assertViewIs('issues.create');
    }

    public function test_issues_edit_form_is_reachable_and_not_shadowed_by_show_route(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get(route('issues.edit', $issue));

        $response->assertOk();
        $response->assertViewIs('issues.edit');
    }

    public function test_issues_search_ajax_endpoint_is_reachable_and_not_shadowed_by_show_route(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->create(['project_id' => $project->id, 'title' => 'Findable issue']);

        $response = $this->get(route('issues.search', ['q' => 'Findable']));

        $response->assertOk();
        $response->assertSee('Findable issue');
    }

    public function test_guests_are_redirected_to_login_for_protected_create_routes(): void
    {
        // A guest hitting /projects/create should be redirected to login by
        // auth middleware, not get a 404 from a shadowed wildcard route.
        $response = $this->get(route('projects.create'));

        $response->assertRedirect(route('login'));
    }
}
