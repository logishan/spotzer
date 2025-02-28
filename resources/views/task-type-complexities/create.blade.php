@extends('layouts.master')

@section('styles')
    <!-- Flatpickr CSS -->
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Create Task Type Complexity</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-type-complexities.index') }}">Task Type Complexities</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Task Type Complexity</h3>
                    <div class="card-options">
                        <a href="{{ route('task-type-complexities.index') }}" class="btn btn-primary btn-sm">
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

                    <form action="{{ route('task-type-complexities.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label" for="task_type_id">Task Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('task_type_id') is-invalid @enderror" id="task_type_id" name="task_type_id" required>
                                <option value="">Select Task Type</option>
                                @foreach($taskTypes as $taskType)
                                    <option value="{{ $taskType->id }}" {{ old('task_type_id') == $taskType->id ? 'selected' : '' }}>
                                        {{ $taskType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('task_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="complexity_level_id">Complexity Level <span class="text-danger">*</span></label>
                            <select class="form-control @error('complexity_level_id') is-invalid @enderror" id="complexity_level_id" name="complexity_level_id" required>
                                <option value="">Select Complexity Level</option>
                                @foreach($complexityLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('complexity_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('complexity_level_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="allocated_minutes">Allocated Minutes <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('allocated_minutes') is-invalid @enderror" id="allocated_minutes" name="allocated_minutes" value="{{ old('allocated_minutes') }}" required min="1">
                            @error('allocated_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="effective_from">Effective From <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr @error('effective_from') is-invalid @enderror" id="effective_from" name="effective_from" value="{{ old('effective_from') }}" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="effective_to">Effective To</label>
                            <input type="text" class="form-control flatpickr @error('effective_to') is-invalid @enderror" id="effective_to" name="effective_to" value="{{ old('effective_to') }}">
                            <small class="form-text text-muted">Leave blank if this is an ongoing configuration.</small>
                            @error('effective_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Create Task Type Complexity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Flatpickr JS -->
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.js') }}"></script>
    <script>
        $(function() {
            // Initialize flatpickr
            $(".flatpickr").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });
    </script>
@endsection 