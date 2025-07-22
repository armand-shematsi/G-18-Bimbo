from flask import Flask, request, jsonify
import pandas as pd
from prophet import Prophet

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict_post():
    data = request.json
    sales_data = data.get('sales', [])
    forecast_days = int(data.get('forecast_days', 0))
    predictions = []
    forecast = {}
    df = pd.DataFrame(sales_data)
    if df.empty:
        return jsonify({'predictions': [], 'forecast': {}})
    # Normalize product names to lowercase
    df['product_name'] = df['product_name'].str.lower()
    for product, group in df.groupby('product_name'):
        ts = group.groupby('date')['quantity'].sum().reset_index()
        ts = ts.rename(columns={'date': 'ds', 'quantity': 'y'})
        ts['ds'] = pd.to_datetime(ts['ds'])
        if len(ts) < 2:
            # Not enough data, return zeros or empty forecast
            forecast[product] = [
                {'date': (pd.Timestamp.now() + pd.Timedelta(days=i)).strftime('%Y-%m-%d'), 'predicted': 0}
                for i in range(1, forecast_days + 1)
            ] if forecast_days > 0 else []
            predictions.append({
                'item_name': product,
                'predicted': 0
            })
            continue
        try:
            m = Prophet()
            m.fit(ts)
            # Predict next day's sales
            future = pd.DataFrame({'ds': [ts['ds'].max() + pd.Timedelta(days=1)]})
            forecast_df = m.predict(future)
            predicted = max(0, round(forecast_df.iloc[0]['yhat']))
            # Predict next N days if requested
            if forecast_days > 0:
                future_dates = [ts['ds'].max() + pd.Timedelta(days=i) for i in range(1, forecast_days+1)]
                future_multi = pd.DataFrame({'ds': future_dates})
                forecast_multi = m.predict(future_multi)
                forecast[product] = [
                    {'date': row.ds.strftime('%Y-%m-%d'), 'predicted': max(0, round(row.yhat))}
                    for row in forecast_multi.itertuples()
                ]
        except Exception as e:
            predicted = 0
            if forecast_days > 0:
                forecast[product] = []
        predictions.append({
            'item_name': product,
            'predicted': predicted if len(ts) >= 2 else 0
        })
    print('DEBUG FORECAST:', forecast)  # Debug print for troubleshooting
    return jsonify({'predictions': predictions, 'forecast': forecast})

@app.route('/predict', methods=['GET'])
def predict_get():
    return jsonify({
        'message': 'This endpoint only supports POST requests with sales data. Please use POST with a JSON body.'
    }), 200

if __name__ == '__main__':
    app.run(port=5000)
