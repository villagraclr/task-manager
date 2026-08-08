<?php

declare(strict_types=1);

namespace Tests\Feature\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_their_project(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create([
            'owner_id' => $owner->id,
            'name' => 'Nombre Original',
        ]);

        Livewire::actingAs($owner)
            ->test('pages::projects.edit', ['project' => $project])
            ->set('name', 'Nombre Actualizado')
            ->set('description', 'Nueva descripción')
            ->call('save')
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Nombre Actualizado',
            'description' => 'Nueva descripción',
        ]);
    }

    public function test_non_owner_cannot_access_edit_page(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        Livewire::actingAs($stranger)
            ->test('pages::projects.edit', ['project' => $project])
            ->assertForbidden();
    }

    public function test_cannot_rename_to_a_name_already_used_by_another_own_project(): void
    {
        $owner = User::factory()->create();
        Project::factory()->create(['owner_id' => $owner->id, 'name' => 'Proyecto A']);
        $projectB = Project::factory()->create(['owner_id' => $owner->id, 'name' => 'Proyecto B']);

        Livewire::actingAs($owner)
            ->test('pages::projects.edit', ['project' => $projectB])
            ->set('name', 'Proyecto A')
            ->call('save')
            ->assertHasErrors('name');

        $this->assertDatabaseHas('projects', ['id' => $projectB->id, 'name' => 'Proyecto B']);
    }

    public function test_can_keep_its_own_current_name_when_updating(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id, 'name' => 'Mi Proyecto']);

        Livewire::actingAs($owner)
            ->test('pages::projects.edit', ['project' => $project])
            ->set('name', 'Mi Proyecto')
            ->set('description', 'Descripción actualizada')
            ->call('save')
            ->assertHasNoErrors();
    }
}
