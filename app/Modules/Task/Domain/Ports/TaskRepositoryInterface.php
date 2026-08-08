<?php

declare(strict_types=1);

namespace App\Modules\Task\Domain\Ports;

use App\Modules\Task\Domain\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getByProject(int $projectId): Collection;

    public function findById(int $id): ?Task;

    public function save(Task $task): Task;

    public function delete(Task $task): bool;
}
