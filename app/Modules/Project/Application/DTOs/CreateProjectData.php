<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\DTOs;

final readonly class CreateProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $ownerId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            ownerId: $data['owner_id'],
        );
    }
}
