<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Data\Project\CreateProjectData;
use App\Data\Task\CreateTaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use App\Services\ProjectService;
use App\Services\TaskService;
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

        $projectService = app(ProjectService::class);
        $taskService = app(TaskService::class);

        $project = $projectService->create(CreateProjectData::fromArray([
            'name' => 'Task Manager - Pruebas',
            'description' => 'Proyecto de prueba para validar CRUD, soft deletes y comentarios',
            'owner_id' => $luis->id,
        ]));

        $projectService->addMember($project, $santiago);

        $taskService->create(CreateTaskData::fromArray([
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
