<?php

declare(strict_types=1);

namespace App\Modules\Project\Infrastructure\Observers;

use App\Modules\Project\Domain\Models\Project;

class ProjectObserver
{
    public function deleted(Project $project): void
    {
        if ($project->isForceDeleting()) {
            return;
        }

        $project->tasks()->each(fn ($task) => $task->delete());
    }

    public function restored(Project $project): void
    {
        $project->tasks()->withTrashed()->each(fn ($task) => $task->restore());
    }
}
