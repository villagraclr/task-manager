<?php

declare(strict_types=1);

namespace App\Modules\Comment\Domain\Ports;

use App\Modules\Comment\Domain\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function getByTask(int $taskId): Collection;

    public function save(Comment $comment): Comment;

    public function delete(Comment $comment): bool;
}
