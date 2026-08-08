<?php

declare(strict_types=1);

namespace App\Modules\Project\Domain\Ports;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function all(): Collection;

    public function getByOwner(int $ownerId): Collection;

    public function findById(int $id): ?Project;

    public function save(Project $project): Project;

    public function delete(Project $project): bool;

    public function getMembers(Project $project): Collection;

    public function addMember(Project $project, User $user): void;

    public function removeMember(Project $project, User $user): void;

    public function isMember(Project $project, int $userId): bool;
}
