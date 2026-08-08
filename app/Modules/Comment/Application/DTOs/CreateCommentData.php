<?php

declare(strict_types=1);

namespace App\Modules\Comment\Application\DTOs;

final readonly class CreateCommentData
{
    public function __construct(
        public int $taskId,
        public int $userId,
        public string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            taskId: $data['task_id'],
            userId: $data['user_id'],
            body: $data['body'],
        );
    }
}
