<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks.
     */
    public function index()
    {
        // Fetch tasks ordered by newest first using Eloquent
        $tasks = Task::latest()->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created task in the database.
     */
    public function store(Request $request)
    {
        // Form validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Insert new task into database using Eloquent
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Update the status of an existing task.
     */
    public function update(Request $request, Task $task)
    {
        // Validate that status is required and must only be pending or completed
        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        // Persist the status change in the database
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task status updated to "' . $task->status . '" successfully!');
    }

    /**
     * Remove the specified task from the database.
     */
    public function destroy(Task $task)
    {
        // Eloquent: Delete the task record
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}

