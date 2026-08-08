<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

new class extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        $this->authorize('view', $project);

        $this->project = $project;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->project);

        app(\App\Services\ProjectService::class)->delete($this->project);

        session()->flash('status', "Proyecto \"{$this->project->name}\" eliminado.");

        $this->redirectRoute('projects.index', navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $project->name }}</h1>
            @if ($project->description)
                <p class="text-gray-600 mt-1">{{ $project->description }}</p>
            @endif
        </div>

        <div class="flex gap-2">
            @can('manageMembers', $project)
                <a href="{{ route('projects.members', $project) }}"
                   wire:navigate
                   class="rounded border px-4 py-2 hover:bg-gray-50">
                    Miembros
                </a>
            @endcan
            <a href="{{ route('projects.edit', $project) }}"
               wire:navigate
               class="rounded border px-4 py-2 hover:bg-gray-50">
                Editar
            </a>
            <button
                wire:click="delete"
                wire:confirm="¿Eliminar el proyecto '{{ $project->name }}'? Esta acción no se puede deshacer."
                class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                Eliminar
            </button>
        </div>
    </div>

    <div class="mb-4 text-sm text-gray-500">
        Dueño:
        {{ $project->owner->trashed() ? $project->owner->name . ' (eliminado)' : $project->owner->name }}
        · Creado el {{ $project->created_at->format('d-m-Y') }}
    </div>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium">Tareas ({{ $project->tasks()->count() }})</h2>
        @can('create', [Task::class, $project])
            <a href="{{ route('tasks.create', $project) }}" wire:navigate class="text-sm text-blue-600 hover:underline">
                + Nueva tarea
            </a>
        @endcan
    </div>

    @forelse ($project->tasks as $task)
        <div wire:key="task-{{ $task->id }}" class="border-b py-2 flex items-center justify-between">
            <a href="{{ route('tasks.show', $task) }}" wire:navigate class="hover:underline">
                {{ $task->title }}
            </a>
            <div class="flex gap-2">
                <x-priority-badge :priority="$task->priority" />
                <x-status-badge :status="$task->status" />
            </div>
        </div>
    @empty
        <p class="text-gray-500">Este proyecto todavía no tiene tareas.</p>
    @endforelse

    <a href="{{ route('projects.index') }}" wire:navigate class="inline-block mt-6 text-sm text-gray-600 hover:underline">
        ← Volver a proyectos
    </a>
</div>
