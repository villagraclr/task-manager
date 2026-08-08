<?php

declare(strict_types=1);

use App\Data\Task\CreateTaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    private TaskService $taskService;

    public Project $project;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:2000')]
    public string $description = '';

    #[Validate('required|string')]
    public string $status = '';

    #[Validate('required|string')]
    public string $priority = '';

    #[Validate('nullable|integer|exists:users,id')]
    public ?int $assigned_to = null;

    #[Validate('nullable|date')]
    public ?string $due_date = null;

    public function boot(TaskService $taskService): void
    {
        $this->taskService = $taskService;
    }

    public function mount(Project $project): void
    {
        $this->authorize('create', [Task::class, $project]);

        $this->project = $project;
        $this->status = TaskStatus::PENDING->value;
        $this->priority = TaskPriority::MEDIUM->value;
    }

    public function save(): void
    {
        $this->validate();

        $data = CreateTaskData::fromArray([
            'project_id' => $this->project->id,
            'created_by' => Auth::id(),
            'assigned_to' => $this->assigned_to,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
        ]);

        $task = $this->taskService->create($data);

        session()->flash('status', "Tarea \"{$task->title}\" creada.");

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
    <h1 class="text-2xl font-semibold mb-1">Nueva tarea</h1>
    <p class="text-gray-500 mb-6">Proyecto: {{ $project->name }}</p>

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
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
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
                @error('due_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Crear tarea</span>
                <span wire:loading wire:target="save">Creando...</span>
            </button>

            <a href="{{ route('projects.show', $project) }}" wire:navigate class="text-sm text-gray-600 hover:underline">
                Cancelar
            </a>
        </div>
    </form>
</div>
