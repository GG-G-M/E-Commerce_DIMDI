@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cartItems->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center mb-4 pb-4 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product->name }}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                            <p class="text-muted mb-0">${{ $item->product->current_price }}</p>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2" id="cart-form-{{ $item->id }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Size Selection Dropdown -->
                                <div class="me-2">
                                    <label class="form-label small mb-1">Size:</label>
                                    <select name="selected_size" class="form-select form-select-sm auto-submit">
                                        @foreach($item->product->available_sizes as $size)
                                        <option value="{{ $size }}" {{ $item->selected_size == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Quantity Input -->
                                <div>
                                    <label class="form-label small mb-1">Qty:</label>
                                    <div class="quantity-control" style="width: 100px;">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                min="1" max="{{ $item->product->stock_quantity }}" 
                                                class="form-control text-center auto-submit" readonly
                                                style="border-left: 0; border-right: 0;">
                                            <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Loading Spinner (hidden by default) -->
                                <div class="mt-3 loading-spinner" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <strong>$<span id="item-total-{{ $item->id }}">{{ number_format($item->total_price, 2) }}</span></strong>
                        </div>
                        <div class="col-md-2">
                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$<span id="summary-subtotal">{{ number_format($subtotal, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>$<span id="summary-tax">{{ number_format($tax, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>$<span id="summary-shipping">{{ number_format($shipping, 2) }}</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>$<span id="summary-total">{{ number_format($total, 2) }}</span></strong>
                    </div>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary w-100 btn-lg">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
        <h3>Your cart is empty</h3>
        <p class="text-muted mb-4">Start shopping to add items to your cart</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Start Shopping</a>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Handle custom quantity buttons
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-btn')) {
            const button = e.target.closest('.quantity-btn');
            const action = button.getAttribute('data-action');
            const input = button.closest('.input-group').querySelector('input[name="quantity"]');
            const currentValue = parseInt(input.value);
            const min = parseInt(input.getAttribute('min'));
            const max = parseInt(input.getAttribute('max'));
            
            let newValue = currentValue;
            
            if (action === 'increase' && currentValue < max) {
                newValue = currentValue + 1;
            } else if (action === 'decrease' && currentValue > min) {
                newValue = currentValue - 1;
            }
            
            if (newValue !== currentValue) {
                input.value = newValue;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit functionality for quantity and size changes
    const autoSubmitElements = document.querySelectorAll('.auto-submit');
    
    autoSubmitElements.forEach(element => {
        element.addEventListener('change', function() {
            const form = this.closest('form');
            submitForm(form);
        });
        
        // For quantity input, also trigger on input (for real-time updates)
        if (element.type === 'number') {
            let timeout;
            element.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const form = this.closest('form');
                    submitForm(form);
                }, 500); // Delay to avoid too many requests
            });
        }
    });
    
    function submitForm(form) {
        const formData = new FormData(form);
        const itemId = form.id.split('-')[2]; // Extract item ID from form ID
        const loadingSpinner = form.querySelector('.loading-spinner');
        
        // Show loading spinner
        if (loadingSpinner) {
            loadingSpinner.style.display = 'block';
        }
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update individual item total
                const itemTotalElement = document.getElementById(`item-total-${itemId}`);
                if (itemTotalElement && data.item_total) {
                    itemTotalElement.textContent = data.item_total;
                }
                
                // Update summary totals
                updateSummaryTotals(data.summary);
                
                // Show success message
                showFlashMessage('Cart updated successfully!', 'success');
            } else {
                throw new Error(data.message || 'Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('Error updating cart. Please try again.', 'error');
            // Reload the page to sync with server state
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        })
        .finally(() => {
            // Hide loading spinner
            if (loadingSpinner) {
                loadingSpinner.style.display = 'none';
            }
        });
    }
    
    function updateSummaryTotals(summary) {
        if (summary.subtotal) {
            document.getElementById('summary-subtotal').textContent = summary.subtotal;
        }
        if (summary.tax) {
            document.getElementById('summary-tax').textContent = summary.tax;
        }
        if (summary.shipping) {
            document.getElementById('summary-shipping').textContent = summary.shipping;
        }
        if (summary.total) {
            document.getElementById('summary-total').textContent = summary.total;
        }
    }
    
    function showFlashMessage(message, type) {
        // Remove existing flash messages
        const existingMessages = document.querySelectorAll('.flash-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Create new flash message
        const flashMessage = document.createElement('div');
        flashMessage.className = `alert alert-${type === 'error' ? 'danger' : 'success'} flash-message position-fixed`;
        flashMessage.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        `;
        flashMessage.textContent = message;
        
        document.body.appendChild(flashMessage);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            flashMessage.remove();
        }, 3000);
    }
});
</script>

<style>
.auto-submit:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.loading-spinner {
    margin-left: 10px;
}

.flash-message {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Improve number input styling */
input[type="number"] {
    -moz-appearance: textfield;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endpush
@endsection