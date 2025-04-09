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
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
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
                            <label class="form-label" for="task_type_id">Task Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('task_type_id') is-invalid @enderror" id="task_type_id" name="task_type_id" required>
                                <option value="">Select Task Type</option>
                                @foreach($taskTypes as $taskType)
                                    <option value="{{ $taskType->id }}" data-group="{{ $taskType->task_group_id }}" {{ old('task_type_id') == $taskType->id ? 'selected' : '' }}>
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

                        <div class="form-group">
                            <label for="allocated_minutes" class="form-label">Allocated Minutes <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('allocated_minutes') is-invalid @enderror" id="allocated_minutes" name="allocated_minutes" value="{{ old('allocated_minutes') }}" min="1" required>
                            @error('allocated_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
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
    <script>
        $(document).ready(function() {
            // Function to filter task types based on selected task group
            function filterTaskTypes() {
                const selectedGroupId = $('#task_group_id').val();
                
                // Hide all options first
                $('#task_type_id option').hide();
                $('#task_type_id option[value=""]').show(); // Always show the placeholder
                
                // Show only task types that belong to the selected group
                if (selectedGroupId) {
                    $('#task_type_id option[data-group="' + selectedGroupId + '"]').show();
                } else {
                    // If no group selected, show all task types
                    $('#task_type_id option').show();
                }
                
                // Reset selection if the current selection is now hidden
                if ($('#task_type_id option:selected').css('display') === 'none') {
                    $('#task_type_id').val('');
                }
            }
            
            // Filter task types when the page loads
            filterTaskTypes();
            
            // Filter task types when the task group selection changes
            $('#task_group_id').on('change', filterTaskTypes);
        });
    </script>
@endsection 