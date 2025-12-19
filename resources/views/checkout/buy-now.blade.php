<h1>Checkout</h1>
<p>Product: {{ $checkoutItem['product_name'] }}</p>
<p>Size: {{ $checkoutItem['selected_size'] }}</p>
<p>Quantity: {{ $checkoutItem['quantity'] }}</p>
<p>Unit Price: ₱{{ number_format($checkoutItem['unit_price'], 2) }}</p>
<p>Subtotal: ₱{{ number_format($subtotal, 2) }}</p>
<p>Tax: ₱{{ number_format($tax, 2) }}</p>
<p>Shipping: ₱{{ number_format($shipping, 2) }}</p>
<h3>Total: ₱{{ number_format($total, 2) }}</h3>

<form action="{{ route('buy-now') }}" method="POST">
    @csrf
    <button type="submit">Confirm & Pay</button>
</form>
