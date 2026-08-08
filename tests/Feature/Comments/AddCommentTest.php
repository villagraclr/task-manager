<?php

declare(strict_types=1);

namespace Tests\Feature\Comments;

use App\Models\User;
use App\Modules\Comment\Domain\Models\Comment;
use App\Modules\Project\Domain\Models\Project;
use App\Modules\Task\Domain\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_add_comment_to_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test('pages::tasks.show', ['task' => $task])
            ->set('newComment', 'Este es un comentario de prueba')
            ->call('addCommentToTask')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'user_id' => $owner->id,
            'body' => 'Este es un comentario de prueba',
        ]);
    }

    public function test_comment_body_is_required(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test('pages::tasks.show', ['task' => $task])
            ->set('newComment', '')
            ->call('addCommentToTask')
            ->assertHasErrors(['newComment' => 'required']);
    }

    public function test_author_can_delete_own_comment_from_task_view(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $owner->id,
        ]);

        Livewire::actingAs($owner)
            ->test('pages::tasks.show', ['task' => $task])
            ->call('removeComment', $comment->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_unrelated_user_cannot_delete_someone_elses_comment(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $owner->id,
        ]);

        Livewire::actingAs($stranger)
            ->test('pages::tasks.show', ['task' => $task])
            ->assertForbidden();
    }
}
