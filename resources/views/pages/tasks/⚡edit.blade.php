<?php

declare(strict_types=1);

use App\Data\Task\UpdateTaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Livewire\Component;

new class extends Component
{
    private TaskService $taskService;

    public Task $task;
    public Project $project;

    public string $title = '';
    public string $description = '';
    public string $status = '';
    public string $priority = '';
    public ?int $assigned_to = null;
    public ?string $due_date = null;

    public function boot(TaskService $taskService): void
    {
        $this->taskService = $taskService;
    }

    public function mount(Task $task): void
    {
        $this->authorize('update', $task);

        $this->task = $task;
        $this->project = $task->project;
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->status = $task->status->value;
        $this->priority = $task->priority->value;
        $this->assigned_to = $task->assigned_to;
        $this->due_date = $task->due_date?->toDateString();
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'due_date' => 'nullable|date',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = UpdateTaskData::fromArray([
            'assigned_to' => $this->assigned_to,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
        ]);

        $task = $this->taskService->update($this->task, $data);

        session()->flash('status', "Tarea \"{$task->title}\" actualizada.");

        $this->redirectRoute('tasks.show', $task, navigate: true);
    }

    public function with(): array
    {
        return [
            'statusOptions' => TaskStatus::options(),
            'priorityOptions' => TaskPriority::options(),
            'users' => $this->project->members->push($this->project->owner)->unique('id'),
        ];
    }
};
?>

<div>
    <h1 class="text-2xl font-semibold mb-6">Editar tarea</h1>

    <form wire:submit="save" class="max-w-lg space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium mb-1">Título</label>
            <input id="title" type="text" wire:model="title" class="w-full rounded border px-3 py-2">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Descripción</label>
            <textarea id="description" wire:model="description" rows="4" class="w-full rounded border px-3 py-2"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium mb-1">Estado</label>
                <select id="status" wire:model="status" class="w-full rounded border px-3 py-2">
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium mb-1">Prioridad</label>
                <select id="priority" wire:model="priority" class="w-full rounded border px-3 py-2">
                    @foreach ($priorityOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="assigned_to" class="block text-sm font-medium mb-1">Asignar a</label>
                <select id="assigned_to" wire:model="assigned_to" class="w-full rounded border px-3 py-2">
                    <option value="">Sin asignar</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="due_date" class="block text-sm font-medium mb-1">Fecha límite</label>
                <input id="due_date" type="date" wire:model="due_date" class="w-full rounded border px-3 py-2">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="rounded bg-blue-600 px-4 py-2 hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Guardar cambios</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>

            <a href="{{ route('tasks.show', $task) }}" wire:navigate class="text-sm text-gray-600 hover:underline">
                Cancelar
            </a>
        </div>
    </form>
</div>
