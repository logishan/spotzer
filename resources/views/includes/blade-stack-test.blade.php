{{-- 
    Blade Stack Test
    This file helps verify that @push and @stack directives are working correctly.
    
    Usage:
    1. Include this file in your template: @include('includes.blade-stack-test')
    2. Make sure your layout has a @stack('scripts') somewhere in the <body>
    3. Check browser console and visual indicators to confirm functionality
--}}

{{-- Add a small indicator to show this file was included --}}
<div id="blade-stack-test-indicator" style="position: fixed; top: 10px; right: 10px; z-index: 9999; background-color: rgba(0,0,0,0.7); color: white; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;">
    Test file included ✓
</div>

{{-- Push the JavaScript to the 'scripts' stack --}}
@push('scripts')
<script>
    // Record when this script runs
    (function() {
        // Create a test object if it doesn't exist
        if (!window.bladeStackTest) {
            window.bladeStackTest = {
                loaded: true,
                timestamp: new Date().toISOString(),
                pushCount: 0,
                checkResults: {}
            };
        }
        
        // Increment push count to detect multiple inclusions
        window.bladeStackTest.pushCount++;
        
        // Function to update the visual indicator
        function updateIndicator(message, isSuccess) {
            const indicator = document.getElementById('blade-stack-test-indicator');
            if (indicator) {
                indicator.innerHTML = message;
                indicator.style.backgroundColor = isSuccess ? 'rgba(39, 174, 96, 0.9)' : 'rgba(231, 76, 60, 0.9)';
            }
        }
        
        // Wait for document to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Log status to console
            console.log('=== BLADE STACK TEST ===');
            console.log('Script loaded via @push at: ' + window.bladeStackTest.timestamp);
            console.log('@push count:', window.bladeStackTest.pushCount);
            
            // Check if script appears in the right place
            const scripts = document.querySelectorAll('script');
            let stackFound = false;
            
            // Look for this script in the DOM
            for (let i = 0; i < scripts.length; i++) {
                if (scripts[i].textContent.includes('window.bladeStackTest')) {
                    stackFound = true;
                    window.bladeStackTest.checkResults.foundInDOM = true;
                    break;
                }
            }
            
            // Update results
            window.bladeStackTest.checkResults.stackWorking = stackFound;
            
            // Display results
            if (stackFound) {
                updateIndicator('✅ Blade @stack working correctly<br>Push count: ' + window.bladeStackTest.pushCount, true);
                console.log('✅ SUCCESS: Script found in the DOM - @stack is working!');
            } else {
                updateIndicator('❌ Blade @stack not working<br>Script not found in DOM', false);
                console.error('❌ ERROR: Script not found in the DOM - @stack may not be working!');
            }
            
            // Check for multiple inclusions
            if (window.bladeStackTest.pushCount > 1) {
                console.warn('⚠️ WARNING: Script included ' + window.bladeStackTest.pushCount + ' times');
            }
            
            // Add a link to the universal debug helper
            const debugHelperLink = document.createElement('div');
            debugHelperLink.style.marginTop = '10px';
            debugHelperLink.style.fontSize = '10px';
            debugHelperLink.innerHTML = '<a href="#" style="color: #3498db;" onclick="activateUniversalDebugger(); return false;">Activate Universal Debugger</a>';
            document.getElementById('blade-stack-test-indicator').appendChild(debugHelperLink);
        });
        
        // Function to load the universal debug helper
        window.activateUniversalDebugger = function() {
            // Check if already loaded
            if (window.DebugHelper) {
                DebugHelper.log('Debug helper already active');
                return;
            }
            
            // Create script element to load debug helper
            const script = document.createElement('script');
            script.src = '/js/debug-helper.js';
            script.onload = function() {
                if (window.DebugHelper) {
                    DebugHelper.log('Universal Debug Helper activated from Blade Stack Test');
                    DebugHelper.info('Blade Stack Test Results:');
                    DebugHelper.info(window.bladeStackTest);
                }
            };
            script.onerror = function() {
                console.error('Failed to load Universal Debug Helper');
                alert('Failed to load Debug Helper. Make sure /js/debug-helper.js exists.');
            };
            
            document.body.appendChild(script);
        };
    })();
</script>
@endpush

{{-- Add a small note at the bottom of the including page --}}
<div style="font-size: 10px; color: #7f8c8d; margin-top: 50px; font-family: monospace; text-align: center;">
    Blade Stack Test included. Check console for details.
</div> 