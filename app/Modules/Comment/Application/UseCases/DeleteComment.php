<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\UseCases;

use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Comment\Domain\Ports\CommentRepositoryInterface;

final class DeleteComment
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments
    ) {}

    public function handle(Comment $comment): bool
    {
        return $this->comments->delete($comment);
    }
}
