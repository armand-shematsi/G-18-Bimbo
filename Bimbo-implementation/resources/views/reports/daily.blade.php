<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report - {{ $roleName }} - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #e74c3c;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .summary-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .summary-section h2 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 18px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }
        .summary-item h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .details-section {
            margin-bottom: 30px;
        }
        .details-section h2 {
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        .status-pending { color: #f39c12; }
        .status-processing { color: #3498db; }
        .status-completed { color: #27ae60; }
        .status-cancelled { color: #e74c3c; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Report</h1>
        <div class="subtitle">
            {{ $roleName }} Dashboard | {{ $date }} | Generated for {{ $user->name }}
        </div>
    </div>

    <div class="summary-section">
        <h2>📊 Daily Summary</h2>
        <div class="summary-grid">
            @foreach($reportData['summary'] ?? [] as $key => $value)
                <div class="summary-item">
                    <h3>{{ ucwords(str_replace('_', ' ', $key)) }}</h3>
                    <div class="value">
                        @if(str_contains($key, 'revenue') || str_contains($key, 'amount') || str_contains($key, 'value'))
                            ${{ number_format($value, 2) }}
                        @else
                            {{ number_format($value) }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if(isset($reportData['orders']))
    <div class="details-section">
        <h2>📋 Order Statistics</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Today</th>
                    <th>Yesterday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Orders</td>
                    <td>{{ $reportData['orders']['today'] ?? 0 }}</td>
                    <td>{{ $reportData['orders']['yesterday'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
        
        @if(isset($reportData['orders']['by_status']))
        <h3>Orders by Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['orders']['by_status'] as $status => $count)
                <tr>
                    <td><span class="status-{{ $status }}">{{ ucfirst($status) }}</span></td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    @if(isset($reportData['production']))
    <div class="details-section">
        <h2>🏭 Production Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['production'] as $key => $value)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($reportData['inventory']))
    <div class="details-section">
        <h2>📦 Inventory Status</h2>
        @if(($reportData['inventory']['low_stock_items'] ?? 0) > 0)
        <div class="alert">
            ⚠️ {{ $reportData['inventory']['low_stock_items'] }} items are running low on stock
        </div>
        @endif
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['inventory'] as $key => $value)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($reportData['financial']))
    <div class="details-section">
        <h2>💰 Financial Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['financial'] as $key => $value)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                    <td>${{ number_format($value, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($reportData['recent_orders']) && count($reportData['recent_orders']) > 0)
    <div class="details-section">
        <h2>🛒 Recent Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['recent_orders'] as $order)
                <tr>
                    <td>#{{ $order['order_id'] }}</td>
                    <td>{{ $order['customer'] }}</td>
                    <td>${{ number_format($order['total'], 2) }}</td>
                    <td><span class="status-{{ $order['status'] }}">{{ ucfirst($order['status']) }}</span></td>
                    <td>{{ $order['items'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>This report was automatically generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Bimbo Implementation System | All rights reserved</p>
    </div>
</body>
</html> 