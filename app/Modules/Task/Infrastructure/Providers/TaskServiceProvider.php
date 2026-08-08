<?php

declare(strict_types=1);

namespace App\Modules\Task\Infrastructure\Providers;

use App\Modules\Task\Domain\Ports\TaskRepositoryInterface;
use App\Modules\Task\Infrastructure\Persistence\EloquentTaskRepository;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            EloquentTaskRepository::class
        );
    }
}
