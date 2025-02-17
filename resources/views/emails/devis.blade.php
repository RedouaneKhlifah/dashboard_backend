<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Devis {{ $devis->reference }}</title>
</head>
<body>
    <h2>Devis {{ $devis->reference }}</h2>
    <p>Please find attached the details for devis {{ $devis->reference }}.</p>
    <p>Devis Date: {{ $devis->created_at->format('Y-m-d') }}</p>
    <p>Expiration Date: {{ $devis->created_at->addDays(30)->format('Y-m-d') }}</p>
    <p>Thank you for using our service!</p>
</body>
</html>