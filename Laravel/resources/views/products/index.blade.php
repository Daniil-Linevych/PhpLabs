<!DOCTYPE html>
<html>
<head>
    <title>Products List</title>
</head>
<body>
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-success">Add New Product</a>
    
    <table>
        <thead>
        <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td>${{ number_format($product['price'], 2) }}</td>
                <td>{{ $product['description'] }}</td>
                <td class="action-buttons">
                    <a href="{{ route('products.show', $product['id']) }}" class="btn btn-primary">View</a>
                    <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-success">Edit</a>
                    <form action="{{ route('products.delete', $product['id']) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>