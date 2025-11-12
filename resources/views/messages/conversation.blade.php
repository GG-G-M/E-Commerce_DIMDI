@extends('layouts.app')

@section('title', "Chat with {$otherUser->name}")

@section('content')
<style>
        :root {
            --primary-color: #2C8F0C;
            --primary-light: #eef2ff;
            --secondary-color: #2C8F0C;
            --accent-color: #2C8F0C;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
            --gray-text: #6c757d;
            --light-gray: #e9ecef;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            --radius: 12px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-text);
        }
        
        .container-fluid {
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            background-color: var(--white);
            border-right: 1px solid var(--light-gray);
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            background-color: var(--white);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .conversation-item {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--light-gray);
            padding: 1rem 1.5rem;
        }
        
        .conversation-item:hover {
            background-color: var(--primary-light);
        }
        
        .conversation-item.active {
            background-color: var(--primary-light);
            border-left: 4px solid var(--primary-color);
        }
        
        .conversation-item .product-img {
            border-radius: var(--radius);
            object-fit: cover;
        }
        
        .unread-badge {
            background-color: var(--accent-color);
            font-size: 0.75rem;
        }
        
        /* Chat Area Styles */
        .chat-area {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            background-color: var(--white);
            box-shadow: var(--shadow);
            z-index: 5;
        }
        
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #f0f4f8;
            background-image: 
                radial-gradient(circle at 25px 25px, rgba(255,255,255,0.3) 2%, transparent 40%),
                radial-gradient(circle at 75px 75px, rgba(255,255,255,0.2) 2%, transparent 40%);
            background-size: 100px 100px;
        }
        
        .message-wrapper {
            margin-bottom: 1rem;
        }
        
        .date-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .date-divider span {
            background-color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--gray-text);
            box-shadow: var(--shadow);
        }
        
        .message-bubble {
            display: flex;
            margin-bottom: 0.75rem;
        }
        
        .own-message {
            justify-content: flex-end;
        }
        
        .other-message {
            justify-content: flex-start;
        }
        
        .message-content {
            max-width: 70%;
        }
        
        .bubble {
            padding: 0.75rem 1rem;
            border-radius: 18px;
            margin-bottom: 0.25rem;
            position: relative;
            word-wrap: break-word;
        }
        
        .own-message .bubble {
            background: var(--primary-color);
            color: var(--white);
            border-bottom-right-radius: 4px;
        }
        
        .other-message .bubble {
            background: var(--white);
            color: var(--dark-text);
            border-bottom-left-radius: 4px;
            box-shadow: var(--shadow);
        }
        
        .time-stamp {
            font-size: 0.75rem;
            padding: 0 0.5rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .message-input {
            padding: 1.25rem 1.5rem;
            background-color: var(--white);
            border-top: 1px solid var(--light-gray);
        }
        
        .message-input .form-control {
            border-radius: 24px;
            padding: 0.75rem 1.25rem;
            border: 1px solid var(--light-gray);
        }
        
        .message-input .btn {
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.75rem;
            background-color: var(--primary-color);
            border: none;
        }
        
        .message-input .btn:hover {
            background-color: var(--secondary-color);
        }
        
        .product-badge {
            background-color: var(--primary-light);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        .dropdown-menu {
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--light-gray);
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
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
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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
            
            .message-content {
                max-width: 85%;
            }
            
            .chat-header .btn {
                padding: 0.375rem 0.75rem;
            }
        }
        
        .no-conversations {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--gray-text);
        }
        
        .no-conversations i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Messages</h4>
                            <span class="badge unread-badge" id="unread-badge">{{ auth()->user()->getUnreadMessagesCount() }}</span>
                        </div>
                    </div>
                    
                    <div class="flex-grow-1 overflow-auto">
                        @if($conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                <a href="{{ route('messages.show', ['product' => $conversation->product->id, 'user' => $conversation->other_user->id]) }}" 
                                   class="conversation-item d-block text-decoration-none text-dark {{ request()->route('user') == $conversation->other_user->id && request()->route('product') == $conversation->product->id ? 'active' : '' }}">
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
                            @endforeach
                        @else
                            <div class="no-conversations">
                                <i class="fas fa-comments"></i>
                                <p>No conversations yet</p>
                                <small>Start a conversation by messaging a seller</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Chat -->
            <div class="col-md-8 col-lg-9 p-0">
                <div class="chat-area">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                    <img src="{{ $product->image_url ?? '/images/placeholder.jpg' }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded me-3" width="50" height="50">
                                </a>
                                <div>
                                    <h5 class="mb-0">{{ $otherUser->name }}</h5>
                                    <small class="text-muted">Product: {{ $product->name }}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('products.show', $product) }}">View Product</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#">Report User</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="messages-container" id="messages-container">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="product-badge">
                                Conversation about: <strong>{{ $product->name }}</strong>
                            </div>
                        </div>
                        
                        @foreach($messages->reverse() as $message)
                            <div class="message-wrapper" data-message-id="{{ $message->id }}">
                                @if($message->date_header != ($messages[$loop->index - 1]->date_header ?? null))
                                    <div class="date-divider">
                                        <span>{{ $message->date_header }}</span>
                                    </div>
                                @endif
                                
                                <div class="message-bubble {{ $message->sender_id == auth()->id() ? 'own-message' : 'other-message' }}">
                                    <div class="d-flex align-items-end {{ $message->sender_id == auth()->id() ? 'justify-content-end' : '' }}">
                                        @if($message->sender_id != auth()->id())
                                            <img src="/images/avatar.png" 
                                                 alt="{{ $message->sender->name }}" 
                                                 class="user-avatar me-2">
                                        @endif
                                        <div class="message-content">
                                            <div class="bubble {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border' }}">
                                                <p class="mb-0">{{ $message->message }}</p>
                                            </div>
                                            <small class="text-muted time-stamp">{{ $message->formatted_time }}</small>
                                        </div>
                                        @if($message->sender_id == auth()->id())
                                            <img src="/images/avatar.png" 
                                                 alt="{{ $message->sender->name }}" 
                                                 class="user-avatar ms-2">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="message-input">
                        <form id="message-form" action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-scroll to bottom of messages
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            
            // Form submission handling
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', function(e) {
                    const messageInput = this.querySelector('input[name="message"]');
                    if (messageInput.value.trim() === '') {
                        e.preventDefault();
                        messageInput.focus();
                    }
                });
            }
            
            // Mark messages as read when viewing conversation
            const currentConversation = document.querySelector('.conversation-item.active');
            if (currentConversation) {
                // In a real app, you would make an AJAX request here to mark messages as read
                console.log('Marking messages as read for current conversation');
            }
        });
    </script>
</body>
</html>