@extends('layouts.master')

@section('title', 'Edit Form Field')

@section('content')
    <div class="main-container container-fluid">
        <div class="page-header">
            <h1 class="page-title">Edit Form Field</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('form-fields.index') }}">Form Fields</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Field</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Form Field for {{ $taskGroup->name }}</h3>
                        <div class="card-options">
                            <a href="{{ route('form-fields.index', ['task_group_id' => $taskGroup->id]) }}" class="btn btn-secondary btn-sm">
                                <i class="fe fe-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('form-fields.update', $formField->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="name" class="form-label">Field Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ $formField->name }}" disabled>
                                    <small class="text-muted">Field name cannot be changed</small>
                                </div>
                                <div class="col-lg-6">
                                    <label for="label" class="form-label">Field Label <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $formField->label) }}" required>
                                    @error('label')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="type" class="form-label">Field Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required onchange="toggleOptionsFields()">
                                        <option value="">Select Field Type</option>
                                        @foreach ($fieldTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $formField->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label for="required" class="form-label">Required?</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="required" name="required" value="1" {{ old('required', $formField->required) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="required">Mark as required field</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="display_order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $formField->display_order) }}" min="0">
                                    @error('display_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $formField->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Field is active</label>
                                </div>
                            </div>

                            <div id="options-container" class="mb-3" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Options</h4>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="addOption()">Add Option</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            Update options for dropdown, radio, or checkbox fields. Each option needs a key (stored value) and label (displayed text).
                                        </div>
                                        <div id="options-list">
                                            @if($formField->options)
                                                @foreach($formField->options as $key => $value)
                                                    <div class="row mb-2 option-row" id="option-row-existing-{{ $loop->index }}">
                                                        <div class="col-lg-5">
                                                            <input type="text" class="form-control" name="options[{{ $key }}]" value="{{ $key }}" required>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <input type="text" class="form-control" name="options[value-{{ $key }}]" value="{{ $value }}" required>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeOption('existing-{{ $loop->index }}')">
                                                                <i class="fe fe-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Validation Rules</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            Select validation rules to apply to this field.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="rule_string" name="validation_rules[]" value="string" 
                                                        {{ in_array('string', old('validation_rules', $formField->validation_rules ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rule_string">String</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="rule_email" name="validation_rules[]" value="email" 
                                                        {{ in_array('email', old('validation_rules', $formField->validation_rules ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rule_email">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="rule_url" name="validation_rules[]" value="url" 
                                                        {{ in_array('url', old('validation_rules', $formField->validation_rules ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rule_url">URL</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="rule_numeric" name="validation_rules[]" value="numeric" 
                                                        {{ in_array('numeric', old('validation_rules', $formField->validation_rules ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rule_numeric">Numeric</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Form Field</button>
                                    <a href="{{ route('form-fields.index', ['task_group_id' => $taskGroup->id]) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Show/hide options section based on field type
    function toggleOptionsFields() {
        const typeField = document.getElementById('type');
        const optionsContainer = document.getElementById('options-container');
        
        if (typeField.value === 'dropdown' || typeField.value === 'radio' || typeField.value === 'checkbox') {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleOptionsFields();
    });

    // Add option row
    let optionCount = {{ $formField->options ? count($formField->options) : 0 }};
    function addOption() {
        const optionsList = document.getElementById('options-list');
        const row = document.createElement('div');
        row.className = 'row mb-2 option-row';
        row.id = `option-row-${optionCount}`;
        
        row.innerHTML = `
            <div class="col-lg-5">
                <input type="text" class="form-control" name="options[key-${optionCount}]" placeholder="Option Key" required>
            </div>
            <div class="col-lg-5">
                <input type="text" class="form-control" name="options[value-${optionCount}]" placeholder="Option Label" required>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(${optionCount})">
                    <i class="fe fe-trash"></i>
                </button>
            </div>
        `;
        
        optionsList.appendChild(row);
        optionCount++;
    }

    // Remove option row
    function removeOption(id) {
        const row = document.getElementById(`option-row-${id}`);
        row.remove();
    }
</script>
@endsection
