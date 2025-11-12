<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2C8F0C;
            --primary-light: #e8f5e9;
            --primary-dark: #1f6c08;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            --radius: 8px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }
        
        .container-fluid {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background-color: var(--white);
            border-right: 1px solid #e9ecef;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background-color: var(--white);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .conversation-item {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }
        
        .conversation-item:hover {
            background-color: var(--primary-light);
        }
        
        .conversation-item.active {
            background-color: var(--primary-light);
            border-left: 4px solid var(--primary-color);
        }
        
        .product-img {
            border-radius: var(--radius);
            object-fit: cover;
        }
        
        /* Main Area Styles */
        .main-area {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #6c757d;
            background-color: var(--white);
        }
        
        .main-area i {
            margin-bottom: 1rem;
            color: var(--primary-color);
            opacity: 0.7;
        }
        
        .main-area h4 {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .main-area p {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Badge Styles */
        .unread-badge {
            background-color: var(--primary-color);
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .no-conversations {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #6c757d;
        }
        
        .no-conversations i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--primary-color);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 100;
                width: 100%;
                height: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3 sidebar">
                <div class="d-flex flex-column h-100">
                    <div class="sidebar-header">
                        <h4 class="mb-0">Customer Messages</h4>
                        <small class="text-muted">Manage customer inquiries</small>
                    </div>
                    
                    <div class="flex-grow-1 overflow-auto">
                        @if($conversations && $conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @if($conversation && $conversation->product && $conversation->other_user)
                                    <a href="{{ route('admin.messages.show', ['product' => $conversation->product->id, 'user' => $conversation->other_user->id]) }}" 
                                       class="conversation-item d-block text-decoration-none text-dark">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 position-relative">
                                                <img src="{{ $conversation->product->image_url ?? '/images/placeholder.jpg' }}" 
                                                     alt="{{ $conversation->product->name }}" 
                                                     class="rounded product-img" width="50" height="50">
                                                @if($conversation->unread_count > 0)
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1">{{ $conversation->other_user->name }}</h6>
                                                    <small class="text-muted">{{ $conversation->latest_message->formatted_time ?? 'Just now' }}</small>
                                                </div>
                                                <p class="mb-1 text-muted small">{{ Str::limit($conversation->latest_message->message ?? 'No messages', 40) }}</p>
                                                <small class="text-muted">{{ $conversation->product->name }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <div class="no-conversations">
                                <i class="fas fa-comments fa-3x mb-3"></i>
                                <p>No customer messages</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Area -->
            <div class="col-md-8 col-lg-9 p-0">
                <div class="main-area">
                    <i class="fas fa-headset fa-4x mb-3"></i>
                    <h4>Customer Support</h4>
                    <p>Select a conversation to assist customers</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add active class to current conversation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const conversationItems = document.querySelectorAll('.conversation-item');
            
            conversationItems.forEach(item => {
                if (item.href && currentPath.includes(item.href)) {
                    item.classList.add('active');
                }
            });
            
            // Auto-refresh conversations every 30 seconds
            setInterval(() => {
                // In a real application, you would make an AJAX request here
                console.log('Refreshing conversations...');
            }, 30000);
        });
    </script>
</body>
</html>