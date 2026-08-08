<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Data\Project\CreateProjectData;
use App\Exceptions\DuplicateProjectNameException;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_throws_exception_when_name_already_exists_for_owner(): void
    {
        $owner = User::factory()->create();

        Project::factory()->create([
            'owner_id' => $owner->id,
            'name' => 'Proyecto Duplicado',
        ]);

        $service = new ProjectService(
            app(ProjectRepositoryInterface::class)
        );

        $data = CreateProjectData::fromArray([
            'name' => 'Proyecto Duplicado',
            'description' => null,
            'owner_id' => $owner->id,
        ]);

        $this->expectException(DuplicateProjectNameException::class);

        $service->create($data);
    }

    public function test_create_allows_same_name_for_different_owners(): void
    {
        $ownerA = User::factory()->create();
        $ownerB = User::factory()->create();

        Project::factory()->create([
            'owner_id' => $ownerA->id,
            'name' => 'Proyecto Compartido',
        ]);

        $service = new ProjectService(
            app(ProjectRepositoryInterface::class)
        );

        $project = $service->create(CreateProjectData::fromArray([
            'name' => 'Proyecto Compartido',
            'description' => null,
            'owner_id' => $ownerB->id,
        ]));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'owner_id' => $ownerB->id,
            'name' => 'Proyecto Compartido',
        ]);
    }
}
