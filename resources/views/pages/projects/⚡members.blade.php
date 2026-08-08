<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\Project\Application\UseCases\AddProjectMember;
use App\Modules\Project\Application\UseCases\GetProjectMembers;
use App\Modules\Project\Application\UseCases\RemoveProjectMember;
use App\Modules\Project\Domain\Exceptions\CannotAddOwnerAsMemberException;
use App\Modules\Project\Domain\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    private AddProjectMember $addProjectMember;
    private RemoveProjectMember $removeProjectMember;
    private GetProjectMembers $getProjectMembers;

    public Project $project;

    #[Validate('required|email')]
    public string $email = '';

    public function boot(
        AddProjectMember $addProjectMember,
        RemoveProjectMember $removeProjectMember,
        GetProjectMembers $getProjectMembers
    ): void {
        $this->addProjectMember = $addProjectMember;
        $this->removeProjectMember = $removeProjectMember;
        $this->getProjectMembers = $getProjectMembers;
    }

    public function mount(Project $project): void
    {
        $this->authorize('manageMembers', $project);

        $this->project = $project;
    }

    public function addMember(): void
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'No existe un usuario con ese correo.');
            return;
        }

        try {
            $this->addProjectMember->handle($this->project, $user);
        } catch (CannotAddOwnerAsMemberException $e) {
            $this->addError('email', $e->getMessage());
            return;
        }

        $this->email = '';
        session()->flash('status', "{$user->name} agregado al proyecto.");
    }

    public function removeMember(User $user): void
    {
        $this->authorize('manageMembers', $this->project);

        $this->removeProjectMember->handle($this->project, $user);

        session()->flash('status', "{$user->name} eliminado del proyecto.");
    }

    public function with(): array
    {
        return [
            'members' => $this->getProjectMembers->handle($this->project),
        ];
    }
};
?>

<div>
    <h1 class="text-2xl font-semibold mb-1">Miembros del proyecto</h1>
    <p class="text-gray-500 mb-6">{{ $project->name }}</p>

    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 px-4 py-2 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="addMember" class="max-w-md mb-8 space-y-2">
        <label for="email" class="block text-sm font-medium mb-1">Agregar miembro por correo</label>
        <div class="flex gap-2">
            <input
                id="email"
                type="email"
                wire:model="email"
                placeholder="usuario@ejemplo.cl"
                class="flex-1 rounded border px-3 py-2"
            >
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Agregar
            </button>
        </div>
        @error('email')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </form>

    <h2 class="text-lg font-medium mb-4">Miembros actuales ({{ $members->count() }})</h2>

    <div class="space-y-2 max-w-md">
        <div class="flex items-center justify-between rounded border p-3 bg-gray-50">
            <div>
                <span class="font-medium">{{ $project->owner->name }}</span>
                <span class="text-sm text-gray-500 ml-2">Dueño</span>
            </div>
        </div>

        @forelse ($members as $member)
            <div wire:key="member-{{ $member->id }}" class="flex items-center justify-between rounded border p-3">
                <span>{{ $member->name }}</span>
                <button
                    wire:click="removeMember({{ $member->id }})"
                    wire:confirm="¿Quitar a {{ $member->name }} del proyecto?"
                    class="text-sm text-red-600 hover:underline">
                    Quitar
                </button>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Solo el dueño tiene acceso por ahora.</p>
        @endforelse
    </div>

    <a href="{{ route('projects.show', $project) }}" wire:navigate class="inline-block mt-6 text-sm text-gray-600 hover:underline">
        ← Volver al proyecto
    </a>
</div>
