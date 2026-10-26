<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Tracker</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333333;
            margin-top: 0;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c2c7;
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            height: 80px;
        }
        .error-message {
            color: #d9534f;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }
        .btn-submit {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #0b5ed7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        th {
            background-color: #f8f9fa;
            color: #555;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-completed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .btn-toggle {
            border: none;
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-complete {
            background-color: #198754;
            color: white;
        }
        .btn-complete:hover {
            background-color: #157347;
        }
        .btn-reopen {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-reopen:hover {
            background-color: #ffca2c;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-delete:hover {
            background-color: #bb2d3b;
        }
        .empty-state {
            padding: 20px;
            text-align: center;
            color: #777;
            background: #fafafa;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Task Tracker</h1>

    {{-- Flash message for successful operations --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation error summary --}}
    @if($errors->any())
        <div class="alert-danger">
            <strong>Please check the form for errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to create a new task --}}
    <h2>Create New Task</h2>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Task Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Enter task title" required>
            @error('title')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" placeholder="Enter task details (optional)">{{ old('description') }}</textarea>
            @error('description')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Add Task</button>
    </form>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

    {{-- List of saved tasks --}}
    <h2>All Tasks</h2>

    @if($tasks->isEmpty())
        <div class="empty-state">
            No tasks saved yet. Use the form above to add your first task!
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 220px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td><strong>{{ $task->title }}</strong></td>
                        <td>{{ $task->description ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $task->status }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td>
                            {{-- Form to toggle task status --}}
                            <form action="{{ route('tasks.update', $task) }}" method="POST" style="display: inline-block; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $task->status === 'pending' ? 'completed' : 'pending' }}">
                                <button type="submit" class="btn-toggle {{ $task->status === 'pending' ? 'btn-complete' : 'btn-reopen' }}">
                                    {{ $task->status === 'pending' ? 'Mark Completed' : 'Mark Pending' }}
                                </button>
                            </form>

                            {{-- Form to delete task --}}
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: inline-block; margin: 0 0 0 5px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this task?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

</body>
</html>
