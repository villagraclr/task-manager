<?php

declare(strict_types=1);

namespace Tests\Feature\Projects;

use App\Exceptions\CannotAddOwnerAsMemberException;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectMembersTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_add_a_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        app(ProjectService::class)->addMember($project, $member);

        $this->assertTrue($project->members()->where('user_id', $member->id)->exists());
    }

    public function test_cannot_add_owner_as_a_member(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $this->expectException(CannotAddOwnerAsMemberException::class);

        app(ProjectService::class)->addMember($project, $owner);
    }

    public function test_owner_can_remove_a_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $service = app(ProjectService::class);
        $service->addMember($project, $member);
        $service->removeMember($project, $member);

        $this->assertFalse($project->members()->where('user_id', $member->id)->exists());
    }

    public function test_owner_is_considered_a_member(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $this->assertTrue(app(ProjectService::class)->isMember($project, $owner));
    }

    public function test_unrelated_user_is_not_a_member(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $this->assertFalse(app(ProjectService::class)->isMember($project, $stranger));
    }

    public function test_only_owner_can_manage_members(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $this->assertTrue($owner->can('manageMembers', $project));
        $this->assertFalse($stranger->can('manageMembers', $project));
    }

    public function test_adding_same_member_twice_does_not_duplicate(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $owner->id]);

        $service = app(ProjectService::class);
        $service->addMember($project, $member);
        $service->addMember($project, $member);

        $this->assertSame(1, $project->members()->where('user_id', $member->id)->count());
    }
}
