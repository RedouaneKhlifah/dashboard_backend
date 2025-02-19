<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->reference }} - Professional Summary</title>
    <style>
        /* Modern Professional Style */
        :root {
            --primary: #2A5C82;
            --secondary: #5A8F7B;
            --accent: #E74C3C;
            --text: #2C3E50;
            --light-bg: #F8FAFC;
            --border: #E0E7ED;
        }

        body {
            margin: 0;
            padding: 40px;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: var(--text);
            background: linear-gradient(135deg, #f8f9ff 0%, #f1f5f9 100%);
        }

        .document-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .header {
            background: var(--primary);
            padding: 32px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .branding {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            height: 48px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .order-meta {
            text-align: right;
        }

        .order-id {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .order-date {
            opacity: 0.9;
            font-size: 14px;
        }

        .content-section {
            padding: 40px;
        }


        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin: 32px 0;
        }

        .detail-card {
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: transform 0.2s ease;
        }

        .detail-card:hover {
            transform: translateY(-2px);
        }

        .detail-title {
            font-size: 15px;
            color: #64748B;
            margin-bottom: 12px;
        }

        .detail-content {
            font-size: 18px;
            font-weight: 500;
            color: var(--text);
        }

        .urgent-notice {
            background: #FFF5F5;
            border: 1px solid #FECACA;
            border-left: 4px solid var(--accent);
            padding: 24px;
            border-radius: 8px;
            margin: 32px 0;
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .notice-icon {
            font-size: 24px;
            color: var(--accent);
        }

        .notice-text {
            font-size: 15px;
            line-height: 1.6;
        }

        .footer {
            padding: 24px 40px;
            background: var(--light-bg);
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 14px;
            color: #64748B;
        }
    </style>
</head>
<body>
    <div class="document-container">
        <header class="header">
            <div class="branding">
                <img src="https://example.com/logo.png" alt="Company Logo" class="logo">
                <div class="company-name">Global Enterprises</div>
            </div>
            <div class="order-meta">
                <div class="order-id">Order #{{ $order->reference }}</div>
                <div class="order-date">Created: {{ $order->created_at->format('M d, Y') }}</div>
            </div>
        </header>

        <div class="content-section">
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="detail-title">Order Date</div>
                    <div class="detail-content">{{ $order->created_at->format('F j, Y') }}</div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-title">Expiration Date</div>
                    <div class="detail-content" style="color: var(--accent);">
                        {{ $order->created_at->addDays(30)->format('F j, Y') }}
                    </div>
                </div>
            </div>

            <div class="urgent-notice">
                <div class="notice-icon">⚠️</div>
                <div class="notice-text">
                    <strong>Important Notice:</strong> Please complete all required actions before 
                    {{ $order->created_at->addDays(30)->format('F j, Y') }}. Late submissions will 
                    result in automatic order cancellation. Contact our support team for any assistance.
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>Thank you for choosing Global Enterprises | support@globalent.com | +1 (800) 555-0123</p>
            <p>© 2024 Global Enterprises. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>