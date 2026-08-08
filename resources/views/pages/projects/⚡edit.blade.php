<?php

declare(strict_types=1);

use App\Modules\Project\Application\DTOs\UpdateProjectData;
use App\Modules\Project\Application\UseCases\UpdateProject;
use App\Modules\Project\Domain\Exceptions\DuplicateProjectNameException;
use App\Modules\Project\Domain\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    private UpdateProject $updateProject;

    public Project $project;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:2000')]
    public string $description = '';

    public function boot(UpdateProject $updateProject): void
    {
        $this->updateProject = $updateProject;
    }

    public function mount(Project $project): void
    {
        $this->authorize('update', $project);

        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
    }

    public function save(): void
    {
        $this->validate();

        try {
            $project = $this->updateProject->handle($this->project, UpdateProjectData::fromArray([
                'name' => $this->name,
                'description' => $this->description ?: null,
            ]));
        } catch (DuplicateProjectNameException $e) {
            $this->addError('name', $e->getMessage());
            return;
        }

        session()->flash('status', "Proyecto \"{$project->name}\" actualizado.");
        $this->redirectRoute('projects.show', $project, navigate: true);
    }
};
?>

<div>
    <h1 class="text-2xl font-semibold mb-6">Editar proyecto</h1>

    <form wire:submit="save" class="max-w-lg space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nombre</label>
            <input
                id="name"
                type="text"
                wire:model="name"
                class="w-full rounded border px-3 py-2"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium mb-1">Descripción</label>
            <textarea
                id="description"
                wire:model="description"
                rows="4"
                class="w-full rounded border px-3 py-2"
            ></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Guardar cambios</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>

            <a href="{{ route('projects.show', $project) }}" wire:navigate class="text-sm text-gray-600 hover:underline">
                Cancelar
            </a>
        </div>
    </form>
</div>
