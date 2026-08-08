<?php

declare(strict_types=1);

namespace App\Modules\Comment\UI\Policies;

use App\Models\User;
use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Task\Domain\Models\Task;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {
        return $comment->task->project->owner_id === $user->id
            || $comment->user_id === $user->id
            || $comment->task->assigned_to === $user->id;
    }

    public function create(User $user, Task $task): bool
    {
        return $task->project->owner_id === $user->id
            || $task->assigned_to === $user->id
            || $task->created_by === $user->id;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id
            || $comment->task->project->owner_id === $user->id;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }
}
