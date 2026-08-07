<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Comment\CreateCommentData;
use App\Data\Comment\UpdateCommentData;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    public function getByTask(int $taskId): Collection
    {
        return $this->commentRepository->getByTask($taskId);
    }

    public function findById(int $id): ?Comment
    {
        return $this->commentRepository->findById($id);
    }

    public function create(CreateCommentData $data): Comment
    {
        return $this->commentRepository->create($data);
    }

    public function update(Comment $comment, UpdateCommentData $data): Comment
    {
        return $this->commentRepository->update($comment, $data);
    }

    public function delete(Comment $comment): bool
    {
        return $this->commentRepository->delete($comment);
    }
}
