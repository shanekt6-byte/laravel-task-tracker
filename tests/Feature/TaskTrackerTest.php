<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTrackerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Valid task creation persists in the database.
     */
    public function test_can_create_a_valid_task(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => 'Complete Laravel Assessment',
            'description' => 'Cover all 5 phases thoroughly',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Complete Laravel Assessment',
            'description' => 'Cover all 5 phases thoroughly',
            'status' => 'pending',
        ]);
    }

    /**
     * Test 2: Empty title is rejected by validation.
     */
    public function test_title_is_required_for_creating_task(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title' => '',
            'description' => 'No title provided',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test 3: Invalid status is rejected by validation.
     */
    public function test_invalid_status_is_rejected(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'status' => 'pending',
        ]);

        $response = $this->patch(route('tasks.update', $task), [
            'status' => 'invalid_status_value',
        ]);

        $response->assertSessionHasErrors('status');

        // Status should remain unchanged in database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 4: Status transition from pending to completed.
     */
    public function test_task_status_transitions_from_pending_to_completed(): void
    {
        $task = Task::create([
            'title' => 'Pending Task',
            'status' => 'pending',
        ]);

        $response = $this->patch(route('tasks.update', $task), [
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test 5: Status transition from completed back to pending.
     */
    public function test_task_status_transitions_from_completed_to_pending(): void
    {
        $task = Task::create([
            'title' => 'Completed Task',
            'status' => 'completed',
        ]);

        $response = $this->patch(route('tasks.update', $task), [
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 6: A task can be deleted successfully.
     */
    public function test_can_delete_a_task(): void
    {
        $task = Task::create([
            'title' => 'Task to be deleted',
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('tasks', 1);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Task deleted successfully!');

        $this->assertDatabaseCount('tasks', 0);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
