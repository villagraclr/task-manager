<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class GetProjectMembers
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(Project $project): Collection
    {
        return $this->projects->getMembers($project);
    }
}
