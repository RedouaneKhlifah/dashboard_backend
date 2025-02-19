<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans("order.Order") }} {{ $order->reference }}</title>
    <style>

    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f7f9fc;
        margin: 0;
        padding: 15px;
    }

    .container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

 
    @media (max-width: 768px) {
        .container {
            padding: 25px;
        }
        
        h2 {
            font-size: 24px;
        }
        
        .title {
            flex-direction: column;
            align-items: flex-start;
            gap: 0;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .container {
            padding: 20px;
            border-radius: 8px;
        }
        
        .header {
            padding-bottom: 15px;
        }
        
        .brand-logo {
            max-width: 140px;
        }
        
        .detail-item {
            flex-direction: column;
            gap: 4px;
        }
        
        .summary-grid {
            padding: 12px;
        }
        
        .summary-item {
            padding: 12px;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .summary-label,
        .summary-value {
            font-size: 14px !important;
        }
        
        .footer p {
            font-size: 14px;
        }
    }


    .header {
        border-bottom: 2px solid #e4e9f2;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

        
        h2 {
            color: #2a325f;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .detail-value {
            color: #22d172;
            font-weight: 700;
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

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e4e9f2;
            text-align: center;
            color: #6c757d;
        }
        
        .brand-logo {
         max-width: 180px;
         width: 100%;
         height: auto;
         margin-bottom: 25px;
        }

        .title {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .span{
            color: #facc15;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="[Your-Logo-URL]" alt="Company Logo" class="brand-logo">
            <div class="title">
                <h2>{{ trans("order.Order") }}</h2>
                <h2 class="span">{{ $order->reference }}</h2>
            </div>
            <p>{{ trans("order.Thank") }}</p>
        </div>

        <section class="order-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">{{ trans("order.Order_Reference") }}</span>
                    <span class="summary-value">{{ $order->reference }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">{{ trans("order.Order_Date") }}</span>
                    <span class="summary-value"> {{ $order->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="summary-item highlight">
                    <span class="summary-label">{{ trans("order.Expiration_Date") }}</span>
                    <span class="summary-value expiration-date">{{ $order->created_at->addDays(30)->format('Y-m-d') }}</span>
                </div>
            </div>
        </section>

        <div class="footer">
            <p>{{ trans("order.Support_Message") }}</p>
            <p>© {{ date('Y') }} Your Company Name. {{ trans("order.Copyright") }}</p>
        </div>
    </div>
</body>
</html>