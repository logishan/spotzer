@extends('layouts.master')

@section('styles')
<style>
    .kanban-board {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        min-height: calc(100vh - 250px);
        padding-bottom: 1rem;
    }
    
    .kanban-column {
        min-width: 300px;
        width: 300px;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
    }
    
    .kanban-column-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .kanban-column-body {
        flex-grow: 1;
        padding: 1rem;
        overflow-y: auto;
        height: 100%;
    }
    
    .task-count {
        background-color: #e9ecef;
        color: #495057;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .kanban-card {
        background-color: white;
        border-radius: 0.375rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: grab;
        transition: all 0.2s;
    }
    
    .kanban-card:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .kanban-card-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .kanban-card-content {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .kanban-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f0f0f0;
        font-size: 0.75rem;
    }
    
    .priority-badge {
        font-size: 0.625rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .priority-low {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .priority-medium {
        background-color: #fff3cd;
        color: #664d03;
    }
    
    .priority-high {
        background-color: #f8d7da;
        color: #842029;
    }
    
    .priority-urgent {
        background-color: #dc3545;
        color: white;
    }
    
    .task-type {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .drag-over {
        background-color: #e2e3e5;
        transition: background-color 0.2s;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Task Board</h1>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $taskGroup ? $taskGroup->name : 'All Task Groups' }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="{{ route('tasks.kanban') }}">All Task Groups</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($taskGroups as $group)
                    <li><a class="dropdown-item" href="{{ route('tasks.kanban', ['taskGroup' => $group->id]) }}">{{ $group->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">New Task</a>
        </div>
    </div>

    <div class="kanban-board">
        <!-- To Do Column -->
        <div class="kanban-column" id="todo" data-status="todo">
            <div class="kanban-column-header">
                <span>To Do</span>
                <span class="task-count">{{ $tasks['todo']->count() }}</span>
            </div>
            <div class="kanban-column-body" ondragover="allowDrop(event)" ondrop="drop(event, 'todo')">
                @foreach($tasks['todo'] as $task)
                <div class="kanban-card" draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="task-type">{{ $task->taskTypeComplexity->taskType->name ?? 'Unknown Type' }} - {{ $task->taskTypeComplexity->complexityLevel->name ?? 'Unknown Complexity' }}</div>
                    <div class="kanban-card-title">{{ $task->title }}</div>
                    <div class="kanban-card-content">{{ Str::limit($task->description, 100) }}</div>
                    <div class="kanban-card-footer">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                        <span>{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="kanban-column" id="in_progress" data-status="in_progress">
            <div class="kanban-column-header">
                <span>In Progress</span>
                <span class="task-count">{{ $tasks['in_progress']->count() }}</span>
            </div>
            <div class="kanban-column-body" ondragover="allowDrop(event)" ondrop="drop(event, 'in_progress')">
                @foreach($tasks['in_progress'] as $task)
                <div class="kanban-card" draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="task-type">{{ $task->taskTypeComplexity->taskType->name ?? 'Unknown Type' }} - {{ $task->taskTypeComplexity->complexityLevel->name ?? 'Unknown Complexity' }}</div>
                    <div class="kanban-card-title">{{ $task->title }}</div>
                    <div class="kanban-card-content">{{ Str::limit($task->description, 100) }}</div>
                    <div class="kanban-card-footer">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                        <span>{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Waiting Column -->
        <div class="kanban-column" id="waiting" data-status="waiting">
            <div class="kanban-column-header">
                <span>Waiting</span>
                <span class="task-count">{{ $tasks['waiting']->count() }}</span>
            </div>
            <div class="kanban-column-body" ondragover="allowDrop(event)" ondrop="drop(event, 'waiting')">
                @foreach($tasks['waiting'] as $task)
                <div class="kanban-card" draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="task-type">{{ $task->taskTypeComplexity->taskType->name ?? 'Unknown Type' }} - {{ $task->taskTypeComplexity->complexityLevel->name ?? 'Unknown Complexity' }}</div>
                    <div class="kanban-card-title">{{ $task->title }}</div>
                    <div class="kanban-card-content">{{ Str::limit($task->description, 100) }}</div>
                    <div class="kanban-card-footer">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                        <span>{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Paused Column -->
        <div class="kanban-column" id="paused" data-status="paused">
            <div class="kanban-column-header">
                <span>Paused</span>
                <span class="task-count">{{ $tasks['paused']->count() }}</span>
            </div>
            <div class="kanban-column-body" ondragover="allowDrop(event)" ondrop="drop(event, 'paused')">
                @foreach($tasks['paused'] as $task)
                <div class="kanban-card" draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="task-type">{{ $task->taskTypeComplexity->taskType->name ?? 'Unknown Type' }} - {{ $task->taskTypeComplexity->complexityLevel->name ?? 'Unknown Complexity' }}</div>
                    <div class="kanban-card-title">{{ $task->title }}</div>
                    <div class="kanban-card-content">{{ Str::limit($task->description, 100) }}</div>
                    <div class="kanban-card-footer">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                        <span>{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Column -->
        <div class="kanban-column" id="completed" data-status="completed">
            <div class="kanban-column-header">
                <span>Completed</span>
                <span class="task-count">{{ $tasks['completed']->count() }}</span>
            </div>
            <div class="kanban-column-body" ondragover="allowDrop(event)" ondrop="drop(event, 'completed')">
                @foreach($tasks['completed'] as $task)
                <div class="kanban-card" draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" data-task-id="{{ $task->id }}">
                    <div class="task-type">{{ $task->taskTypeComplexity->taskType->name ?? 'Unknown Type' }} - {{ $task->taskTypeComplexity->complexityLevel->name ?? 'Unknown Complexity' }}</div>
                    <div class="kanban-card-title">{{ $task->title }}</div>
                    <div class="kanban-card-content">{{ Str::limit($task->description, 100) }}</div>
                    <div class="kanban-card-footer">
                        <span class="priority-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                        <span>{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Drag and drop functionality
    function drag(event) {
        event.dataTransfer.setData("text", event.target.id);
    }

    function allowDrop(event) {
        event.preventDefault();
        event.currentTarget.classList.add('drag-over');
    }

    function drop(event, status) {
        event.preventDefault();
        const taskId = event.dataTransfer.getData("text");
        const taskElement = document.getElementById(taskId);
        const taskDataId = taskElement.dataset.taskId;
        
        // Check if dropping in in-progress column and if there's already a task there
        if (status === 'in_progress') {
            const inProgressColumn = document.getElementById('in_progress');
            const inProgressTasks = inProgressColumn.querySelectorAll('.kanban-card');
            
            // If there are tasks and the dropped task is not from the in-progress column
            if (inProgressTasks.length > 0 && taskElement.parentElement.parentElement.id !== 'in_progress') {
                // Move existing in-progress task to paused
                const existingTask = inProgressTasks[0];
                const existingTaskId = existingTask.dataset.taskId;
                
                // Update existing task status to paused in the database
                fetch(`/tasks/${existingTaskId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: 'paused'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Move the existing task card to the paused column in the UI
                        document.querySelector('#paused .kanban-column-body').appendChild(existingTask);
                        
                        // Update task counts
                        updateTaskCounts();
                    } else {
                        console.error('Failed to update existing task status');
                    }
                });
            }
        }
        
        // Update the dropped task's status in the database
        fetch(`/tasks/${taskDataId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Move the task card to the new column in the UI
                event.currentTarget.appendChild(taskElement);
                
                // Update task counts
                updateTaskCounts();
            } else {
                console.error('Failed to update task status');
            }
        });
        
        // Remove drag-over class
        const columns = document.querySelectorAll('.kanban-column-body');
        columns.forEach(col => col.classList.remove('drag-over'));
    }

    function updateTaskCounts() {
        const columns = document.querySelectorAll('.kanban-column');
        columns.forEach(column => {
            const status = column.dataset.status;
            const count = column.querySelectorAll('.kanban-card').length;
            const countElement = column.querySelector('.task-count');
            countElement.textContent = count;
        });
    }

    // Add event listener to remove drag-over class when dragging leaves an element
    document.addEventListener('DOMContentLoaded', function() {
        const dropZones = document.querySelectorAll('.kanban-column-body');
        dropZones.forEach(zone => {
            zone.addEventListener('dragleave', function(event) {
                if (event.currentTarget === event.target) {
                    event.currentTarget.classList.remove('drag-over');
                }
            });
        });
    });
</script>
@endpush
@endsection 