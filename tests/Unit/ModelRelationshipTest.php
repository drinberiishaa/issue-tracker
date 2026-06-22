<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_has_many_issues(): void
    {
        $project = Project::factory()->create();
        Issue::factory()->count(3)->create(['project_id' => $project->id]);

        $this->assertCount(3, $project->issues);
    }

    public function test_issue_belongs_to_project(): void
    {
        $project = Project::factory()->create();
        $issue = Issue::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($issue->project->is($project));
    }

    public function test_issue_has_many_comments(): void
    {
        $issue = Issue::factory()->create();
        Comment::factory()->count(2)->create(['issue_id' => $issue->id]);

        $this->assertCount(2, $issue->comments);
    }

    public function test_issue_belongs_to_many_tags(): void
    {
        $issue = Issue::factory()->create();
        $tags = Tag::factory()->count(3)->create();
        $issue->tags()->attach($tags->pluck('id'));

        $this->assertCount(3, $issue->tags);
        $this->assertTrue($tags->first()->issues->contains($issue));
    }

    public function test_issue_belongs_to_many_members(): void
    {
        $issue = Issue::factory()->create();
        $user = User::factory()->create();
        $issue->members()->attach($user->id);

        $this->assertTrue($issue->members->contains($user));
        $this->assertTrue($user->assignedIssues->contains($issue));
    }

    public function test_project_belongs_to_owner(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $this->assertTrue($project->owner->is($owner));
    }

    public function test_deleting_an_issue_cascades_comments_and_pivot_rows(): void
    {
        $issue = Issue::factory()->create();
        $comment = Comment::factory()->create(['issue_id' => $issue->id]);
        $tag = Tag::factory()->create();
        $issue->tags()->attach($tag->id);

        $issue->delete();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('issue_tag', ['issue_id' => $issue->id]);
    }
}
