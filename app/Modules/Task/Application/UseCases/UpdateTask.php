<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\UseCases;

use App\Modules\Task\Application\DTOs\UpdateTaskData;
use App\Modules\Task\Domain\Models\Task;
use App\Modules\Task\Domain\Ports\TaskRepositoryInterface;

final class UpdateTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks
    ) {}

    public function handle(Task $task, UpdateTaskData $data): Task
    {
        $task->assigned_to = $data->assignedTo;
        $task->title = $data->title;
        $task->description = $data->description;
        $task->status = $data->status;
        $task->priority = $data->priority;
        $task->due_date = $data->dueDate;

        return $this->tasks->save($task);
    }
}
