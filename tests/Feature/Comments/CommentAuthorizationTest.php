<?php

declare(strict_types=1);

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_owner_can_create_comment_on_task(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $this->assertTrue($owner->can('create', [Comment::class, $task]));
    }

    public function test_assigned_user_can_create_comment_on_task(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $assignee->id,
        ]);

        $this->assertTrue($assignee->can('create', [Comment::class, $task]));
    }

    public function test_unrelated_user_cannot_create_comment_on_task(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
        ]);

        $this->assertFalse($stranger->can('create', [Comment::class, $task]));
    }

    public function test_author_can_delete_own_comment(): void
    {
        $owner = User::factory()->create();
        $author = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $author->id,
        ]);
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $author->id,
        ]);

        $this->assertTrue($author->can('delete', $comment));
    }

    public function test_project_owner_can_delete_any_comment_on_their_project(): void
    {
        $owner = User::factory()->create();
        $author = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $author->id,
        ]);
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $author->id,
        ]);

        $this->assertTrue($owner->can('delete', $comment));
    }

    public function test_unrelated_user_cannot_delete_someone_elses_comment(): void
    {
        $owner = User::factory()->create();
        $author = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $owner->id,
            'assigned_to' => $author->id,
        ]);
        $comment = Comment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $author->id,
        ]);

        $this->assertFalse($stranger->can('delete', $comment));
    }
}
