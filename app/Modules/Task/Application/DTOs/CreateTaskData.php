<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\DTOs;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use Carbon\CarbonInterface;

final readonly class CreateTaskData
{
    public function __construct(
        public int $projectId,
        public int $createdBy,
        public ?int $assignedTo,
        public string $title,
        public ?string $description,
        public TaskStatus $status,
        public TaskPriority $priority,
        public ?CarbonInterface $dueDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectId: $data['project_id'],
            createdBy: $data['created_by'],
            assignedTo: $data['assigned_to'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            status: TaskStatus::from($data['status']),
            priority: TaskPriority::from($data['priority']),
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
        );
    }
}
