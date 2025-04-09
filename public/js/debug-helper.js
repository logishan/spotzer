/**
 * Debug Helper - A utility for logging debug information
 * 
 * This helper provides several features:
 * 1. Console logging with type-specific methods (log, info, warn, error)
 * 2. Visual logging panel that appears when console is not available
 * 3. Automatic console availability detection
 * 4. Manual panel toggling via keyboard shortcut (Ctrl+Shift+D)
 */

const DebugHelper = (function() {
    // Private variables
    let _logs = [];
    let _panelVisible = false;
    let _panel = null;
    let _logList = null;
    
    // Public API
    const api = {
        // Configurable settings
        maxLogs: 100,
        showTimestamps: true,
        _consoleAvailable: true,  // Made public for testing purposes
        
        /**
         * Initialize the debug helper
         */
        init: function() {
            this.checkConsole();
            this._createPanel();
            this._setupKeyboardShortcut();
            
            // Log initialization
            this.log('Debug Helper initialized');
        },
        
        /**
         * Check if console is available
         */
        checkConsole: function() {
            try {
                // Check if console exists and console.log is a function
                this._consoleAvailable = (
                    typeof console !== 'undefined' && 
                    typeof console.log === 'function'
                );
                
                // Log a test message to verify console works
                if (this._consoleAvailable) {
                    console.log('%c Debug Helper ', 
                        'background: #3498db; color: white; padding: 2px 5px; border-radius: 3px;', 
                        'Console available');
                }
            } catch(e) {
                this._consoleAvailable = false;
            }
            
            // If console is not available, show the panel
            if (!this._consoleAvailable) {
                this.showPanel();
            }
            
            return this._consoleAvailable;
        },
        
        /**
         * Log a message (regular log)
         */
        log: function(message, data) {
            this._logWithLevel('log', message, data);
        },
        
        /**
         * Log an informational message
         */
        info: function(message, data) {
            this._logWithLevel('info', message, data);
        },
        
        /**
         * Log a warning message
         */
        warn: function(message, data) {
            this._logWithLevel('warn', message, data);
        },
        
        /**
         * Log an error message
         */
        error: function(message, data) {
            this._logWithLevel('error', message, data);
        },
        
        /**
         * Log a debug message
         */
        debug: function(message, data) {
            this._logWithLevel('debug', message, data);
        },
        
        /**
         * Show the debug panel
         */
        showPanel: function() {
            if (_panel) {
                _panel.style.display = 'block';
                _panelVisible = true;
            }
        },
        
        /**
         * Hide the debug panel
         */
        hidePanel: function() {
            if (_panel) {
                _panel.style.display = 'none';
                _panelVisible = false;
            }
        },
        
        /**
         * Toggle the debug panel
         */
        togglePanel: function() {
            if (_panelVisible) {
                this.hidePanel();
            } else {
                this.showPanel();
            }
        },
        
        /**
         * Clear all logs
         */
        clearLogs: function() {
            _logs = [];
            if (_logList) {
                _logList.innerHTML = '';
            }
            
            if (this._consoleAvailable) {
                console.clear();
                console.log('%c Debug Helper ', 
                    'background: #3498db; color: white; padding: 2px 5px; border-radius: 3px;', 
                    'Logs cleared');
            }
        },
        
        /**
         * Get all logs
         */
        getLogs: function() {
            return _logs.slice(); // Return a copy of the logs
        },
        
        /**
         * Log with a specific level
         * @private
         */
        _logWithLevel: function(level, message, data) {
            // Create log entry
            const timestamp = new Date();
            const logEntry = {
                level: level,
                message: message,
                data: data,
                timestamp: timestamp
            };
            
            // Add to logs array (limit to max logs)
            _logs.push(logEntry);
            if (_logs.length > this.maxLogs) {
                _logs.shift(); // Remove oldest log
            }
            
            // Log to console if available
            if (this._consoleAvailable) {
                if (data !== undefined) {
                    console[level](
                        `%c${this.showTimestamps ? '[' + timestamp.toLocaleTimeString() + '] ' : ''}${message}`,
                        this._getConsoleStyle(level),
                        data
                    );
                } else {
                    console[level](
                        `%c${this.showTimestamps ? '[' + timestamp.toLocaleTimeString() + '] ' : ''}${message}`,
                        this._getConsoleStyle(level)
                    );
                }
            }
            
            // Add to visual panel
            this._addLogToPanel(logEntry);
        },
        
        /**
         * Create the visual debug panel
         * @private
         */
        _createPanel: function() {
            // Create panel elements if they don't exist yet
            if (!_panel) {
                _panel = document.createElement('div');
                _panel.id = 'debug-helper-panel';
                _panel.style.cssText = `
                    position: fixed;
                    bottom: 0;
                    right: 0;
                    width: 400px;
                    max-height: 300px;
                    background-color: rgba(44, 62, 80, 0.95);
                    color: #ecf0f1;
                    z-index: 9999;
                    font-family: monospace;
                    font-size: 12px;
                    border-top-left-radius: 5px;
                    box-shadow: -3px -3px 5px rgba(0,0,0,0.2);
                    display: none;
                    display: flex;
                    flex-direction: column;
                `;
                
                // Header
                const header = document.createElement('div');
                header.style.cssText = `
                    padding: 5px 10px;
                    background-color: #34495e;
                    border-top-left-radius: 5px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                `;
                header.innerHTML = '<span style="font-weight: bold;">Debug Helper</span>';
                
                // Controls
                const controls = document.createElement('div');
                controls.style.cssText = `
                    display: flex;
                    gap: 5px;
                `;
                
                // Clear button
                const clearBtn = document.createElement('button');
                clearBtn.textContent = 'Clear';
                clearBtn.style.cssText = `
                    background-color: #e74c3c;
                    color: white;
                    border: none;
                    padding: 2px 5px;
                    border-radius: 3px;
                    cursor: pointer;
                    font-size: 10px;
                `;
                clearBtn.onclick = () => this.clearLogs();
                
                // Close button
                const closeBtn = document.createElement('button');
                closeBtn.textContent = 'Hide';
                closeBtn.style.cssText = `
                    background-color: #7f8c8d;
                    color: white;
                    border: none;
                    padding: 2px 5px;
                    border-radius: 3px;
                    cursor: pointer;
                    font-size: 10px;
                `;
                closeBtn.onclick = () => this.hidePanel();
                
                controls.appendChild(clearBtn);
                controls.appendChild(closeBtn);
                header.appendChild(controls);
                
                // Log list container
                const logContainer = document.createElement('div');
                logContainer.style.cssText = `
                    overflow-y: auto;
                    max-height: calc(300px - 30px);
                    padding: 5px 0;
                `;
                
                _logList = document.createElement('div');
                _logList.style.cssText = `
                    display: flex;
                    flex-direction: column;
                `;
                
                logContainer.appendChild(_logList);
                
                // Append all elements
                _panel.appendChild(header);
                _panel.appendChild(logContainer);
                
                // Add to document
                document.body.appendChild(_panel);
            }
        },
        
        /**
         * Add a log entry to the visual panel
         * @private
         */
        _addLogToPanel: function(logEntry) {
            if (!_logList) return;
            
            const logItem = document.createElement('div');
            logItem.style.cssText = `
                padding: 3px 10px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
                word-break: break-word;
            `;
            
            // Apply styles based on log level
            switch(logEntry.level) {
                case 'error':
                    logItem.style.backgroundColor = 'rgba(231, 76, 60, 0.2)';
                    logItem.style.borderLeft = '3px solid #e74c3c';
                    break;
                case 'warn':
                    logItem.style.backgroundColor = 'rgba(241, 196, 15, 0.2)';
                    logItem.style.borderLeft = '3px solid #f1c40f';
                    break;
                case 'info':
                    logItem.style.backgroundColor = 'rgba(52, 152, 219, 0.2)';
                    logItem.style.borderLeft = '3px solid #3498db';
                    break;
                case 'debug':
                    logItem.style.backgroundColor = 'rgba(142, 68, 173, 0.2)';
                    logItem.style.borderLeft = '3px solid #8e44ad';
                    break;
                default:
                    logItem.style.borderLeft = '3px solid #95a5a6';
            }
            
            // Create content
            let content = '';
            
            // Add timestamp if enabled
            if (this.showTimestamps) {
                content += `<span style="color: #95a5a6; font-size: 10px;">[${logEntry.timestamp.toLocaleTimeString()}]</span> `;
            }
            
            // Add message
            content += `<span>${this._escapeHtml(logEntry.message)}</span>`;
            
            // Add data if available
            if (logEntry.data !== undefined) {
                try {
                    const dataStr = typeof logEntry.data === 'object' 
                        ? JSON.stringify(logEntry.data, null, 2)
                        : logEntry.data.toString();
                    
                    content += `<div style="margin-top: 3px; padding-left: 10px; color: #bdc3c7; font-style: italic;">${this._escapeHtml(dataStr)}</div>`;
                } catch (e) {
                    content += '<div style="margin-top: 3px; padding-left: 10px; color: #e74c3c;">[Cannot display data]</div>';
                }
            }
            
            logItem.innerHTML = content;
            _logList.appendChild(logItem);
            
            // Scroll to bottom
            logItem.scrollIntoView({ behavior: 'smooth', block: 'end' });
        },
        
        /**
         * Set up keyboard shortcut (Ctrl+Shift+D) to toggle panel
         * @private
         */
        _setupKeyboardShortcut: function() {
            document.addEventListener('keydown', (e) => {
                // Ctrl+Shift+D to toggle panel
                if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                    e.preventDefault();
                    this.togglePanel();
                }
            });
        },
        
        /**
         * Get console style based on log level
         * @private
         */
        _getConsoleStyle: function(level) {
            const baseStyle = 'padding: 2px 5px; border-radius: 3px; color: white;';
            switch(level) {
                case 'error': 
                    return baseStyle + 'background: #e74c3c;';
                case 'warn': 
                    return baseStyle + 'background: #f39c12; color: #000;';
                case 'info': 
                    return baseStyle + 'background: #3498db;';
                case 'debug': 
                    return baseStyle + 'background: #8e44ad;';
                default: 
                    return baseStyle + 'background: #2c3e50;';
            }
        },
        
        /**
         * Escape HTML to prevent XSS
         * @private
         */
        _escapeHtml: function(text) {
            if (typeof text !== 'string') {
                text = String(text);
            }
            
            return text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    };
    
    // Initialize when the DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => api.init());
    } else {
        // DOM already loaded
        setTimeout(() => api.init(), 0);
    }
    
    return api;
})(); 