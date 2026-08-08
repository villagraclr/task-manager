<?php

declare(strict_types=1);

namespace App\Data\Comment;

final readonly class UpdateCommentData
{
    public function __construct(
        public string $body,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            body: $data['body'],
        );
    }

    public function toArray(): array
    {
        return [
            'body' => $this->body,
        ];
    }
}
