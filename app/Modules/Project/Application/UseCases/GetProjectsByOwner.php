<?php

declare(strict_types=1);

namespace App\Modules\Project\Application\UseCases;

use App\Modules\Project\Domain\Ports\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class GetProjectsByOwner
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects
    ) {}

    public function handle(int $ownerId): Collection
    {
        return $this->projects->getByOwner($ownerId);
    }
}
