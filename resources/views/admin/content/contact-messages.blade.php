@extends('layouts.admin')

@section('title', 'Contact Messages Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Contact Messages Management</h1>
        <p class="page-subtitle">Kelola pesan kontak dari pengguna dan berikan respons yang tepat</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline-primary" onclick="markAllAsRead()">
            <i class="fas fa-check-double me-2"></i>Mark All as Read
        </button>
        <button type="button" class="btn btn-outline-danger" onclick="deleteAllRead()">
            <i class="fas fa-trash me-2"></i>Delete Read Messages
        </button>
    </div>
</div>

<!-- Contact Messages Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ count($messages) }}</div>
                            <div class="stats-label">Total Messages</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #f093fb; --card-bg-to: #f5576c;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ collect($messages)->where('is_read', false)->count() }}</div>
                            <div class="stats-label">Unread Messages</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #4facfe; --card-bg-to: #00f2fe;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ collect($messages)->where('is_read', true)->count() }}</div>
                            <div class="stats-label">Read Messages</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #43e97b; --card-bg-to: #38f9d7;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ collect($messages)->whereNotNull('phone')->count() }}</div>
                            <div class="stats-label">With Phone</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Filters -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted me-2">Filter:</span>
            <button class="btn btn-outline-primary message-filter active" data-status="all">
                <i class="fas fa-list me-1"></i>All ({{ count($messages) }})
            </button>
            <button class="btn btn-outline-warning message-filter" data-status="unread">
                <i class="fas fa-envelope me-1"></i>Unread ({{ collect($messages)->where('is_read', false)->count() }})
            </button>
            <button class="btn btn-outline-success message-filter" data-status="read">
                <i class="fas fa-check me-1"></i>Read ({{ collect($messages)->where('is_read', true)->count() }})
            </button>
            <div class="ms-auto">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchMessages" placeholder="Search messages...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Messages List -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-envelope me-2"></i>Pesan Kontak
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshMessages()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(empty($messages) || count($messages) === 0)
            <div class="text-center py-5">
                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pesan kontak</h5>
                <p class="text-muted">Pesan kontak dari pengguna akan muncul di sini</p>
            </div>
        @else
            <div class="messages-list" id="messagesList">
                @foreach($messages as $index => $message)
                <div class="message-item {{ $message['is_read'] ? 'read' : 'unread' }}" data-status="{{ $message['is_read'] ? 'read' : 'unread' }}">
                    <div class="card mb-3 message-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @if(!$message['is_read'])
                                            <span class="badge bg-warning me-2">
                                                <i class="fas fa-envelope me-1"></i>New
                                            </span>
                                        @else
                                            <span class="badge bg-success me-2">
                                                <i class="fas fa-check me-1"></i>Read
                                            </span>
                                        @endif
                                        <h6 class="mb-0 fw-bold">{{ $message['name'] }}</h6>
                                    </div>
                                    
                                    <div class="message-meta mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <a href="mailto:{{ $message['email'] }}" class="text-decoration-none">{{ $message['email'] }}</a>
                                                </small>
                                            </div>
                                            @if(isset($message['phone']) && $message['phone'])
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <a href="tel:{{ $message['phone'] }}" class="text-decoration-none">{{ $message['phone'] }}</a>
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="message-subject mb-2">
                                        <strong>{{ $message['subject'] ?? 'No Subject' }}</strong>
                                    </div>
                                    
                                    <div class="message-content">
                                        <p class="mb-0">{{ $message['message'] }}</p>
                                    </div>
                                    
                                    <div class="message-date mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ date('d M Y, H:i', strtotime($message['created_at'])) }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="message-actions">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(!$message['is_read'])
                                            <li><a class="dropdown-item" href="#" onclick="markAsRead({{ $index }})">
                                                <i class="fas fa-check me-2"></i>Mark as Read
                                            </a></li>
                                            @else
                                            <li><a class="dropdown-item" href="#" onclick="markAsUnread({{ $index }})">
                                                <i class="fas fa-envelope me-2"></i>Mark as Unread
                                            </a></li>
                                            @endif
                                            <li><a class="dropdown-item" href="mailto:{{ $message['email'] }}?subject=Re: {{ $message['subject'] ?? 'Your Message' }}">
                                                <i class="fas fa-reply me-2"></i>Reply via Email
                                            </a></li>
                                            @if(isset($message['phone']) && $message['phone'])
                                            <li><a class="dropdown-item" href="tel:{{ $message['phone'] }}">
                                                <i class="fas fa-phone me-2"></i>Call
                                            </a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteMessage({{ $index }})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-reply me-2"></i>Reply to Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="replyForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">To:</label>
                        <input type="email" class="form-control" id="replyTo" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Subject:</label>
                        <input type="text" class="form-control" id="replySubject">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Message:</label>
                        <textarea class="form-control" id="replyMessage" rows="6"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.message-card {
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.message-item.unread .message-card {
    border-left: 4px solid #f39c12;
    background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%);
}

.message-item.read .message-card {
    border-left: 4px solid #27ae60;
}

.message-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.message-filter {
    transition: all 0.3s ease;
}

.message-filter.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}

.message-meta a {
    color: #6c757d;
}

.message-meta a:hover {
    color: var(--bs-primary);
}

.message-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.message-item {
    transition: all 0.3s ease;
}

.message-item.d-none {
    display: none !important;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
// Global messages data
let messagesData = @json($messages);

// Message filtering
document.querySelectorAll('.message-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        const status = this.dataset.status;
        
        // Update active button
        document.querySelectorAll('.message-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter messages
        document.querySelectorAll('.message-item').forEach(message => {
            if (status === 'all' || message.dataset.status === status) {
                message.classList.remove('d-none');
            } else {
                message.classList.add('d-none');
            }
        });
    });
});

// Search functionality
document.getElementById('searchMessages').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    
    document.querySelectorAll('.message-item').forEach(message => {
        const text = message.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            message.classList.remove('d-none');
        } else {
            message.classList.add('d-none');
        }
    });
});

// Refresh messages
function refreshMessages() {
    location.reload();
}

// Mark as read
function markAsRead(index) {
    fetch(`/admin/content/contact-messages/${index}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ is_read: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menandai pesan');
    });
}

// Mark as unread
function markAsUnread(index) {
    fetch(`/admin/content/contact-messages/${index}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ is_read: false })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menandai pesan');
    });
}

// Delete message
function deleteMessage(index) {
    const message = messagesData[index];
    
    if (confirm(`Delete message from ${message.name}?`)) {
        fetch(`/admin/content/contact-messages/${index}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pesan');
        });
    }
}

// Mark all as read
function markAllAsRead() {
    if (confirm('Mark all messages as read?')) {
        fetch('/admin/content/contact-messages/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menandai semua pesan');
        });
    }
}

// Delete all read messages
function deleteAllRead() {
    if (confirm('Delete all read messages? This action cannot be undone.')) {
        fetch('/admin/content/contact-messages/delete-read', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus pesan');
        });
    }
}

// Reply functionality (opens email client)
function replyToMessage(index) {
    const message = messagesData[index];
    const subject = `Re: ${message.subject || 'Your Message'}`;
    const body = `\n\n--- Original Message ---\nFrom: ${message.name} <${message.email}>\nDate: ${message.created_at}\nSubject: ${message.subject || 'No Subject'}\n\n${message.message}`;
    
    window.location.href = `mailto:${message.email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
}
</script>
@endpush
