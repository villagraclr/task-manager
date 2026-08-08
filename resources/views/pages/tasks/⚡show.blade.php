<?php

declare(strict_types=1);

use App\Data\Comment\CreateCommentData;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    private TaskService $taskService;
    private CommentService $commentService;

    public Task $task;

    public string $newComment = '';

    public function boot(TaskService $taskService, CommentService $commentService): void
    {
        $this->taskService = $taskService;
        $this->commentService = $commentService;
    }

    public function mount(Task $task): void
    {
        $this->authorize('view', $task);

        $this->task = $task->load(['project', 'createdBy', 'assignedTo', 'comments.author']);
    }

    public function addComment(): void
    {
        $this->authorize('create', [Comment::class, $this->task]);

        $this->validate([
            'newComment' => 'required|string|max:2000',
        ]);

        $this->commentService->create(CreateCommentData::fromArray([
            'task_id' => $this->task->id,
            'user_id' => Auth::id(),
            'body' => $this->newComment,
        ]));

        $this->newComment = '';
        $this->task->load('comments.author');
    }

    public function deleteComment(Comment $comment): void
    {
        $this->authorize('delete', $comment);

        $this->commentService->delete($comment);

        $this->task->load('comments.author');
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->task);

        $this->taskService->delete($this->task);

        session()->flash('status', "Tarea \"{$this->task->title}\" eliminada.");

        $this->redirectRoute('projects.show', $this->task->project, navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-semibold">{{ $task->title }}</h1>

        <div class="flex gap-2">
            @can('update', $task)
                <a href="{{ route('tasks.edit', $task) }}" wire:navigate class="rounded border px-4 py-2 hover:bg-gray-50">
                    Editar
                </a>
            @endcan
            @can('delete', $task)
                <button
                    wire:click="delete"
                    wire:confirm="¿Eliminar la tarea '{{ $task->title }}'?"
                    class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                    Eliminar
                </button>
            @endcan
        </div>
    </div>

    <p class="text-sm text-gray-500 mb-4">
        <a href="{{ route('projects.show', $task->project) }}" wire:navigate class="hover:underline">
            {{ $task->project->name }}
        </a>
    </p>

    <div class="flex gap-2 mb-4">
        <span class="rounded bg-{{ $task->priority->color() }}-100 text-{{ $task->priority->color() }}-800 px-2 py-1 text-xs">
            {{ $task->priority->label() }}
        </span>
        <span class="rounded bg-gray-100 px-2 py-1 text-xs">
            {{ $task->status->label() }}
        </span>
        @if ($task->due_date)
            <span class="rounded bg-gray-100 px-2 py-1 text-xs">
                Vence: {{ $task->due_date->format('d-m-Y') }}
            </span>
        @endif
    </div>

    @if ($task->description)
        <p class="text-gray-700 mb-4">{{ $task->description }}</p>
    @endif

    <div class="text-sm text-gray-500 mb-6 space-y-1">
        <p>Creado por: {{ $task->createdBy->name }}{{ $task->createdBy->trashed() ? ' (eliminado)' : '' }}</p>
        <p>Asignado a: {{ $task->assignedTo?->name ?? 'Sin asignar' }}{{ $task->assignedTo?->trashed() ? ' (eliminado)' : '' }}</p>
    </div>

    <hr class="my-6">

    <h2 class="text-lg font-medium mb-4">Comentarios ({{ $task->comments->count() }})</h2>

    <div class="space-y-3 mb-6">
        @forelse ($task->comments as $comment)
            <div wire:key="comment-{{ $comment->id }}" class="rounded border p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium">{{ $comment->author->name }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        @can('delete', $comment)
                            <button
                                wire:click="deleteComment({{ $comment->id }})"
                                wire:confirm="¿Eliminar este comentario?"
                                class="text-xs text-red-600 hover:underline">
                                Eliminar
                            </button>
                        @endcan
                    </div>
                </div>
                <p class="text-gray-700 text-sm">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Todavía no hay comentarios.</p>
        @endforelse
    </div>

    @can('create', [Comment::class, $task])
        <form wire:submit="addComment" class="space-y-2">
            <label for="newComment" class="sr-only">Nuevo comentario</label>
            <textarea
                id="newComment"
                wire:model="newComment"
                rows="3"
                placeholder="Escribe un comentario..."
                class="w-full rounded border px-3 py-2"
            ></textarea>
            @error('newComment')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Comentar
            </button>
        </form>
    @endcan

    <a href="{{ route('projects.show', $task->project) }}" wire:navigate class="inline-block mt-6 text-sm text-gray-600 hover:underline">
        ← Volver al proyecto
    </a>
</div>
