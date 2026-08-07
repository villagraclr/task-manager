<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Data\Task\CreateTaskData;
use App\Data\Task\UpdateTaskData;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::query()
            ->with(['project', 'createdBy', 'assignedTo'])
            ->get();
    }

    public function getByProject(int $projectId): Collection
    {
        return Task::query()
            ->with(['createdBy', 'assignedTo'])
            ->where('project_id', $projectId)
            ->get();
    }

    public function getByAssignee(int $userId): Collection
    {
        return Task::query()
            ->with(['project', 'createdBy', 'assignedTo'])
            ->where('assigned_to', $userId)
            ->get();
    }

    public function getByCreator(int $userId): Collection
    {
        return Task::query()
            ->with(['project', 'createdBy', 'assignedTo'])
            ->where('created_by', $userId)
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        return Task::query()
            ->with(['project', 'createdBy', 'assignedTo'])
            ->where('status', $status)
            ->get();
    }

    public function findById(int $id): ?Task
    {
        return Task::query()
            ->with(['project', 'createdBy', 'assignedTo'])
            ->find($id);
    }

    public function create(CreateTaskData $data): Task
    {
        return Task::create($data->toArray());
    }

    public function update(Task $task, UpdateTaskData $data): Task
    {
        $task->update($data->toArray());
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function restore(Task $task): bool
    {
        return $task->restore();
    }

    public function forceDelete(Task $task): bool
    {
        return $task->forceDelete();
    }
}
