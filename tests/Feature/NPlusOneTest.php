<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NPlusOneTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_show_page_does_not_n_plus_one_on_issue_tags(): void
    {
        $project = Project::factory()->create();
        $tag = Tag::factory()->create();

        Issue::factory()->count(10)->create(['project_id' => $project->id])
            ->each(fn (Issue $issue) => $issue->tags()->attach($tag->id));

        $queryCount = 0;
        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $this->get(route('projects.show', $project))->assertOk();

        // A small constant number of queries regardless of issue count indicates
        // eager loading is working (project + issues + tags pivot, roughly).
        $this->assertLessThan(10, $queryCount, 'Expected eager loading to keep query count low regardless of issue count.');
    }

    public function test_issues_index_does_not_n_plus_one_on_project_and_tags(): void
    {
        $projects = Project::factory()->count(5)->create();
        $tag = Tag::factory()->create();

        foreach ($projects as $project) {
            Issue::factory()->count(3)->create(['project_id' => $project->id])
                ->each(fn (Issue $issue) => $issue->tags()->attach($tag->id));
        }

        $queryCount = 0;
        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $this->get(route('issues.index'))->assertOk();

        $this->assertLessThan(15, $queryCount, 'Expected eager loading to keep query count low regardless of issue count.');
    }
}
