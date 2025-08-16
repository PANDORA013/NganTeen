@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-broadcast-tower text-blue-500 mr-3"></i>
                Real-time Notifications Test
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Test Controls -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">Test Controls</h2>
                    
                    <div class="space-y-4">
                        <button onclick="testMenuBroadcast()" 
                                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                            <i class="fas fa-utensils mr-2"></i>
                            Test Menu Added
                        </button>
                        
                        <button onclick="testOrderBroadcast()" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Test Order Status Update
                        </button>
                        
                        <button onclick="clearNotifications()" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Clear Notifications
                        </button>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">Connection Status</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span>Pusher Connection:</span>
                            <span id="pusher-status" class="px-2 py-1 rounded text-sm">
                                <i class="fas fa-circle text-yellow-500"></i> Connecting...
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span>Menu Channel:</span>
                            <span id="menu-channel-status" class="px-2 py-1 rounded text-sm">
                                <i class="fas fa-circle text-yellow-500"></i> Subscribing...
                            </span>
                        </div>
                        
                        @auth
                        <div class="flex items-center justify-between">
                            <span>User Channel:</span>
                            <span id="user-channel-status" class="px-2 py-1 rounded text-sm">
                                <i class="fas fa-circle text-yellow-500"></i> Subscribing...
                            </span>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Event Log -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Event Log</h2>
                <div id="event-log" class="bg-gray-100 rounded-lg p-4 h-64 overflow-y-auto">
                    <p class="text-gray-500 text-sm">Waiting for events...</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    How to Test
                </h3>
                <ol class="list-decimal list-inside text-blue-700 space-y-1">
                    <li>Make sure the queue worker is running: <code class="bg-blue-100 px-2 py-1 rounded">php artisan queue:work</code></li>
                    <li>Click the test buttons above to trigger real-time events</li>
                    <li>Watch for notifications in the top-right corner</li>
                    <li>Check the event log below for detailed information</li>
                    <li>Open this page in multiple tabs to see real-time sync</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize connection status monitoring
    initializeStatusMonitoring();
    
    // Setup event logging
    setupEventLogging();
});

function initializeStatusMonitoring() {
    if (typeof window.Echo !== 'undefined') {
        // Monitor Pusher connection
        window.Echo.connector.pusher.connection.bind('connected', function() {
            updateStatus('pusher-status', 'Connected', 'green');
        });
        
        window.Echo.connector.pusher.connection.bind('disconnected', function() {
            updateStatus('pusher-status', 'Disconnected', 'red');
        });
        
        window.Echo.connector.pusher.connection.bind('error', function() {
            updateStatus('pusher-status', 'Error', 'red');
        });
        
        // Check if already connected
        if (window.Echo.connector.pusher.connection.state === 'connected') {
            updateStatus('pusher-status', 'Connected', 'green');
        }
    }
}

function setupEventLogging() {
    if (typeof window.Echo !== 'undefined') {
        // Listen to menu updates
        window.Echo.channel('menu.updates')
            .listen('.menu.added', function(e) {
                logEvent('Menu Added', e, 'green');
                updateStatus('menu-channel-status', 'Active', 'green');
            });
        
        @auth
        // Listen to user-specific updates
        window.Echo.private('user.{{ auth()->id() }}')
            .listen('.order.status.updated', function(e) {
                logEvent('Order Status Updated', e, 'blue');
                updateStatus('user-channel-status', 'Active', 'green');
            });
        @endauth
    }
}

function updateStatus(elementId, text, color) {
    const element = document.getElementById(elementId);
    if (element) {
        const colorClass = color === 'green' ? 'text-green-500' : 
                          color === 'red' ? 'text-red-500' : 'text-yellow-500';
        element.innerHTML = `<i class="fas fa-circle ${colorClass}"></i> ${text}`;
    }
}

function logEvent(type, data, color) {
    const log = document.getElementById('event-log');
    const timestamp = new Date().toLocaleTimeString();
    const colorClass = color === 'green' ? 'text-green-600' : 
                      color === 'blue' ? 'text-blue-600' : 'text-gray-600';
    
    const eventDiv = document.createElement('div');
    eventDiv.className = 'mb-2 p-2 bg-white rounded border-l-4 border-l-' + color + '-400';
    eventDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="font-semibold ${colorClass}">${type}</span>
            <span class="text-xs text-gray-500">${timestamp}</span>
        </div>
        <pre class="text-xs text-gray-600 mt-1 overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>
    `;
    
    log.insertBefore(eventDiv, log.firstChild);
    
    // Keep only last 10 events
    while (log.children.length > 10) {
        log.removeChild(log.lastChild);
    }
}

function testMenuBroadcast() {
    fetch('/api/test/menu-broadcast', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Menu broadcast test:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testOrderBroadcast() {
    fetch('/api/test/order-broadcast', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Order broadcast test:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function clearNotifications() {
    const container = document.getElementById('notification-container');
    if (container) {
        container.innerHTML = '';
    }
    
    const log = document.getElementById('event-log');
    if (log) {
        log.innerHTML = '<p class="text-gray-500 text-sm">Event log cleared...</p>';
    }
}
</script>
@endsection
