import pandas as pd
import random

# Load forecast file
forecast = pd.read_csv('ml/product_demand_forecast.csv')

# Generate random actual quantities around the predicted values
actuals = []
for _, row in forecast.iterrows():
    actual_quantity = max(0, int(row['PredictedQuantity'] + random.randint(-20, 20)))
    actuals.append({
        'Date': row['Date'],
        'ProductType': row['ProductType'],
        'ActualQuantity': actual_quantity
    })

actual_df = pd.DataFrame(actuals)
actual_df.to_csv('ml/actual_demand.csv', index=False)
print('Generated ml/actual_demand.csv template.')
