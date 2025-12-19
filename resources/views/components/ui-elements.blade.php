<!-- Toast Notification System -->
<style>
    /* === Toast Notification System === */
    .toast-notification-overlay {
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        min-width: 300px;
        text-align: center;
        pointer-events: none;
    }

    .toast-notification {
        margin-bottom: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        animation: slideInDown 0.3s ease-out;
        pointer-events: auto;
    }

    .toast-notification.success {
        background-color: #2C8F0C;
        color: white;
    }

    .toast-notification.error {
        background-color: #dc3545;
        color: white;
    }

    .toast-notification.warning {
        background-color: #ffc107;
        color: #212529;
    }

    .toast-notification.info {
        background-color: #17a2b8;
        color: white;
    }

    .toast-notification .toast-body {
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .toast-notification .toast-body i {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideOutUp {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(-100%);
            opacity: 0;
        }
    }
</style>

<!-- Toast Notification Container -->
<div id="toastContainer" class="toast-notification-overlay"></div>

<!-- Custom Confirmation Dialog -->
<style>
    .custom-confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(2px);
    }

    .custom-confirm-dialog {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(44, 143, 12, 0.3);
        max-width: 400px;
        width: 90%;
        transform: scale(0.9);
        animation: confirmModalIn 0.3s ease-out forwards;
        overflow: hidden;
        border: 2px solid #E8F5E6;
    }

    @keyframes confirmModalIn {
        to {
            transform: scale(1);
        }
    }

    .custom-confirm-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }

    .custom-confirm-header::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .custom-confirm-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .custom-confirm-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .custom-confirm-body {
        padding: 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8fdf8 0%, #f0f8f0 100%);
    }

    .custom-confirm-message {
        font-size: 1rem;
        color: #495057;
        line-height: 1.6;
        margin: 0;
        font-weight: 500;
    }

    .custom-confirm-buttons {
        display: flex;
        gap: 0.75rem;
        padding: 1.5rem 2rem 2rem;
        background: white;
        justify-content: center;
    }

    .custom-confirm-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .custom-confirm-btn-cancel {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #dee2e6;
    }

    .custom-confirm-btn-cancel:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: #495057;
    }

    .custom-confirm-btn-confirm {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: 2px solid #2C8F0C;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.3);
    }

    .custom-confirm-btn-confirm:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        border-color: #1E6A08;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(44, 143, 12, 0.4);
    }

    .custom-confirm-btn-confirm.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border-color: #dc3545;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .custom-confirm-btn-confirm.danger:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        border-color: #bd2130;
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .custom-confirm-dialog {
            width: 95%;
            margin: 1rem;
        }
        
        .custom-confirm-body {
            padding: 1.5rem;
        }
        
        .custom-confirm-buttons {
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem 1.5rem 1.5rem;
        }
        
        .custom-confirm-btn {
            width: 100%;
        }
    }
</style>

<!-- Custom Confirmation Dialog HTML -->
<div id="customConfirmDialog" class="custom-confirm-overlay" style="display: none;">
    <div class="custom-confirm-dialog">
        <div class="custom-confirm-header">
            <i class="fas fa-question-circle custom-confirm-icon"></i>
            <h3 class="custom-confirm-title">Confirm Action</h3>
        </div>
        <div class="custom-confirm-body">
            <p class="custom-confirm-message"></p>
        </div>
        <div class="custom-confirm-buttons">
            <button type="button" class="custom-confirm-btn custom-confirm-btn-cancel">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="custom-confirm-btn custom-confirm-btn-confirm">
                <i class="fas fa-check"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Toast notification function
    function showToast(message, type = 'success', duration = 3000) {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => {
            toast.style.animation = 'slideOutUp 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        });
        
        const bgColors = {
            'success': '#2C8F0C',
            'error': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        
        const icons = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        };
        
        const bgColor = bgColors[type] || bgColors.success;
        const icon = icons[type] || icons.success;
        const textColor = type === 'warning' ? 'text-dark' : 'text-white';
        
        const toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.style.backgroundColor = bgColor;
        
        toast.innerHTML = `
            <div class="toast-body ${textColor} d-flex align-items-center">
                <i class="fas ${icon} me-2 fs-5"></i>
                <span class="fw-semibold">${message}</span>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOutUp 0.3s ease-in forwards';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, duration);
    }

    // Custom confirmation dialog function
    function showCustomConfirm(message, callback, options = {}) {
        const dialog = document.getElementById('customConfirmDialog');
        if (!dialog) return;
        
        const messageEl = dialog.querySelector('.custom-confirm-message');
        const titleEl = dialog.querySelector('.custom-confirm-title');
        const iconEl = dialog.querySelector('.custom-confirm-icon');
        const confirmBtn = dialog.querySelector('.custom-confirm-btn-confirm');
        const cancelBtn = dialog.querySelector('.custom-confirm-btn-cancel');
        
        // Set options
        const config = {
            title: options.title || 'Confirm Action',
            message: message,
            confirmText: options.confirmText || 'Confirm',
            cancelText: options.cancelText || 'Cancel',
            icon: options.icon || 'fa-question-circle',
            isDanger: options.isDanger || false
        };
        
        // Update dialog content
        titleEl.textContent = config.title;
        messageEl.textContent = config.message;
        iconEl.className = `fas ${config.icon} custom-confirm-icon`;
        confirmBtn.querySelector('span').textContent = config.confirmText;
        cancelBtn.querySelector('span').textContent = config.cancelText;
        
        // Update confirm button style
        if (config.isDanger) {
            confirmBtn.classList.add('danger');
        } else {
            confirmBtn.classList.remove('danger');
        }
        
        // Show dialog
        dialog.style.display = 'flex';
        
        // Handle confirm
        const confirmHandler = () => {
            dialog.style.display = 'none';
            callback(true);
            cleanup();
        };
        
        // Handle cancel
        const cancelHandler = () => {
            dialog.style.display = 'none';
            callback(false);
            cleanup();
        };
        
        // Handle backdrop click
        const backdropHandler = (e) => {
            if (e.target === dialog) {
                cancelHandler();
            }
        };
        
        // Handle escape key
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                cancelHandler();
            }
        };
        
        // Cleanup function
        const cleanup = () => {
            confirmBtn.removeEventListener('click', confirmHandler);
            cancelBtn.removeEventListener('click', cancelHandler);
            dialog.removeEventListener('click', backdropHandler);
            document.removeEventListener('keydown', escapeHandler);
        };
        
        // Add event listeners
        confirmBtn.addEventListener('click', confirmHandler);
        cancelBtn.addEventListener('click', cancelHandler);
        dialog.addEventListener('click', backdropHandler);
        document.addEventListener('keydown', escapeHandler);
    }

    // Enhanced logout function with confirmation
    function logoutWithConfirm(event) {
        event.preventDefault();
        
        showCustomConfirm(
            'Are you sure you want to logout? You will need to login again to access the admin panel.',
            (confirmed) => {
                if (confirmed) {
                    const logoutForm = document.getElementById('logout-form');
                    if (logoutForm) {
                        logoutForm.submit();
                    }
                }
            },
            {
                title: 'Logout Confirmation',
                confirmText: 'Logout',
                cancelText: 'Cancel',
                icon: 'fa-sign-out-alt',
                isDanger: false
            }
        );
    }
</script>
