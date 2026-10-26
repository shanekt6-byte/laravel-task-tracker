<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Display the task tracker homepage (list of tasks)
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Store a newly created task
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Update a task's status (Route Model Binding: {task})
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// Delete a task (Route Model Binding: {task})
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');


