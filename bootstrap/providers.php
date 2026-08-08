<?php

use App\Modules\Comment\Infrastructure\Providers\CommentServiceProvider;
use App\Modules\Project\Infrastructure\Providers\ProjectServiceProvider;
use App\Modules\Task\Infrastructure\Providers\TaskServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    ProjectServiceProvider::class,
    TaskServiceProvider::class,
    CommentServiceProvider::class,
];
