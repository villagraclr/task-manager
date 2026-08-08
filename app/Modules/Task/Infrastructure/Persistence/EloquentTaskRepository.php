<?php

declare(strict_types=1);

namespace App\Modules\Task\Infrastructure\Persistence;

use App\Modules\Task\Domain\Models\Task;
use App\Modules\Task\Domain\Ports\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getByProject(int $projectId): Collection
    {
        return Task::with(['createdBy', 'assignedTo'])
            ->where('project_id', $projectId)
            ->get();
    }

    public function findById(int $id): ?Task
    {
        return Task::with(['project', 'createdBy', 'assignedTo'])->find($id);
    }

    public function save(Task $task): Task
    {
        $task->save();

        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
