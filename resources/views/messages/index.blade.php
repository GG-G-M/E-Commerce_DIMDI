@extends('layouts.app')

@section('title', 'Messages')

@section('content')
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
        }
        
        .conversation-item {
            transition: background-color 0.2s;
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
        
        .unread-badge {
            background-color: var(--primary-color);
            font-size: 0.8rem;
        }
        
        /* Main Chat Area */
        .chat-placeholder {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #6c757d;
        }
        
        .chat-placeholder i {
            margin-bottom: 1rem;
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
        
        .product-image {
            border-radius: var(--radius);
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar - Conversation List -->
            <div class="col-md-4 col-lg-3 sidebar">
                <div class="d-flex flex-column h-100">
                    <!-- Header -->
                    <div class="sidebar-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Messages</h4>
                            <span class="badge unread-badge" id="unread-badge">3</span>
                        </div>
                    </div>

                    <!-- Conversation List -->
                    <div class="flex-grow-1 overflow-auto">
                        <!-- Sample conversation items -->
                        <a href="#" class="conversation-item d-block text-decoration-none text-dark active">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <img src="https://via.placeholder.com/50" 
                                         alt="Product Name" 
                                         class="rounded product-image" width="50" height="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1">John Doe</h6>
                                        <small class="text-muted">10:30 AM</small>
                                    </div>
                                    <p class="mb-1 text-muted small">Hey, I'm interested in your product. Is it still available?</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Vintage Camera</small>
                                        <span class="badge bg-danger rounded-pill">2</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <a href="#" class="conversation-item d-block text-decoration-none text-dark">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <img src="https://via.placeholder.com/50" 
                                         alt="Product Name" 
                                         class="rounded product-image" width="50" height="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1">Sarah Johnson</h6>
                                        <small class="text-muted">Yesterday</small>
                                    </div>
                                    <p class="mb-1 text-muted small">Thanks for the quick response!</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Wooden Desk</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <a href="#" class="conversation-item d-block text-decoration-none text-dark">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <img src="https://via.placeholder.com/50" 
                                         alt="Product Name" 
                                         class="rounded product-image" width="50" height="50">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1">Mike Wilson</h6>
                                        <small class="text-muted">Oct 12</small>
                                    </div>
                                    <p class="mb-1 text-muted small">Can you send me more pictures?</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Gaming Laptop</small>
                                        <span class="badge bg-danger rounded-pill">1</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="col-md-8 col-lg-9">
                <div class="chat-placeholder">
                    <i class="fas fa-comments fa-4x mb-3"></i>
                    <h4>Select a conversation to start messaging</h4>
                    <p>Your messages will appear here</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>