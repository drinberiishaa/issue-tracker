<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Anyone authenticated can view projects.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create a project (they become its owner).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner can update a project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }

    /**
     * Only the owner can delete a project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }
}
