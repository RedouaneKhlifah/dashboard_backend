<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order {{ $order->reference }}</title>
</head>
<body>
    <h2>Order {{ $order->reference }}</h2>
    <p>Please find attached the details for order {{ $order->reference }}.</p>
    <p>Order Date: {{ $order->created_at->format('Y-m-d') }}</p>
    <p>Expiration Date: {{ $order->created_at->addDays(30)->format('Y-m-d') }}</p>
    <p>Thank you for using our service!</p>
</body>
</html>