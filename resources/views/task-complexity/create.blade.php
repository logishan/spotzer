@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Create Complexity Level</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-complexity.index') }}">Task Complexity</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Complexity Level</h3>
                    <div class="card-options">
                        <a href="{{ route('task-complexity.index') }}" class="btn btn-primary btn-sm">
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

                    <form action="{{ route('task-complexity.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label" for="task_group_id">Task Group <span class="text-danger">*</span></label>
                            <select class="form-control @error('task_group_id') is-invalid @enderror" id="task_group_id" name="task_group_id" required>
                                <option value="">Select Task Group</option>
                                @foreach($taskGroups as $taskGroup)
                                    <option value="{{ $taskGroup->id }}" {{ old('task_group_id') == $taskGroup->id ? 'selected' : '' }}>
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Create Complexity Level</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 