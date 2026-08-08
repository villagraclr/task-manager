<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use App\Modules\Project\Application\DTOs\CreateProjectData;
use App\Modules\Project\Application\UseCases\AddProjectMember;
use App\Modules\Project\Application\UseCases\CreateProject;
use App\Modules\Task\Application\DTOs\CreateTaskData;
use App\Modules\Task\Application\UseCases\CreateTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $luis = User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis@test.cl',
        ]);

        $santiago = User::factory()->create([
            'name' => 'Santiago',
            'email' => 'santiago@test.cl',
        ]);

        $createProject = app(CreateProject::class);
        $addProjectMember = app(AddProjectMember::class);
        $createTask = app(CreateTask::class);

        $project = $createProject->handle(CreateProjectData::fromArray([
            'name' => 'Task Manager - Pruebas',
            'description' => 'Proyecto de prueba para validar CRUD, soft deletes y comentarios',
            'owner_id' => $luis->id,
        ]));

        $addProjectMember->handle($project, $santiago);

        $createTask->handle(CreateTaskData::fromArray([
            'project_id' => $project->id,
            'created_by' => $luis->id,
            'assigned_to' => $santiago->id,
            'title' => 'Configurar entorno de desarrollo',
            'description' => 'Instalar dependencias y validar conexión a BD',
            'status' => TaskStatus::PENDING->value,
            'priority' => TaskPriority::MEDIUM->value,
            'due_date' => now()->addWeek()->toDateString(),
        ]));
    }
}
