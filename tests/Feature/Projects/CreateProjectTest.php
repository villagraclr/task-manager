<?php

declare(strict_types=1);

namespace Tests\Feature\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_create_page(): void
    {
        $this->get(route('projects.create'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_a_project(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::projects.create')
            ->set('name', 'Mi Primer Proyecto')
            ->set('description', 'Descripción de prueba')
            ->call('save')
            ->assertRedirect(route('projects.show', Project::first()));

        $this->assertDatabaseHas('projects', [
            'name' => 'Mi Primer Proyecto',
            'owner_id' => $user->id,
        ]);
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::projects.create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_cannot_create_project_with_duplicate_name_for_same_owner(): void
    {
        $user = User::factory()->create();

        Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'Proyecto Existente',
        ]);

        Livewire::actingAs($user)
            ->test('pages::projects.create')
            ->set('name', 'Proyecto Existente')
            ->call('save')
            ->assertHasErrors('name');

        $this->assertDatabaseCount('projects', 1);
    }
}
