<?php

declare(strict_types=1);

use App\Modules\Comment\Application\DTOs\CreateCommentData;
use App\Modules\Comment\Application\UseCases\AddComment;
use App\Modules\Comment\Application\UseCases\DeleteComment;
use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Task\Application\UseCases\DeleteTask;
use App\Modules\Task\Domain\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    private DeleteTask $deleteTask;
    private AddComment $addComment;
    private DeleteComment $deleteComment;

    public Task $task;

    public string $newComment = '';

    public function boot(DeleteTask $deleteTask, AddComment $addComment, DeleteComment $deleteComment): void
    {
        $this->deleteTask = $deleteTask;
        $this->addComment = $addComment;
        $this->deleteComment = $deleteComment;
    }

    public function mount(Task $task): void
    {
        $this->authorize('view', $task);

        $this->task = $task->load(['project', 'createdBy', 'assignedTo', 'comments.author']);
    }

    public function addCommentToTask(): void
    {
        $this->authorize('create', [Comment::class, $this->task]);

        $this->validate([
            'newComment' => 'required|string|max:2000',
        ]);

        $this->addComment->handle(CreateCommentData::fromArray([
            'task_id' => $this->task->id,
            'user_id' => Auth::id(),
            'body' => $this->newComment,
        ]));

        $this->newComment = '';
        $this->task->load('comments.author');
    }

    public function removeComment(Comment $comment): void
    {
        $this->authorize('delete', $comment);

        $this->deleteComment->handle($comment);

        $this->task->load('comments.author');
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->task);

        $this->deleteTask->handle($this->task);

        session()->flash('status', "Tarea \"{$this->task->title}\" eliminada.");
        $this->redirectRoute('projects.show', $this->task->project, navigate: true);
    }
};
?>

<div>
    {{-- ... mismo HTML de layered para título, badges, descripción, creado/asignado ... --}}

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
                                wire:click="removeComment({{ $comment->id }})"
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
        <form wire:submit="addCommentToTask" class="space-y-2">
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