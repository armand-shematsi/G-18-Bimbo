import pandas as pd
from sklearn.metrics import mean_absolute_error, mean_squared_error

# Load actual and predicted values
actual = pd.read_csv('ml/actual_demand.csv')  # Columns: Date, ProductType, ActualQuantity
predicted = pd.read_csv('ml/product_demand_forecast.csv')  # Columns: Date, ProductType, PredictedQuantity

# Merge on Date and ProductType to align rows
merged = pd.merge(actual, predicted, on=['Date', 'ProductType'])

# Calculate metrics
mae = mean_absolute_error(merged['ActualQuantity'], merged['PredictedQuantity'])
rmse = mean_squared_error(merged['ActualQuantity'], merged['PredictedQuantity'], squared=False)
mape = (abs((merged['ActualQuantity'] - merged['PredictedQuantity']) / merged['ActualQuantity'])).mean() * 100

print(f"MAE: {mae:.2f}")
print(f"RMSE: {rmse:.2f}")
print(f"MAPE: {mape:.2f}%")
