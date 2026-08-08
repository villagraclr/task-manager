<?php

declare(strict_types=1);

namespace App\Modules\Task\Application\UseCases;

use App\Modules\Task\Domain\Models\Task;
use App\Modules\Task\Domain\Ports\TaskRepositoryInterface;

final class DeleteTask
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks
    ) {}

    public function handle(Task $task): bool
    {
        return $this->tasks->delete($task);
    }
}
