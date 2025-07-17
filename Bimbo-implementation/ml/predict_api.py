from flask import Flask, request, jsonify
import pandas as pd
from prophet import Prophet
import os
from datetime import datetime, timedelta

app = Flask(__name__)

# Load and train Prophet models for each product at startup
sales = pd.read_csv('large_sales_cleaned.csv', parse_dates=['Date'])
product_types = sales['ProductType'].unique()
models = {}

for product in product_types:
    df = sales[sales['ProductType'] == product].copy()
    daily = df.groupby('Date')['QuantitySold'].sum().reset_index()
    daily = daily.rename(columns={'Date': 'ds', 'QuantitySold': 'y'})
    m = Prophet()
    m.fit(daily)
    models[product] = m

@app.route('/predict', methods=['GET'])
def predict():
    product = request.args.get('product')
    date = request.args.get('date')  # format: YYYY-MM-DD

    if product not in models:
        return jsonify({'error': 'Unknown product'}), 404

    m = models[product]
    future = pd.DataFrame({'ds': [pd.to_datetime(date)]})
    forecast = m.predict(future)
    predicted_quantity = max(0, round(forecast.iloc[0]['yhat']))

    return jsonify({
        'product': product,
        'date': date,
        'predicted_quantity': predicted_quantity
    })

@app.route('/predict/batch', methods=['GET'])
def predict_batch():
    product = request.args.get('product')
    start_date = request.args.get('start_date')  # format: YYYY-MM-DD
    days = int(request.args.get('days', 30))

    if product not in models:
        return jsonify({'error': 'Unknown product'}), 404

    try:
        start = datetime.strptime(start_date, '%Y-%m-%d')
    except Exception:
        return jsonify({'error': 'Invalid start_date format'}), 400

    m = models[product]
    dates = [start + timedelta(days=i) for i in range(days)]
    future = pd.DataFrame({'ds': dates})
    forecast = m.predict(future)
    results = []
    for i, row in enumerate(forecast.itertuples()):
        results.append({
            'date': row.ds.strftime('%Y-%m-%d'),
            'predicted_quantity': max(0, round(row.yhat))
        })
    return jsonify({
        'product': product,
        'predictions': results
    })

@app.route('/products', methods=['GET'])
def list_products():
    return jsonify({'products': list(models.keys())})

if __name__ == '__main__':
    app.run(debug=True)
