// Debug Blade Scripts: Detects whether @push('scripts') is working properly
(function() {
    // Record the exact time this script loaded
    const scriptLoadTime = new Date().toISOString();
    
    // This ID helps us identify this specific script instance
    const scriptId = Math.random().toString(36).substring(2, 8);
    
    // Log immediately when script is parsed
    console.log(`[BLADE-DEBUG-${scriptId}] Script loaded at ${scriptLoadTime}`);
    
    // Function to add debug banners to the page
    function addDebugBanner(message, type = 'info') {
        // Create the banner element
        const banner = document.createElement('div');
        banner.style.position = 'fixed';
        banner.style.top = '0';
        banner.style.left = '0';
        banner.style.right = '0';
        banner.style.padding = '10px';
        banner.style.zIndex = '9999';
        banner.style.fontFamily = 'monospace';
        banner.style.textAlign = 'center';
        
        // Set color based on type
        switch(type) {
            case 'success':
                banner.style.backgroundColor = '#d4edda';
                banner.style.color = '#155724';
                banner.style.borderBottom = '1px solid #c3e6cb';
                break;
            case 'warning':
                banner.style.backgroundColor = '#fff3cd';
                banner.style.color = '#856404';
                banner.style.borderBottom = '1px solid #ffeeba';
                break;
            case 'error':
                banner.style.backgroundColor = '#f8d7da';
                banner.style.color = '#721c24';
                banner.style.borderBottom = '1px solid #f5c6cb';
                break;
            default:
                banner.style.backgroundColor = '#d1ecf1';
                banner.style.color = '#0c5460';
                banner.style.borderBottom = '1px solid #bee5eb';
        }
        
        // Add content and make it dismissible
        banner.innerHTML = `
            ${message}
            <span style="position:absolute;right:10px;top:10px;cursor:pointer;font-weight:bold;" 
                  onclick="this.parentNode.remove()">×</span>
        `;
        
        // Add to document
        document.body.appendChild(banner);
        
        // Automatically remove after 10 seconds if not dismissed
        setTimeout(() => {
            if (document.body.contains(banner)) {
                banner.remove();
            }
        }, 10000);
        
        // Also log to console
        console.log(`[BLADE-DEBUG-${scriptId}] ${message}`);
    }
    
    // When DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log(`[BLADE-DEBUG-${scriptId}] DOMContentLoaded fired - checking for @push('scripts') functionality`);
        
        // Create a test flag in the window object to check if multiple instances are loaded
        if (window.bladeScriptDebuggerLoaded) {
            console.warn(`[BLADE-DEBUG-${scriptId}] Multiple instances of the debug script detected!`);
        } else {
            window.bladeScriptDebuggerLoaded = true;
        }
        
        // Check the page to find what stack system is being used
        const pageHtml = document.documentElement.outerHTML;
        const usingYield = pageHtml.includes('@yield(\'scripts\')');
        const usingStack = pageHtml.includes('@stack(\'scripts\')');
        
        // Add banner once DOM is loaded
        addDebugBanner(`Blade Scripts Debugger Active (ID: ${scriptId})`, 'info');
        
        // After a short delay, check the layout template usage
        setTimeout(function() {
            if (usingYield) {
                addDebugBanner('Warning: Found @yield(\'scripts\') in the layout! Scripts pushed via @push won\'t be included.', 'warning');
                console.warn(`[BLADE-DEBUG-${scriptId}] Found @yield('scripts') in the layout. This won't work with @push('scripts')`);
            } else if (usingStack) {
                addDebugBanner('Good: Found @stack(\'scripts\') in the layout, which is correct for @push(\'scripts\').', 'success');
                console.log(`[BLADE-DEBUG-${scriptId}] Found @stack('scripts') in the layout, which is correct`);
            } else {
                addDebugBanner('Error: Could not detect either @stack or @yield for scripts. Check your layout files.', 'error');
                console.error(`[BLADE-DEBUG-${scriptId}] Could not detect script inclusion method in the layout`);
            }
            
            // Check if jQuery is available (common dependency)
            if (typeof jQuery !== 'undefined') {
                console.log(`[BLADE-DEBUG-${scriptId}] jQuery is available: version ${jQuery.fn.jquery}`);
            } else {
                console.warn(`[BLADE-DEBUG-${scriptId}] jQuery is NOT available`);
            }
        }, 500);
    });
    
    // When the window is fully loaded (including all resources)
    window.addEventListener('load', function() {
        console.log(`[BLADE-DEBUG-${scriptId}] Window fully loaded event fired`);
        
        // Calculate how long it took from script loading to window load
        const windowLoadTime = new Date();
        const scriptElement = document.currentScript || document.querySelector('script[src*="debug-blade-scripts.js"]');
        
        if (scriptElement) {
            console.log(`[BLADE-DEBUG-${scriptId}] Script element found in DOM:`, scriptElement);
        } else {
            console.warn(`[BLADE-DEBUG-${scriptId}] Script element NOT found in DOM - may indicate improper loading`);
        }
    });
    
    // Expose a global function that can be called from the console to check status
    window.checkBladeScriptsStatus = function() {
        console.log(`[BLADE-DEBUG-${scriptId}] Manual check triggered at ${new Date().toISOString()}`);
        console.log(`[BLADE-DEBUG-${scriptId}] Script was loaded at ${scriptLoadTime}`);
        
        return {
            scriptId: scriptId,
            loadTime: scriptLoadTime,
            multipleInstances: window.bladeScriptDebuggerLoaded !== undefined,
            isWorking: true
        };
    };
    
    // Log that setup is complete
    console.log(`[BLADE-DEBUG-${scriptId}] Debug script setup complete`);
})(); 