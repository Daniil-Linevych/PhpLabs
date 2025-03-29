<!DOCTYPE html>
<html>
<head>
    <title>View Product</title>
</head>
<body>
    <div class="product-details">
        <h1>Product Details</h1>
        
        <div class="detail-row">
            <span class="label">ID:</span>
            <span>{{ $product['id'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Name:</span>
            <span>{{ $product['name'] }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Price:</span>
            <span>${{ number_format($product['price'], 2) }}</span>
        </div>
        
        <div class="detail-row">
            <span class="label">Description:</span>
            <span>{{ $product['description'] }}</span>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
</body>
</html>