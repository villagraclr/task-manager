<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Data\Comment\CreateCommentData;
use App\Data\Comment\UpdateCommentData;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function all(): Collection;

    public function getByTask(int $taskId): Collection;

    public function findById(int $id): ?Comment;

    public function create(CreateCommentData $data): Comment;

    public function update(Comment $comment, UpdateCommentData $data): Comment;

    public function delete(Comment $comment): bool;
}
