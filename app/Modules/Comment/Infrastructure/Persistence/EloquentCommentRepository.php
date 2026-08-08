<?php

declare(strict_types=1);

namespace App\Modules\Comment\Infrastructure\Persistence;

use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Comment\Domain\Ports\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function getByTask(int $taskId): Collection
    {
        return Comment::with('author')
            ->where('task_id', $taskId)
            ->latest()
            ->get();
    }

    public function save(Comment $comment): Comment
    {
        $comment->save();

        return $comment->fresh();
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
