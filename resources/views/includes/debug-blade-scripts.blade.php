{{-- Debug Script for Blade @push/@stack functionality --}}
<div id="blade-script-debugger" style="display: none;">
    <div class="debug-info"></div>
</div>

@push('scripts')
<script>
(function() {
    // Record when this script was executed
    const loadTime = new Date().toISOString();
    const scriptId = 'blade-push-debug-' + Math.random().toString(36).substr(2, 9);
    
    // Store script info in window so we can check for duplicates
    window.bladeScriptDebug = window.bladeScriptDebug || {
        instances: []
    };
    
    // Add this instance to the tracked instances
    window.bladeScriptDebug.instances.push({
        id: scriptId,
        loadTime: loadTime,
        location: 'Pushed via @push directive'
    });
    
    console.log(`[Blade Script Debug] Script loaded at ${loadTime}`);
    console.log(`[Blade Script Debug] Instance ID: ${scriptId}`);
    
    // Create a helper to add debug banners to the page
    function addDebugBanner(message, type = 'info') {
        const debugger = document.getElementById('blade-script-debugger');
        const debugInfo = debugger.querySelector('.debug-info');
        
        const banner = document.createElement('div');
        banner.style.padding = '10px';
        banner.style.margin = '5px 0';
        banner.style.borderRadius = '4px';
        banner.style.fontFamily = 'monospace';
        banner.style.fontSize = '12px';
        
        switch(type) {
            case 'success':
                banner.style.backgroundColor = '#d4edda';
                banner.style.color = '#155724';
                banner.style.border = '1px solid #c3e6cb';
                break;
            case 'error':
                banner.style.backgroundColor = '#f8d7da';
                banner.style.color = '#721c24';
                banner.style.border = '1px solid #f5c6cb';
                break;
            case 'warning':
                banner.style.backgroundColor = '#fff3cd';
                banner.style.color = '#856404';
                banner.style.border = '1px solid #ffeeba';
                break;
            default:
                banner.style.backgroundColor = '#d1ecf1';
                banner.style.color = '#0c5460';
                banner.style.border = '1px solid #bee5eb';
        }
        
        banner.textContent = message;
        debugInfo.appendChild(banner);
        
        // Make sure the debugger is visible
        debugger.style.display = 'block';
        debugger.style.position = 'fixed';
        debugger.style.bottom = '10px';
        debugger.style.right = '10px';
        debugger.style.zIndex = '9999';
        debugger.style.width = '300px';
        debugger.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        debugger.style.border = '1px solid #ddd';
        debugger.style.borderRadius = '4px';
        debugger.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
        debugger.style.padding = '10px';
    }
    
    // Function to check Blade script status
    function checkBladeScriptStatus() {
        const instances = window.bladeScriptDebug.instances;
        
        // Check if we have multiple instances (possible duplicate)
        if (instances.length > 1) {
            console.warn(`[Blade Script Debug] Multiple script instances detected: ${instances.length}`);
            addDebugBanner(`Multiple @push script instances detected: ${instances.length}`, 'warning');
            
            // Log all instances
            console.table(instances);
        } else {
            console.log('[Blade Script Debug] Single script instance correctly loaded');
            addDebugBanner('Script properly loaded via @push', 'success');
        }
        
        // Try to determine if we're using @yield('scripts') or @stack('scripts')
        document.addEventListener('DOMContentLoaded', function() {
            // Check the HTML source to see if @yield('scripts') or @stack('scripts') is present
            const html = document.documentElement.outerHTML;
            
            // This will help detect if the layout is using @stack
            if (html.includes('blade-push-debug-')) {
                console.log('[Blade Script Debug] Script content found in rendered HTML - @stack is likely working');
                addDebugBanner('@stack directive appears to be working correctly', 'success');
            } else {
                console.error('[Blade Script Debug] Script content not found in HTML - possible @stack issue');
                addDebugBanner('Script content missing - check @stack configuration', 'error');
            }
        });
    }
    
    // Check if jQuery is available (common dependency)
    if (typeof jQuery !== 'undefined') {
        console.log('[Blade Script Debug] jQuery is available: ' + jQuery.fn.jquery);
        jQuery(document).ready(function() {
            console.log('[Blade Script Debug] jQuery document ready fired');
            addDebugBanner('jQuery is available: v' + jQuery.fn.jquery, 'info');
        });
    } else {
        console.log('[Blade Script Debug] jQuery is NOT available');
    }
    
    // Run the check when the document is fully loaded
    window.addEventListener('load', function() {
        console.log('[Blade Script Debug] Window load event fired');
        setTimeout(checkBladeScriptStatus, 500); // Small delay to ensure everything is loaded
    });
    
    // Expose function globally for manual checking from console
    window.checkBladeScriptsStatus = checkBladeScriptStatus;
})();
</script>
@endpush

{{-- This comment provides usage instructions --}}
{{-- 
    HOW TO USE:
    1. Include this file in your Blade template using: @include('includes.debug-blade-scripts')
    2. Make sure your layout has @stack('scripts') somewhere, usually before the closing </body> tag
    3. Check browser console for debug messages
    4. A debug banner will appear in the bottom-right corner if scripts are loaded
    5. You can manually check script status by running checkBladeScriptsStatus() in the console
--}} 