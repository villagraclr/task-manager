<?php

declare(strict_types=1);

namespace App\Data\Project;

final readonly class CreateProjectMemberData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $ownerId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            ownerId: $data['owner_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => $this->ownerId,
        ];
    }
}
