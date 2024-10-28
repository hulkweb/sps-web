<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h1>Order Invoice</h1>
    <p>Order ID: {{ $order->id }}</p>
    <p>Customer Name: {{ $order->code }}</p>
    <p>Total Amount: {{ $order->total_amount }}</p>
    <!-- Add more details as needed -->
</body>
</html>
