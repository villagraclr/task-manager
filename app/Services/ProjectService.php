<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Project\CreateProjectData;
use App\Data\Project\UpdateProjectData;
use App\Exceptions\CannotAddOwnerAsMemberException;
use App\Exceptions\DuplicateProjectNameException;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->projectRepository->all();
    }

    public function getByOwner(int $ownerId): Collection
    {
        return $this->projectRepository->getByOwner($ownerId);
    }

    public function getById(int $id): ?Project
    {
        return $this->projectRepository->findById($id);
    }

    public function create(CreateProjectData $data): Project
    {
        $this->ensureUniqueName($data->name, $data->ownerId);

        return $this->projectRepository->create($data);
    }

    public function update(Project $project, UpdateProjectData $data): Project
    {
        $this->ensureUniqueName($data->name, $project->owner_id, ignoreId: $project->id);

        return $this->projectRepository->update($project, $data);
    }

    public function delete(Project $project): bool
    {
        return $this->projectRepository->delete($project);
    }

    public function getMembers(Project $project): Collection
    {
        return $this->projectRepository->getMembers($project);
    }

    public function addMember(Project $project, User $user): void
    {
        if ($user->id === $project->owner_id) {
            throw new CannotAddOwnerAsMemberException;
        }

        $this->projectRepository->addMember($project, $user);
    }

    public function removeMember(Project $project, User $user): void
    {
        $this->projectRepository->removeMember($project, $user);
    }

    public function isMember(Project $project, User $user): bool
    {
        return $this->projectRepository->isMember($project, $user->id);
    }

    private function ensureUniqueName(string $name, int $ownerId, ?int $ignoreId = null): void
    {
        $exists = Project::query()
            ->where('owner_id', $ownerId)
            ->where('name', $name)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            throw new DuplicateProjectNameException($name);
        }
    }
}
