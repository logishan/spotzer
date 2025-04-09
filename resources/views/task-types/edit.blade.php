@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Task Type</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-types.index') }}">Task Types</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Task Type: {{ $taskType->name }}</h3>
                    <div class="card-options">
                        <a href="{{ route('task-types.index') }}" class="btn btn-primary btn-sm">
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

                    <form action="{{ route('task-types.update', $taskType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label class="form-label" for="task_group_id">Task Group <span class="text-danger">*</span></label>
                            <select class="form-select @error('task_group_id') is-invalid @enderror" id="task_group_id" name="task_group_id" required>
                                <option value="">Select Task Group</option>
                                @foreach($taskGroups as $taskGroup)
                                    <option value="{{ $taskGroup->id }}" {{ (old('task_group_id', $taskType->task_group_id) == $taskGroup->id) ? 'selected' : '' }}>
                                        {{ $taskGroup->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $taskType->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" id="is_active" name="is_active" value="1" {{ old('is_active', $taskType->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Update Task Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Complexity Levels</h3>
                    <div class="card-options">
                        <a href="{{ route('task-type-complexities.create', ['task_type_id' => $taskType->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add Complexity Level
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($taskType->complexities->isEmpty())
                        <div class="alert alert-info">
                            No complexity levels defined for this task type yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Complexity Level</th>
                                        <th>Allocated Minutes</th>
                                        <th>Effective From</th>
                                        <th>Effective To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taskType->complexities as $complexity)
                                        <tr>
                                            <td>{{ $complexity->complexityLevel->name }}</td>
                                            <td>{{ $complexity->allocated_minutes }}</td>
                                            <td>{{ $complexity->effective_from ? $complexity->effective_from->format('Y-m-d') : 'N/A' }}</td>
                                            <td>{{ $complexity->effective_to ? $complexity->effective_to->format('Y-m-d') : 'Ongoing' }}</td>
                                            <td>
                                                <a href="{{ route('task-type-complexities.edit', $complexity) }}" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 