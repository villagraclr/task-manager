<?php

declare(strict_types=1);

namespace Tests\Feature\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Task\Domain\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_owner_can_create_a_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($owner)
            ->test('pages::tasks.create', ['project' => $project])
            ->set('title', 'Tarea de prueba')
            ->set('status', TaskStatus::PENDING->value)
            ->set('priority', TaskPriority::MEDIUM->value)
            ->call('save')
            ->assertRedirect(route('tasks.show', Task::first()));

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'title' => 'Tarea de prueba',
        ]);
    }

    public function test_non_owner_cannot_create_a_task_in_someone_elses_project(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($intruder)
            ->test('pages::tasks.create', ['project' => $project])
            ->assertForbidden();
    }

    public function test_title_is_required(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($owner)
            ->test('pages::tasks.create', ['project' => $project])
            ->set('title', '')
            ->set('status', TaskStatus::PENDING->value)
            ->set('priority', TaskPriority::MEDIUM->value)
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }
}
