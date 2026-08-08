<?php

declare(strict_types=1);

namespace App\Modules\Comment\Infrastructure\Providers;

use App\Modules\Comment\Domain\Ports\CommentRepositoryInterface;
use App\Modules\Comment\Infrastructure\Persistence\EloquentCommentRepository;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CommentRepositoryInterface::class,
            EloquentCommentRepository::class
        );
    }
}
