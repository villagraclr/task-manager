<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;

final class DeleteProject
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(Project $project): bool
    {
        return $this->projects->delete($project);
    }
}
