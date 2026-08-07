<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Data\Project\CreateProjectData;
use App\Data\Project\UpdateProjectData;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function all(): Collection
    {
        return Project::query()
            ->get();
    }

    public function getByOwner(int $ownerId): Collection
    {
        return Project::query()
            ->where('owner_id', $ownerId)
            ->latest()
            ->get();
    }

    public function findById(int $id): ?Project
    {
        return Project::query()
            ->find($id);
    }

    public function create(CreateProjectData $data): Project
    {
        return Project::query()
            ->create($data->toArray());
    }

    public function update(Project $project, UpdateProjectData $data): Project
    {
        $project->update($data->toArray());
        return $project->fresh();
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }

    public function getMembers(Project $project): Collection
    {
        return $project->members()->get();
    }

    public function addMember(Project $project, User $user): void
    {
        $project->members()->syncWithoutDetaching($user->id);
    }

    public function removeMember(Project $project, User $user): void
    {
        $project->members()->detach($user->id);
    }

    public function isMember(Project $project, int $userId): bool
    {
        return $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();
    }
}
