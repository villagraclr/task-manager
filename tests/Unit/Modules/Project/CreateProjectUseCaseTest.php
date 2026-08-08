<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Project;

use App\Models\User;
use App\Modules\Project\Application\DTOs\CreateProjectData;
use App\Modules\Project\Application\UseCases\CreateProject;
use App\Modules\Project\Domain\Exceptions\DuplicateProjectNameException;
use App\Modules\Project\Domain\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProjectUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_throws_exception_when_name_already_exists_for_owner(): void
    {
        $owner = User::factory()->create();

        Project::factory()->create([
            'owner_id' => $owner->id,
            'name' => 'Proyecto Duplicado',
        ]);

        $useCase = app(CreateProject::class);

        $data = CreateProjectData::fromArray([
            'name' => 'Proyecto Duplicado',
            'description' => null,
            'owner_id' => $owner->id,
        ]);

        $this->expectException(DuplicateProjectNameException::class);

        $useCase->handle($data);
    }

    public function test_allows_same_name_for_different_owners(): void
    {
        $ownerA = User::factory()->create();
        $ownerB = User::factory()->create();

        Project::factory()->create([
            'owner_id' => $ownerA->id,
            'name' => 'Proyecto Compartido',
        ]);

        $useCase = app(CreateProject::class);

        $project = $useCase->handle(CreateProjectData::fromArray([
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
