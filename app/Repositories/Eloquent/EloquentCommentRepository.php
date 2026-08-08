<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Data\Comment\CreateCommentData;
use App\Data\Comment\UpdateCommentData;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function all(): Collection
    {
        return Comment::query()
            ->with(['task.project', 'author'])
            ->get();
    }

    public function getByTask(int $taskId): Collection
    {
        return Comment::query()
            ->with('author')
            ->where('task_id', $taskId)
            ->latest()
            ->get();
    }

    public function findById(int $id): ?Comment
    {
        return Comment::query()
            ->with(['task.project', 'author'])
            ->find($id);
    }

    public function create(CreateCommentData $data): Comment
    {
        return Comment::create($data->toArray());
    }

    public function update(Comment $comment, UpdateCommentData $data): Comment
    {
        $comment->update($data->toArray());

        return $comment->fresh();
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
