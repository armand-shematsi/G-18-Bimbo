<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Alert - {{ $date }}</title>
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
        .alert-banner {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .alert-banner h2 {
            margin: 0 0 10px 0;
            color: #e74c3c;
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
        .critical { background-color: #ffe6e6; }
        .low { background-color: #fff3cd; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .priority-high {
            color: #e74c3c;
            font-weight: bold;
        }
        .priority-medium {
            color: #f39c12;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Inventory Alert</h1>
        <div class="subtitle">
            Low Stock Notification | {{ $date }} | Generated for {{ $user->name }}
        </div>
    </div>

    <div class="alert-banner">
        <h2>⚠️ URGENT: Low Stock Items Detected</h2>
        <p>This is an automated alert for items that are running low on stock and require immediate attention.</p>
    </div>

    <div class="summary-section">
        <h2>📊 Alert Summary</h2>
        <div class="summary-grid">
            <div class="summary-item">
                <h3>Total Low Stock Items</h3>
                <div class="value">{{ $lowStockItems->count() }}</div>
            </div>
            <div class="summary-item">
                <h3>Critical Items (≤5 units)</h3>
                <div class="value priority-high">{{ $lowStockItems->where('quantity', '<=', 5)->count() }}</div>
            </div>
            <div class="summary-item">
                <h3>Low Stock Items (6-10 units)</h3>
                <div class="value priority-medium">{{ $lowStockItems->where('quantity', '>', 5)->count() }}</div>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2>📦 Low Stock Items Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Current Stock</th>
                    <th>Reorder Level</th>
                    <th>Priority</th>
                    <th>Supplier</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockItems as $item)
                <tr class="{{ $item->quantity <= 5 ? 'critical' : 'low' }}">
                    <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                    <td class="{{ $item->quantity <= 5 ? 'priority-high' : 'priority-medium' }}">
                        {{ $item->quantity }} units
                    </td>
                    <td>{{ $item->reorder_level ?? 10 }} units</td>
                    <td>
                        @if($item->quantity <= 5)
                            <span class="priority-high">CRITICAL</span>
                        @else
                            <span class="priority-medium">LOW</span>
                        @endif
                    </td>
                    <td>{{ $item->user->name ?? 'Unknown' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="details-section">
        <h2>🔧 Recommended Actions</h2>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <h3>Immediate Actions Required:</h3>
            <ul>
                <li><strong>Critical Items (≤5 units):</strong> Place urgent orders immediately</li>
                <li><strong>Low Stock Items (6-10 units):</strong> Schedule reorders within 24-48 hours</li>
                <li>Contact suppliers for expedited delivery if needed</li>
                <li>Review and adjust reorder levels based on demand patterns</li>
                <li>Consider alternative suppliers for critical items</li>
            </ul>
            
            <h3>Prevention Measures:</h3>
            <ul>
                <li>Implement automated reorder triggers</li>
                <li>Set up supplier notifications for low stock</li>
                <li>Review demand forecasting models</li>
                <li>Establish safety stock levels</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>This alert was automatically generated on {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Bimbo Implementation System | All rights reserved</p>
        <p><strong>Please take immediate action to prevent stockouts!</strong></p>
    </div>
</body>
</html> 