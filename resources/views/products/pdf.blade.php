<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products Export</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; text-align: left; }
    </style>
<head>
<body>
    <h2>Products Export</h2>
    <p>Low stock threshold: <strong>20</strong>. Rows highlighted where stock &lt; 20.</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $product)
                @php $low = isset($product->stock_quantity) && $product->stock_quantity < 20; @endphp
                <tr style="background: {{ $low ? '#fff3cd' : 'transparent' }};">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
