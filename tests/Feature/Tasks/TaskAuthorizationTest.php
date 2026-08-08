<?php

declare(strict_types=1);

namespace Tests\Feature\Tasks;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_owner_can_view_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $this->assertTrue($owner->can('view', $task));
    }

    public function test_assigned_user_can_view_task(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $assignee->id,
        ]);

        $this->assertTrue($assignee->can('view', $task));
    }

    public function test_unrelated_user_cannot_view_task(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $this->assertFalse($stranger->can('view', $task));
    }

    public function test_assigned_user_can_update_task_but_not_delete_it(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $assignee->id,
        ]);

        $this->assertTrue($assignee->can('update', $task));
        $this->assertFalse($assignee->can('delete', $task));
    }

    public function test_only_project_owner_can_delete_task(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $assignee->id,
        ]);

        $this->assertTrue($owner->can('delete', $task));
        $this->assertFalse($assignee->can('delete', $task));
    }
}
