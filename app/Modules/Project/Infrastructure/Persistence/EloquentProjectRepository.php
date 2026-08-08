<?php

declare(strict_types=1);

namespace App\Modules\Project\Infrastructure\Persistence;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function all(): Collection
    {
        return Project::all();
    }

    public function getByOwner(int $ownerId): Collection
    {
        return Project::where('owner_id', $ownerId)->get();
    }

    public function findById(int $id): ?Project
    {
        return Project::find($id);
    }

    public function save(Project $project): Project
    {
        $project->save();

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
