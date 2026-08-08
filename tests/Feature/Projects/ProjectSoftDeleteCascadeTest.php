<?php

declare(strict_types=1);

namespace Tests\Feature\Projects;

use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Task\Domain\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectSoftDeleteCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_project_soft_deletes_its_tasks(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $project->delete();

        $this->assertSoftDeleted($task);
        $this->assertSoftDeleted($project);
    }

    public function test_restoring_project_restores_its_tasks(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $project->delete();
        $project->restore();

        $this->assertNotSoftDeleted($task->fresh());
    }

    public function test_force_deleting_project_does_not_run_cascade_soft_delete_logic(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $project->forceDelete();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
