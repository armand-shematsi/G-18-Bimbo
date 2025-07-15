import pandas as pd
import numpy as np

# Set random seed for reproducibility
np.random.seed(42)

num_customers = 1000

data = {
    "customer_id": np.arange(1, num_customers + 1),
    "total_orders": np.random.poisson(lam=20, size=num_customers),  # Most customers order 20 times on average
    "avg_order_value": np.round(np.random.normal(loc=100, scale=30, size=num_customers), 2),  # Average order value
    "bread_orders": np.random.poisson(lam=10, size=num_customers),
    "cake_orders": np.random.poisson(lam=5, size=num_customers),
    "pastry_orders": np.random.poisson(lam=5, size=num_customers),
}

# Ensure no negative values
data["total_orders"] = np.clip(data["total_orders"], 1, None)
data["avg_order_value"] = np.clip(data["avg_order_value"], 10, None)
data["bread_orders"] = np.clip(data["bread_orders"], 0, None)
data["cake_orders"] = np.clip(data["cake_orders"], 0, None)
data["pastry_orders"] = np.clip(data["pastry_orders"], 0, None)

df = pd.DataFrame(data)
df.to_csv("customer_purchase_data.csv", index=False)

print("Generated customer_purchase_data.csv with 1000 rows.") 