@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Task Group Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-groups.index') }}">Task Groups</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $taskGroup->name }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $taskGroup->name }}</h3>
                    <div class="card-options">
                        <a href="{{ route('task-groups.edit', $taskGroup) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fe fe-edit-2"></i> Edit Task Group
                        </a>
                        <a href="{{ route('task-groups.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div>
                                    <span class="badge bg-{{ $taskGroup->is_active ? 'success' : 'danger' }}">
                                        {{ $taskGroup->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Created By</label>
                                <div>{{ $taskGroup->creator->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Last Updated By</label>
                                <div>{{ $taskGroup->updater->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Created At</label>
                                <div>{{ $taskGroup->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($taskGroup->description)
                        <div class="form-group mb-4">
                            <label class="form-label">Description</label>
                            <div>{{ $taskGroup->description }}</div>
                        </div>
                    @endif

                    <div class="form-group mb-4">
                        <label class="form-label">Departments</label>
                        <div>
                            @if($taskGroup->departments->isNotEmpty())
                                @foreach($taskGroup->departments as $department)
                                    <span class="badge bg-primary me-1">{{ $department->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No departments assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Users</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Permissions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taskGroup->users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->pivot->can_edit ? 'success' : 'info' }}">
                                                    {{ $user->pivot->can_edit ? 'Can Edit' : 'View Only' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm {{ $user->pivot->can_edit ? 'btn-warning' : 'btn-success' }}"
                                                    onclick="toggleUserPermission({{ $taskGroup->id }}, {{ $user->id }}, {{ $user->pivot->can_edit ? 'false' : 'true' }})">
                                                    <i class="fe fe-{{ $user->pivot->can_edit ? 'eye' : 'edit-2' }}"></i>
                                                    {{ $user->pivot->can_edit ? 'Set to View Only' : 'Grant Edit Access' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tasks</label>
                        @if($taskGroup->tasks->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($taskGroup->tasks as $task)
                                            <tr>
                                                <td>{{ $task->title }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted">No tasks in this group</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleUserPermission(taskGroupId, userId, canEdit) {
            fetch(`/task-groups/${taskGroupId}/users/${userId}/permissions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ can_edit: canEdit })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update user permissions');
            });
        }
    </script>
@endsection 