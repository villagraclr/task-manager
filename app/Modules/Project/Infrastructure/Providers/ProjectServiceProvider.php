<?php

declare(strict_types=1);

namespace App\Modules\Project\Infrastructure\Providers;

use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;
use App\Modules\Project\Infrastructure\Persistence\EloquentProjectRepository;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProjectRepositoryInterface::class,
            EloquentProjectRepository::class
        );
    }
}
