@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Create User</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create User</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New User</h3>
                    <div class="card-options">
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-control @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="shift_schedule_id" class="form-label">Shift Schedule</label>
                            <select class="form-control @error('shift_schedule_id') is-invalid @enderror" id="shift_schedule_id" name="shift_schedule_id">
                                <option value="">Select Shift Schedule</option>
                                @foreach($shiftSchedules as $shiftSchedule)
                                    <option value="{{ $shiftSchedule->id }}" {{ old('shift_schedule_id') == $shiftSchedule->id ? 'selected' : '' }}>
                                        {{ $shiftSchedule->name }} ({{ $shiftSchedule->start_time }} - {{ $shiftSchedule->end_time }})
                                    </option>
                                @endforeach
                            </select>
                            @error('shift_schedule_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="role_ids" class="form-label">Roles <span class="text-danger">*</span></label>
                            <select class="form-control @error('role_ids') is-invalid @enderror" id="role_ids" name="role_ids[]" multiple required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (old('role_ids') && in_array($role->id, old('role_ids'))) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="task_group_ids" class="form-label">Task Groups</label>
                            <select class="form-control @error('task_group_ids') is-invalid @enderror" id="task_group_ids" name="task_group_ids[]" multiple>
                                @foreach($taskGroups as $taskGroup)
                                    <option value="{{ $taskGroup->id }}" {{ (old('task_group_ids') && in_array($taskGroup->id, old('task_group_ids'))) ? 'selected' : '' }}>
                                        {{ $taskGroup->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_group_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select2 for multiple selects
            $('#role_ids, #task_group_ids').select2({
                placeholder: 'Select options',
                width: '100%'
            });
        });
    </script>
@endsection 