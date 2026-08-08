<?php

declare(strict_types=1);

namespace App\Modules\Project\UI\Policies;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    public function update(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    public function manageMembers(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }
}
