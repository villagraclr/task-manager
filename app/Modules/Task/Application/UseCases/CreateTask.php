<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\UseCases;

use App\Modules\Task\Application\DTOs\CreateTaskData;
use App\Modules\Task\Domain\Models\Task;
use App\Modules\Task\Domain\Ports\TaskRepositoryInterface;

final class CreateTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks
    ) {}

    public function handle(CreateTaskData $data): Task
    {
        $task = new Task([
            'project_id' => $data->projectId,
            'created_by' => $data->createdBy,
            'assigned_to' => $data->assignedTo,
            'title' => $data->title,
            'description' => $data->description,
            'status' => $data->status,
            'priority' => $data->priority,
            'due_date' => $data->dueDate,
        ]);

        return $this->tasks->save($task);
    }
}
