<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order {{ $order->reference }}</title>
    <style>
    
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            color: #333333;
            background-color: #f5f5f5;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .header {
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .logo {
            max-height: 50px;
            margin-bottom: 15px;
        }

        .order-title {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 600;
        }


        .summary-grid {
            display: grid;
            gap: 12px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 25px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .summary-item.highlight {
            border-left: 4px solid #e74c3c;
            background: #fff5f5;
        }

        .summary-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .summary-value {
            color: #212529;
            font-size: 15px;
            font-weight: 600;
        }

        .expiration-date {
            color: #e74c3c;
            font-weight: 700;
        }


        .important-notice {
            background-color: #fffbe6;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ffe58f;
            margin-bottom: 25px;
        }

        .notice-title {
            color: #faad14;
            margin: 0 0 12px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .notice-text {
            color: #666666;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .footer {
            border-top: 2px solid #eeeeee;
            padding-top: 20px;
            text-align: center;
        }

        .support-contact {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .copyright {
            color: #7f8c8d;
            font-size: 12px;
            margin: 0;
        }

        .support-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <header class="header">
            <img src="https://example.com/logo.png" alt="Company Logo" class="logo">
            <h1 class="order-title">Order Confirmation</h1>
            <p class="summary-label">Reference: {{ $order->reference }}</p>
        </header>

        <section class="order-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Order Reference</span>
                    <span class="summary-value">{{ $order->reference }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Order Date</span>
                    <span class="summary-value">{{ $order->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="summary-item highlight">
                    <span class="summary-label">Expiration Date</span>
                    <span class="summary-value expiration-date">{{ $order->created_at->addDays(30)->format('Y-m-d') }}</span>
                </div>
            </div>
        </section>

        <div class="important-notice">
            <h4 class="notice-title">⚠️ Important Notice</h4>
            <p class="notice-text">
                Please complete your order before the expiration date. After {{ $order->created_at->addDays(30)->format('Y-m-d') }}, 
                this order will be automatically canceled. Late submissions cannot be processed.
            </p>
        </div>

        <footer class="footer">
            <p class="support-contact">
                Need assistance? Contact our support team at 
                <a href="mailto:support@example.com" class="support-link">support@example.com</a>
            </p>
            <p class="copyright">
                © 2025 Your Company Name. All rights reserved.
            </p>
        </footer>
    </div>
</body>
</html>