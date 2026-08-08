<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Task\CreateTaskData;
use App\Data\Task\UpdateTaskData;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function all(): Collection
    {
        return $this->taskRepository->all();
    }

    public function getByProject(int $projectId): Collection
    {
        return $this->taskRepository->getByProject($projectId);
    }

    public function getByAssignee(int $userId): Collection
    {
        return $this->taskRepository->getByAssignee($userId);
    }

    public function getByCreator(int $userId): Collection
    {
        return $this->taskRepository->getByCreator($userId);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->taskRepository->getByStatus($status);
    }

    public function findById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    public function create(CreateTaskData $data): Task
    {
        return $this->taskRepository->create($data);
    }

    public function update(Task $task, UpdateTaskData $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    public function delete(Task $task): bool
    {
        return $this->taskRepository->delete($task);
    }
}
