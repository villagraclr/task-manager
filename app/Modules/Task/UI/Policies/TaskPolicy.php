<?php

declare(strict_types=1);

namespace App\Modules\Task\UI\Policies;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Task\Domain\Models\Task;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id
            || $task->created_by === $user->id
            || $task->assigned_to === $user->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id
            || $task->assigned_to === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id;
    }

    public function restore(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id;
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id;
    }
}
