<?php

declare(strict_types=1);

use App\Modules\Project\Application\UseCases\DeleteProject;
use App\Modules\Project\Application\UseCases\GetProjectsByOwner;
use App\Modules\Project\Domain\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    private GetProjectsByOwner $getProjectsByOwner;
    private DeleteProject $deleteProject;

    public string $search = '';

    public function boot(GetProjectsByOwner $getProjectsByOwner, DeleteProject $deleteProject): void
    {
        $this->getProjectsByOwner = $getProjectsByOwner;
        $this->deleteProject = $deleteProject;
    }

    #[Computed]
    public function projects()
    {
        $projects = $this->getProjectsByOwner->handle(Auth::id());

        if ($this->search === '') {
            return $projects;
        }

        return $projects->filter(
            fn (Project $project) => str_contains(
                strtolower($project->name),
                strtolower($this->search)
            )
        );
    }

    public function delete(Project $project): void
    {
        $this->authorize('delete', $project);

        $this->deleteProject->handle($project);

        session()->flash('status', "Proyecto \"{$project->name}\" eliminado.");

        unset($this->projects);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Proyectos</h1>

        <a href="{{ route('projects.create') }}"
           wire:navigate
           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Nuevo proyecto
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4">
        <label for="project-search" class="sr-only">Buscar proyectos por nombre</label>
        <input
            id="project-search"
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre..."
            class="w-full max-w-sm rounded border px-3 py-2"
        >
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b text-left">
                <th class="py-2">Nombre</th>
                <th class="py-2">Descripción</th>
                <th class="py-2">Tareas</th>
                <th class="py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($this->projects as $project)
                <tr wire:key="project-{{ $project->id }}" class="border-b">
                    <td class="py-2">
                        <a href="{{ route('projects.show', $project) }}"
                           wire:navigate
                           class="text-blue-600 hover:underline">
                            {{ $project->name }}
                        </a>
                    </td>
                    <td class="py-2 text-gray-600">
                        {{ Str::limit($project->description, 60) }}
                    </td>
                    <td class="py-2">{{ $project->tasks()->count() }}</td>
                    <td class="py-2 text-right space-x-2">
                        <a href="{{ route('projects.edit', $project) }}"
                           wire:navigate
                           class="text-sm text-gray-600 hover:underline">
                            Editar
                        </a>
                        <button
                            wire:click="delete({{ $project->id }})"
                            wire:confirm="¿Eliminar el proyecto '{{ $project->name }}'? Esta acción no se puede deshacer."
                            class="text-sm text-red-600 hover:underline">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-500">
                        No tienes proyectos todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
