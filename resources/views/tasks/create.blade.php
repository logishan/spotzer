@extends('layouts.master')

@section('title', 'Create New Task')

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">Create New Task</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Task</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Task Group Selection Form -->
                    <div id="taskGroupSelection" class="{{ $selectedTaskGroup ? 'd-none' : '' }}">
                        <form action="{{ route('tasks.create') }}" method="GET" id="taskGroupForm">
                            <div class="form-group mb-3">
                                <label for="task_group_id" class="form-label">Select Task Group <span class="text-danger">*</span></label>
                                <select class="form-select" id="task_group_id" name="task_group_id" required>
                                    <option value="">-- Select Task Group --</option>
                                    @foreach($taskGroups as $taskGroup)
                                        <option value="{{ $taskGroup->id }}" {{ $selectedTaskGroup && $selectedTaskGroup->id == $taskGroup->id ? 'selected' : '' }}>
                                            {{ $taskGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </div>
                        </form>
                    </div>

                    <!-- Task Creation Form -->
                    @if($selectedTaskGroup)
                        <div id="taskCreationForm">
                            <div class="mb-4">
                                <h4>Creating task for: {{ $selectedTaskGroup->name }}</h4>
                                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-outline-primary">Change Task Group</a>
                            </div>

                            <form action="{{ route('tasks.store') }}" method="POST" onsubmit="
                                const taskTypeComplexityId = document.getElementById('task_type_complexity_id')?.value;
                                
                                if (!taskTypeComplexityId) {
                                    alert('Missing task_type_complexity_id value. Please select a task type and complexity first.');
                                    return false;
                                }
                                
                                return true;
                            ">
                                @csrf
                                <input type="hidden" name="task_group_id" value="{{ $selectedTaskGroup->id }}">

                                <!-- Common Fields -->
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
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

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                            <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="waiting" {{ old('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                            <option value="paused" {{ old('status') == 'paused' ? 'selected' : '' }}>Paused</option>
                                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Task Type and Complexity Section -->
                                <div id="taskTypeComplexitySection">
                                    @php
                                        $hasTaskType = false;
                                        $hasComplexity = false;
                                        $hasCombined = false;
                                        
                                        foreach($formFields as $field) {
                                            if($field['name'] === 'task_type') {
                                                $hasTaskType = true;
                                            }
                                            if($field['name'] === 'complexity') {
                                                $hasComplexity = true;
                                            }
                                            if($field['name'] === 'task_type_complexity_id' && $field['type'] !== 'hidden') {
                                                $hasCombined = true;
                                            }
                                        }
                                    @endphp

                                    <!-- First display Task Type options -->
                                    <div class="form-group mb-3">
                                        <label for="task_type" class="form-label">Task Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('task_type') is-invalid @enderror" 
                                               id="task_type" 
                                               name="task_type" 
                                               required 
                                               onchange="
                                                const taskTypeId = this.value;
                                                const taskGroupId = document.querySelector('input[name=task_group_id]')?.value;
                                                
                                                if (taskTypeId && taskGroupId) {
                                                    // Visual feedback
                                                    const complexitySelect = document.getElementById('complexity');
                                                    const loadingIndicator = document.getElementById('complexity-loading');
                                                    
                                                    if (complexitySelect) {
                                                        complexitySelect.innerHTML = '<option value=&quot;&quot;>Loading...</option>';
                                                        complexitySelect.disabled = true;
                                                    }
                                                    
                                                    if (loadingIndicator) {
                                                        loadingIndicator.classList.remove('d-none');
                                                    }
                                                    
                                                    // Make request
                                                    fetch('/api/task-type-complexities?task_type_id=' + taskTypeId + '&task_group_id=' + taskGroupId + '&_=' + Date.now(), {
                                                        method: 'GET',
                                                        headers: {
                                                            'Accept': 'application/json',
                                                            'X-Requested-With': 'XMLHttpRequest'
                                                        }
                                                    })
                                                    .then(r => r.text())
                                                    .then(text => {
                                                        try {
                                                            const data = JSON.parse(text);
                                                            
                                                            // Hide loading
                                                            if (loadingIndicator) loadingIndicator.classList.add('d-none');
                                                            
                                                            // Process data
                                                            if (complexitySelect) {
                                                                complexitySelect.innerHTML = '<option value=&quot;&quot;>-- Select Complexity --</option>';
                                                                
                                                                if (data.success && data.complexities && data.complexities.length) {
                                                                    data.complexities.forEach(c => {
                                                                        const opt = document.createElement('option');
                                                                        opt.value = c.complexity_level_id;
                                                                        opt.textContent = c.complexity_name + ' (' + c.allocated_minutes + ' min)';
                                                                        opt.dataset.ttcId = c.id;
                                                                        complexitySelect.appendChild(opt);
                                                                    });
                                                                    complexitySelect.disabled = false;
                                                                } else {
                                                                    complexitySelect.innerHTML = '<option value=&quot;&quot;>No options found</option>';
                                                                }
                                                            }
                                                        } catch (err) {
                                                            alert('Error parsing response: ' + err.message);
                                                        }
                                                    })
                                                    .catch(err => {
                                                        if (loadingIndicator) loadingIndicator.classList.add('d-none');
                                                        if (complexitySelect) {
                                                            complexitySelect.innerHTML = '<option value=&quot;&quot;>Error loading options</option>';
                                                            complexitySelect.disabled = true;
                                                        }
                                                        alert('Error loading complexity options: ' + err.message);
                                                    });
                                                } else {
                                                    alert('Missing required information. Task Type: ' + taskTypeId + ', Task Group: ' + taskGroupId);
                                                }
                                               "
                                               >
                                            <option value="">-- Select Task Type --</option>
                                            @foreach($formFields as $field)
                                                @if($field['name'] === 'task_type' && !empty($field['options']))
                                                    @foreach($field['options'] as $value => $label)
                                                        <option value="{{ $value }}" {{ old('task_type') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('task_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Then display Complexity options (will be populated based on Task Type) -->
                                    <div class="form-group mb-3">
                                        <label for="complexity" class="form-label">Task Complexity <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <select class="form-select @error('complexity') is-invalid @enderror" 
                                                   id="complexity" 
                                                   name="complexity" 
                                                   required 
                                                   disabled
                                                   onchange="
                                                    const selectedOption = this.options[this.selectedIndex];
                                                    
                                                    // Update the hidden task_type_complexity_id field
                                                    const ttcIdField = document.getElementById('task_type_complexity_id');
                                                    if (ttcIdField && selectedOption && selectedOption.dataset.ttcId) {
                                                        ttcIdField.value = selectedOption.dataset.ttcId;
                                                    }
                                                   ">
                                                <option value="">-- First Select a Task Type --</option>
                                            </select>
                                            <div id="complexity-loading" class="position-absolute end-0 top-0 d-none" style="margin: 10px 15px 0 0;">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        @error('complexity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted complexity-helper-text">Complexity options will load after selecting a task type</small>
                                    </div>
                                    
                                    <input type="hidden" name="task_type_complexity_id" id="task_type_complexity_id">
                                </div>

                                <!-- Dynamic Form Fields -->
                                <div id="dynamicFormFields">
                                    @foreach($formFields as $field)
                                        @if(!in_array($field['name'], ['task_type', 'complexity', 'task_type_complexity_id']))
                                            <div class="form-group mb-3">
                                                <label for="{{ $field['name'] }}" class="form-label">
                                                    {{ $field['label'] }}
                                                    @if($field['required'])
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>

                                                @if($field['type'] === 'text')
                                                    <input type="text" class="form-control @error($field['name']) is-invalid @enderror" 
                                                        id="{{ $field['name'] }}" name="{{ $field['name'] }}" 
                                                        value="{{ old($field['name']) }}" 
                                                        {{ $field['required'] ? 'required' : '' }}>

                                                @elseif($field['type'] === 'textarea')
                                                    <textarea class="form-control @error($field['name']) is-invalid @enderror" 
                                                        id="{{ $field['name'] }}" name="{{ $field['name'] }}" 
                                                        rows="3" {{ $field['required'] ? 'required' : '' }}>{{ old($field['name']) }}</textarea>

                                                @elseif($field['type'] === 'dropdown')
                                                    <select class="form-select @error($field['name']) is-invalid @enderror" 
                                                        id="{{ $field['name'] }}" name="{{ $field['name'] }}" 
                                                        {{ $field['required'] ? 'required' : '' }}>
                                                        <option value="">-- Select {{ $field['label'] }} --</option>
                                                        @if(!empty($field['options']))
                                                            @foreach($field['options'] as $value => $label)
                                                                <option value="{{ $value }}" {{ old($field['name']) == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>

                                                @elseif($field['type'] === 'radio')
                                                    @if(!empty($field['options']))
                                                        <div>
                                                            @foreach($field['options'] as $value => $label)
                                                                <div class="form-check">
                                                                    <input class="form-check-input @error($field['name']) is-invalid @enderror" 
                                                                        type="radio" name="{{ $field['name'] }}" 
                                                                        id="{{ $field['name'] }}_{{ $value }}" 
                                                                        value="{{ $value }}" 
                                                                        {{ old($field['name']) == $value ? 'checked' : '' }} 
                                                                        {{ $field['required'] ? 'required' : '' }}>
                                                                    <label class="form-check-label" for="{{ $field['name'] }}_{{ $value }}">
                                                                        {{ $label }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                @elseif($field['type'] === 'checkbox')
                                                    <div class="form-check">
                                                        <input class="form-check-input @error($field['name']) is-invalid @enderror" 
                                                            type="checkbox" id="{{ $field['name'] }}" 
                                                            name="{{ $field['name'] }}" value="1" 
                                                            {{ old($field['name']) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $field['name'] }}">
                                                            Yes
                                                        </label>
                                                    </div>

                                                @elseif($field['type'] === 'date')
                                                    <input type="date" class="form-control @error($field['name']) is-invalid @enderror" 
                                                        id="{{ $field['name'] }}" name="{{ $field['name'] }}" 
                                                        value="{{ old($field['name']) }}" 
                                                        {{ $field['required'] ? 'required' : '' }}>
                                                @endif

                                                @error($field['name'])
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary">Create Task</button>
                                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const taskTypeSelect = document.getElementById('task_type');
        const complexitySelect = document.getElementById('complexity');
        const taskTypeComplexityIdInput = document.getElementById('task_type_complexity_id');
        const complexityLoadingIndicator = document.getElementById('complexity-loading');
        const taskGroupInput = document.querySelector('input[name="task_group_id"]');
        
        if (!taskTypeSelect || !complexitySelect || !taskTypeComplexityIdInput || !taskGroupInput) {
            return;
        }
        
        // Add change handler for task type
        taskTypeSelect.addEventListener('change', function() {
            const taskTypeId = this.value;
            const taskGroupId = taskGroupInput.value;
            
            // Clear and disable complexity select
            complexitySelect.innerHTML = '<option value="">Loading complexities...</option>';
            complexitySelect.disabled = true;
            
            // Show loading indicator
            if (complexityLoadingIndicator) {
                complexityLoadingIndicator.classList.remove('d-none');
            }
            
            // Clear any existing messages
            document.querySelectorAll('.complexity-helper-message').forEach(el => el.remove());
            
            if (!taskTypeId) {
                complexitySelect.innerHTML = '<option value="">-- First Select a Task Type --</option>';
                return;
            }
            
            loadComplexities(taskTypeId, taskGroupId);
        });
        
        // Add change handler for complexity
        complexitySelect.addEventListener('change', function() {
            updateTaskTypeComplexityId();
        });
        
        function loadComplexities(taskTypeId, taskGroupId) {
            const timestamp = new Date().getTime();
            
            // Make sure we have valid IDs before making the request
            if (!taskTypeId || !taskGroupId) {
                showErrorMessage(
                    'Error loading complexity options',
                    '<p>Missing required task information. Please ensure both task type and task group are selected.</p>'
                );
                return;
            }
            
            const apiUrl = `/api/task-type-complexities?task_type_id=${taskTypeId}&task_group_id=${taskGroupId}&_=${timestamp}`;
            
            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
                }
                return response.text().then(text => {
                    try {
                        const jsonData = JSON.parse(text);
                        return jsonData;
                    } catch (e) {
                        throw new Error('Invalid JSON response from server: ' + e.message);
                    }
                });
            })
            .then(data => {
                // Hide loading indicator
                if (complexityLoadingIndicator) {
                    complexityLoadingIndicator.classList.add('d-none');
                }
                
                // Clear existing options
                complexitySelect.innerHTML = '<option value="">-- Select Task Complexity --</option>';
                
                if (data.success && data.complexities && data.complexities.length > 0) {
                    // Add new options
                    data.complexities.forEach(complexity => {
                        const option = document.createElement('option');
                        option.value = complexity.complexity_level_id;
                        
                        let label = complexity.complexity_name + ' (' + complexity.allocated_minutes + ' min)';
                        if (complexity.is_default) {
                            label += ' [Default]';
                        }
                        
                        option.textContent = label;
                        option.dataset.ttcId = complexity.id;
                        complexitySelect.appendChild(option);
                    });
                    
                    // Enable the select
                    complexitySelect.disabled = false;
                    
                    // Try to restore old value if it exists
                    const oldValue = "{{ old('complexity') }}";
                    if (oldValue) {
                        [...complexitySelect.options].some(option => {
                            if (option.value === oldValue) {
                                option.selected = true;
                                updateTaskTypeComplexityId();
                                return true;
                            }
                            return false;
                        });
                    }
                } else {
                    complexitySelect.innerHTML = '<option value="">No complexity options found</option>';
                    complexitySelect.disabled = true;
                    
                    showWarningMessage(
                        'No complexity options found',
                        `<p>No complexity options are configured for this task type in this task group.</p>
                         <p>Please contact an administrator to set up the complexity options.</p>`
                    );
                }
            })
            .catch(error => {
                // Hide loading indicator
                if (complexityLoadingIndicator) {
                    complexityLoadingIndicator.classList.add('d-none');
                }
                
                complexitySelect.innerHTML = '<option value="">Error loading complexities</option>';
                complexitySelect.disabled = true;
                
                showErrorMessage(
                    'Error loading complexity options',
                    `<p>${error.message}</p>`
                );
            });
        }
        
        function updateTaskTypeComplexityId() {
            const selectedOption = complexitySelect.options[complexitySelect.selectedIndex];
            
            if (selectedOption && selectedOption.dataset.ttcId) {
                taskTypeComplexityIdInput.value = selectedOption.dataset.ttcId;
            } else {
                taskTypeComplexityIdInput.value = '';
            }
        }
        
        function showWarningMessage(title, message) {
            const helpText = document.createElement('div');
            helpText.className = 'alert alert-warning mt-2 complexity-helper-message';
            helpText.innerHTML = `
                <strong>${title}</strong> 
                ${message}
            `;
            
            complexitySelect.parentNode.insertBefore(helpText, complexitySelect.nextSibling);
        }
        
        function showErrorMessage(title, message) {
            const errorText = document.createElement('div');
            errorText.className = 'alert alert-danger mt-2 complexity-helper-message';
            errorText.innerHTML = `
                <strong>${title}</strong> 
                ${message}
            `;
            
            complexitySelect.parentNode.insertBefore(errorText, complexitySelect.nextSibling);
        }
        
        // If there's an initial task type value, trigger the change event
        if (taskTypeSelect.value) {
            taskTypeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>

<!-- Fix PerfectScrollbar error -->
<script>
    // Create a dummy element for PerfectScrollbar to prevent errors
    if (typeof PerfectScrollbar !== 'undefined') {
        try {
            // Create a dummy element if the original target doesn't exist
            const dummyElement = document.createElement('div');
            dummyElement.id = 'dummy-scrollbar-target';
            dummyElement.style.display = 'none';
            document.body.appendChild(dummyElement);
            
            // Monkey patch PerfectScrollbar constructor to avoid errors
            const originalPerfectScrollbar = PerfectScrollbar;
            window.PerfectScrollbar = function(element, options) {
                if (!element) {
                    return new originalPerfectScrollbar(dummyElement, options);
                }
                return new originalPerfectScrollbar(element, options);
            };
        } catch (e) {
            console.error('Failed to patch PerfectScrollbar:', e);
        }
    }
</script>
@endpush
