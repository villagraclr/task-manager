<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Models\User;
use App\Modules\Project\Domain\Exceptions\CannotAddOwnerAsMemberException;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;

final class AddProjectMember
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(Project $project, User $user): void
    {
        if ($user->id === $project->owner_id) {
            throw new CannotAddOwnerAsMemberException;
        }

        $this->projects->addMember($project, $user);
    }
}
