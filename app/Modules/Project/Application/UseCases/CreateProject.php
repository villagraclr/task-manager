<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Modules\Project\Application\DTOs\CreateProjectData;
use App\Modules\Project\Domain\Exceptions\DuplicateProjectNameException;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;

final class CreateProject
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(CreateProjectData $data): Project
    {
        $exists = $this->projects->getByOwner($data->ownerId)
            ->contains('name', $data->name);

        if ($exists) {
            throw new DuplicateProjectNameException($data->name);
        }

        $project = new Project([
            'name' => $data->name,
            'description' => $data->description,
            'owner_id' => $data->ownerId,
        ]);

        return $this->projects->save($project);
    }
}
