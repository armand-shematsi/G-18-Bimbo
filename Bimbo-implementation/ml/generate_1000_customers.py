import pandas as pd
import numpy as np

np.random.seed(42)

locations = ['Kampala Central', 'Entebbe', 'Mukono', 'Jinja', 'Gulu']
bread_types = ['Whole Wheat', 'White Bread', 'Multigrain', 'Sourdough', 'Rye']
payment_methods = ['Mobile Money', 'Bank Transfer', 'Cash']

# Generate 1000 customers with the required columns
n = 1500
data = {
    'customer_id': [f'C{i+1:04d}' for i in range(n)],
    'purchase_frequency': np.random.randint(1, 7, size=n),
    'avg_spending': np.random.randint(40000, 150000, size=n),
    'total_spending': np.random.randint(100000, 1000000, size=n),
    'customer_lifetime': np.random.randint(1, 60, size=n),
    'preferred_bread': np.random.choice(bread_types, n),
    'location': np.random.choice(locations, n),
    'payment_method': np.random.choice(payment_methods, n),
    'satisfaction': np.round(np.random.uniform(1, 5, size=n), 1),
    'Loyalty_Score': np.round(np.random.uniform(1, 10, size=n), 1),
    'Feedback_Score': np.round(np.random.uniform(1, 5, size=n), 1),
}
df = pd.DataFrame(data)

# Save to the ml/ directory
import os
script_dir = os.path.dirname(__file__)
csv_path = os.path.join(script_dir, 'customer_purchase_data.csv')
df.to_csv(csv_path, index=False)
print(f"Generated {n} customers and saved to {csv_path}") 