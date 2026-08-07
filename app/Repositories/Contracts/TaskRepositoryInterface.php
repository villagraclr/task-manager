<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Data\Task\CreateTaskData;
use App\Data\Task\UpdateTaskData;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function all(): Collection;

    public function getByProject(int $projectId): Collection;

    public function getByAssignee(int $userId): Collection;

    public function getByCreator(int $userId): Collection;

    public function getByStatus(string $status): Collection;

    public function findById(int $id): ?Task;

    public function create(CreateTaskData $data): Task;

    public function update(Task $task, UpdateTaskData $data): Task;

    public function delete(Task $task): bool;
    
    public function restore(Task $task): bool;

    public function forceDelete(Task $task): bool;
}
