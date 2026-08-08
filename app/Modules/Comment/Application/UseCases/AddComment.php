<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases;

use App\Modules\Comment\Application\DTOs\CreateCommentData;
use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Comment\Domain\Ports\CommentRepositoryInterface;

final class AddComment
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments
    ) {}

    public function handle(CreateCommentData $data): Comment
    {
        $comment = new Comment([
            'task_id' => $data->taskId,
            'user_id' => $data->userId,
            'body' => $data->body,
        ]);

        return $this->comments->save($comment);
    }
}
