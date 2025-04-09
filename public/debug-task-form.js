// Task Form Debug Script
(function() {
    console.log('==== TASK FORM DEBUG SCRIPT LOADED ====');

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded - initializing task form debug');
        
        // Find the task type select element
        const taskTypeSelect = document.getElementById('task_type');
        const complexitySelect = document.getElementById('complexity');
        const taskTypeComplexityIdInput = document.getElementById('task_type_complexity_id');
        const taskGroupInput = document.querySelector('input[name="task_group_id"]');
        
        console.log('Task Type Select:', taskTypeSelect ? 'Found' : 'Not found');
        console.log('Complexity Select:', complexitySelect ? 'Found' : 'Not found');
        console.log('Task Type Complexity ID Input:', taskTypeComplexityIdInput ? 'Found' : 'Not found');
        console.log('Task Group Input:', taskGroupInput ? 'Found' : 'Not found');
        
        if (!taskTypeSelect) {
            console.error('Task Type Select element not found!');
            addWarningBanner('Debug Error', 'Task Type select element not found. Debug script cannot continue.');
            return;
        }

        if (!taskGroupInput) {
            console.error('Task Group Input not found!');
            addWarningBanner('Debug Error', 'Task Group input not found. This is required for loading complexities.');
            return;
        }
        
        // Log current value
        console.log('Task Type initial value:', taskTypeSelect.value);
        console.log('Task Group initial value:', taskGroupInput.value);
        
        // Log options
        for (let i = 0; i < taskTypeSelect.options.length; i++) {
            console.log(`Option ${i}: value = "${taskTypeSelect.options[i].value}", text = "${taskTypeSelect.options[i].text}"`);
        }
        
        // Add change event handler that logs the change and forces complexity update
        taskTypeSelect.addEventListener('change', function(event) {
            console.log('==== TASK TYPE CHANGED ====');
            console.log('New value:', this.value);
            console.log('Selected text:', this.options[this.selectedIndex].text);
            
            const taskGroupId = taskGroupInput.value;
            const taskTypeId = this.value;
            
            console.log('Loading complexities with:', {
                taskTypeId: taskTypeId,
                taskGroupId: taskGroupId
            });
            
            if (taskTypeId) {
                loadComplexitiesDirectly(taskTypeId, taskGroupId);
            } else {
                console.log('No task type selected, clearing complexity options');
                if (complexitySelect) {
                    complexitySelect.innerHTML = '<option value="">-- First Select a Task Type --</option>';
                    complexitySelect.disabled = true;
                }
            }
        });
        
        // If there's a value already selected, trigger a change to load complexities
        if (taskTypeSelect.value) {
            console.log('Initial value found, triggering change event');
            // Create and dispatch a change event
            const changeEvent = new Event('change');
            taskTypeSelect.dispatchEvent(changeEvent);
        }
        
        function loadComplexitiesDirectly(taskTypeId, taskGroupId) {
            console.log(`Loading complexities for task type ${taskTypeId} in task group ${taskGroupId}`);
            
            // Show loading indicator if it exists
            const complexityLoadingIndicator = document.getElementById('complexity-loading');
            if (complexityLoadingIndicator) {
                complexityLoadingIndicator.classList.remove('d-none');
            }
            
            // Update complexity select to show loading
            if (complexitySelect) {
                complexitySelect.innerHTML = '<option value="">Loading complexities...</option>';
                complexitySelect.disabled = true;
            }
            
            // Make direct fetch request to the API
            const timestamp = new Date().getTime();
            const apiUrl = `/api/task-type-complexities?task_type_id=${taskTypeId}&task_group_id=${taskGroupId}&_=${timestamp}`;
            console.log('Calling API:', apiUrl);
            
            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('API response status:', response.status);
                return response.text().then(text => {
                    try {
                        // Try to parse as JSON
                        const data = JSON.parse(text);
                        console.log('API response parsed:', data);
                        return data;
                    } catch (e) {
                        console.error('Failed to parse API response:', text);
                        throw new Error('Invalid JSON response from API');
                    }
                });
            })
            .then(data => {
                // Hide loading indicator
                if (complexityLoadingIndicator) {
                    complexityLoadingIndicator.classList.add('d-none');
                }
                
                if (!complexitySelect) {
                    console.error('Complexity select disappeared');
                    return;
                }
                
                // Update complexity select with options
                complexitySelect.innerHTML = '<option value="">-- Select Task Complexity --</option>';
                
                if (data.success && data.complexities && data.complexities.length > 0) {
                    console.log(`Found ${data.complexities.length} complexity options`);
                    
                    // Add the complexity options
                    data.complexities.forEach((complexity, index) => {
                        console.log(`Adding option ${index}:`, complexity);
                        
                        const option = document.createElement('option');
                        option.value = complexity.complexity_level_id;
                        
                        let label = complexity.complexity_name + ' (' + complexity.allocated_minutes + ' min)';
                        if (complexity.is_default) {
                            label += ' [Default]';
                        }
                        
                        option.textContent = label;
                        option.dataset.ttcId = complexity.id; // Store the ttc ID for later
                        
                        complexitySelect.appendChild(option);
                    });
                    
                    // Enable the select
                    complexitySelect.disabled = false;
                    console.log('Complexity select enabled with options');
                    
                    // Add success message
                    addSuccessBanner('Complexity Options Loaded', `Successfully loaded ${data.complexities.length} complexity options for this task type.`);
                } else {
                    console.warn('No complexities found or API error');
                    complexitySelect.innerHTML = '<option value="">No complexity options found</option>';
                    complexitySelect.disabled = true;
                    
                    // Add warning message
                    addWarningBanner('No Complexity Options Found', 
                        `There are no complexity options configured for Task Type ID ${taskTypeId} in Task Group ID ${taskGroupId}.`);
                }
            })
            .catch(error => {
                console.error('Error loading complexities:', error);
                
                // Hide loading indicator
                if (complexityLoadingIndicator) {
                    complexityLoadingIndicator.classList.add('d-none');
                }
                
                if (complexitySelect) {
                    complexitySelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    complexitySelect.disabled = true;
                }
                
                // Add error message
                addErrorBanner('API Error', `Failed to load complexity options: ${error.message}`);
            });
        }
        
        // Add complexity change handler
        if (complexitySelect) {
            complexitySelect.addEventListener('change', function() {
                console.log('Complexity changed to:', this.value);
                
                // Update the hidden task_type_complexity_id input
                if (taskTypeComplexityIdInput && this.selectedIndex > 0) {
                    const selectedOption = this.options[this.selectedIndex];
                    const ttcId = selectedOption.dataset.ttcId;
                    
                    if (ttcId) {
                        taskTypeComplexityIdInput.value = ttcId;
                        console.log('Set task_type_complexity_id to:', ttcId);
                    } else {
                        console.warn('Selected option does not have a ttcId data attribute');
                    }
                } else if (taskTypeComplexityIdInput) {
                    taskTypeComplexityIdInput.value = '';
                    console.log('Cleared task_type_complexity_id');
                }
            });
        }
        
        function addSuccessBanner(title, message) {
            const banner = document.createElement('div');
            banner.className = 'alert alert-success alert-dismissible fade show mb-3 debug-banner';
            banner.innerHTML = `
                <strong>${title}</strong> 
                <p>${message}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            insertBanner(banner);
        }
        
        function addWarningBanner(title, message) {
            const banner = document.createElement('div');
            banner.className = 'alert alert-warning alert-dismissible fade show mb-3 debug-banner';
            banner.innerHTML = `
                <strong>${title}</strong> 
                <p>${message}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            insertBanner(banner);
        }
        
        function addErrorBanner(title, message) {
            const banner = document.createElement('div');
            banner.className = 'alert alert-danger alert-dismissible fade show mb-3 debug-banner';
            banner.innerHTML = `
                <strong>${title}</strong> 
                <p>${message}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            insertBanner(banner);
        }
        
        function insertBanner(bannerElement) {
            // Remove any existing debug banners
            document.querySelectorAll('.debug-banner').forEach(el => el.remove());
            
            // Find the best location to insert the banner
            const taskCreationForm = document.getElementById('taskCreationForm');
            const taskTypeComplexitySection = document.getElementById('taskTypeComplexitySection');
            
            if (taskCreationForm) {
                taskCreationForm.insertBefore(bannerElement, taskCreationForm.firstChild);
            } else if (taskTypeComplexitySection) {
                taskTypeComplexitySection.parentNode.insertBefore(bannerElement, taskTypeComplexitySection);
            } else {
                // Fallback - just add it near the complexity select
                if (complexitySelect) {
                    complexitySelect.parentNode.insertBefore(bannerElement, complexitySelect);
                }
            }
        }
    });
})(); 