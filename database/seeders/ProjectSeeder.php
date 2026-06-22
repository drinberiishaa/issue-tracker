<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed demo projects, each with issues, comments, tags, and assigned members.
     */
    public function run(): void
    {
        $owners = User::all();
        $tags = Tag::all();
        $members = User::all();

        Project::factory()
            ->count(5)
            ->create()
            ->each(function (Project $project) use ($owners, $tags, $members) {
                $project->update(['owner_id' => $owners->random()->id]);

                Issue::factory()
                    ->count(rand(4, 9))
                    ->create(['project_id' => $project->id])
                    ->each(function (Issue $issue) use ($tags, $members) {
                        // Attach 1-3 random tags to each issue.
                        $issue->tags()->attach(
                            $tags->random(rand(1, 3))->pluck('id')->toArray()
                        );

                        // Assign 0-3 random members to each issue.
                        $memberCount = rand(0, 3);
                        if ($memberCount > 0) {
                            $issue->members()->attach(
                                $members->random($memberCount)->pluck('id')->toArray()
                            );
                        }

                        // Add a few comments to each issue.
                        Comment::factory()
                            ->count(rand(0, 4))
                            ->create(['issue_id' => $issue->id]);
                    });
            });
    }
}
