<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Report - {{ $roleName }} - {{ $period }}</title>
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
        .growth-positive { color: #27ae60; }
        .growth-negative { color: #e74c3c; }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .trend-chart {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .trend-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .trend-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Weekly Report</h1>
        <div class="subtitle">
            {{ $roleName }} Dashboard | {{ $period }} | Generated for {{ $user->name }}
        </div>
    </div>

    <div class="summary-section">
        <h2>📊 Weekly Summary</h2>
        <div class="summary-grid">
            @foreach($reportData['summary'] ?? [] as $key => $value)
                <div class="summary-item">
                    <h3>{{ ucwords(str_replace('_', ' ', $key)) }}</h3>
                    <div class="value">
                        @if(str_contains($key, 'revenue') || str_contains($key, 'amount') || str_contains($key, 'value'))
                            ${{ number_format($value, 2) }}
                        @elseif(str_contains($key, 'rate') || str_contains($key, 'efficiency'))
                            {{ number_format($value, 1) }}%
                        @else
                            {{ number_format($value) }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if(isset($reportData['trends']))
    <div class="details-section">
        <h2>📈 Weekly Trends</h2>
        @foreach($reportData['trends'] as $trendName => $trendData)
        <div class="trend-chart">
            <h3>{{ ucwords(str_replace('_', ' ', $trendName)) }}</h3>
            @foreach($trendData as $trend)
            <div class="trend-item">
                <span>{{ $trend['date'] }}</span>
                <span>
                    @if(str_contains($trendName, 'revenue'))
                        ${{ number_format($trend['value'], 2) }}
                    @else
                        {{ number_format($trend['value']) }}
                    @endif
                </span>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    @if(isset($reportData['top_products']))
    <div class="details-section">
        <h2>🏆 Top Performing Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['top_products'] as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->total_sold) }}</td>
                    <td>${{ number_format($product->total_revenue, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($reportData['growth_rate']))
    <div class="details-section">
        <h2>📊 Growth Analysis</h2>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Growth Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Overall Growth</td>
                    <td class="{{ $reportData['growth_rate'] >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        {{ $reportData['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($reportData['growth_rate'], 1) }}%
                    </td>
                </tr>
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