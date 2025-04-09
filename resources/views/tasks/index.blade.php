@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tasks</h5>
                        <div>
                            <a href="{{ route('tasks.kanban') }}" class="btn btn-outline-primary btn-sm me-2">Kanban Board</a>
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">Create New Task</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Task Group</th>
                                    <th>Type</th>
                                    <th>Complexity</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        @foreach($task->taskGroups as $group)
                                        <span class="badge bg-secondary me-1">{{ $group->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $task->taskTypeComplexity->taskType->name ?? 'N/A' }}</td>
                                    <td>{{ $task->taskTypeComplexity->complexityLevel->name ?? 'N/A' }}</td>
                                    <td>
                                        @switch($task->status)
                                            @case('to-do')
                                                <span class="badge bg-secondary">To Do</span>
                                                @break
                                            @case('in-progress')
                                                <span class="badge bg-primary">In Progress</span>
                                                @break
                                            @case('waiting')
                                                <span class="badge bg-info">Waiting</span>
                                                @break
                                            @case('paused')
                                                <span class="badge bg-warning">Paused</span>
                                                @break
                                            @case('done')
                                                <span class="badge bg-success">Done</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $task->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($task->priority)
                                            @case('low')
                                                <span class="badge bg-success">Low</span>
                                                @break
                                            @case('medium')
                                                <span class="badge bg-warning text-dark">Medium</span>
                                                @break
                                            @case('high')
                                                <span class="badge bg-danger">High</span>
                                                @break
                                            @case('urgent')
                                                <span class="badge bg-danger" style="background-color: darkred !important;">Urgent</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $task->priority }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $task->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $task->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 