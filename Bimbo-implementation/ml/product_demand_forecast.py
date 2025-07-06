import pandas as pd
from prophet import Prophet
import os

# Load cleaned sales data
sales = pd.read_csv('large_sales_cleaned.csv', parse_dates=['Date'])

# Prepare output DataFrame
forecast_rows = []

# Get unique product types
product_types = sales['ProductType'].unique()

for product in product_types:
    # Filter data for this product
    df = sales[sales['ProductType'] == product].copy()
    # Aggregate by date (in case there are multiple locations or entries per day)
    daily = df.groupby('Date')['QuantitySold'].sum().reset_index()
    daily = daily.rename(columns={'Date': 'ds', 'QuantitySold': 'y'})
    # Prophet model
    m = Prophet()
    m.fit(daily)
    # Make future dataframe for next 30 days
    future = m.make_future_dataframe(periods=30)
    forecast = m.predict(future)
    # Only keep the forecasted period (not historical)
    forecast_future = forecast.tail(30)
    for _, row in forecast_future.iterrows():
        forecast_rows.append({
            'Date': row['ds'].date(),
            'ProductType': product,
            'PredictedQuantity': max(0, round(row['yhat']))
        })

# Save to CSV
forecast_df = pd.DataFrame(forecast_rows)
forecast_df.to_csv('product_demand_forecast.csv', index=False)

print('Forecast complete. Results saved to product_demand_forecast.csv')
