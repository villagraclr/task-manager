<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Data\Project\CreateProjectData;
use App\Data\Project\UpdateProjectData;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function all(): Collection;

    public function getByOwner(int $ownerId): Collection;

    public function findById(int $id): ?Project;

    public function create(CreateProjectData $data): Project;

    public function update(Project $project, UpdateProjectData $data): Project;

    public function delete(Project $project): bool;

    public function getMembers(Project $project): Collection;

    public function addMember(Project $project, User $user): void;

    public function removeMember(Project $project, User $user): void;

    public function isMember(Project $project, int $userId): bool;
}
