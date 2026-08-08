<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Modules\Project\Application\DTOs\UpdateProjectData;
use App\Modules\Project\Domain\Exceptions\DuplicateProjectNameException;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;

final class UpdateProject
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(Project $project, UpdateProjectData $data): Project
    {
        $exists = $this->projects->getByOwner($project->owner_id)
            ->where('id', '!=', $project->id)
            ->contains('name', $data->name);

        if ($exists) {
            throw new DuplicateProjectNameException($data->name);
        }

        $project->name = $data->name;
        $project->description = $data->description;

        return $this->projects->save($project);
    }
}
