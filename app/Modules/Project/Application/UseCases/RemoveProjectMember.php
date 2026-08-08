<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;

final class RemoveProjectMember
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(Project $project, User $user): void
    {
        $this->projects->removeMember($project, $user);
    }
}
