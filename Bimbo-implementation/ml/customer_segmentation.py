import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
import warnings
import os
warnings.filterwarnings('ignore')

# Use absolute path for the CSV file
script_dir = os.path.dirname(__file__)
csv_path = os.path.join(script_dir, 'customer_purchase_data.csv')
df = pd.read_csv(csv_path)
# Rename columns to match what the importer and features expect
rename_map = {
    'customer_id': 'Customer_ID',
    'purchase_frequency': 'Purchase_Frequency',
    'avg_spending': 'Avg_Order_Value',
    'preferred_bread': 'Bread_Type',
    'location': 'Location'
}
df.rename(columns=rename_map, inplace=True)
# Select features for clustering
features = ['Purchase_Frequency', 'Avg_Order_Value', 'Loyalty_Score', 'Feedback_Score']
X = df[features]

# Standardize features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# Perform KMeans clustering
kmeans = KMeans(n_clusters=3, random_state=42)
df['segment'] = kmeans.fit_predict(X_scaled)

# Diagnostics: check for missing values
print('Rows before dropna:', len(df))
print('Missing values per column:')
print(df[features].isnull().sum())
# Fill NaNs with column means to avoid dropping rows
for col in features:
    if df[col].isnull().any():
        df[col].fillna(df[col].mean(), inplace=True)
print('Rows after fillna:', len(df))

# Save the results in a detailed format for the seeder
output_path = os.path.join(script_dir, 'customer_segments_detailed.csv')
df.to_csv(output_path, index=False)

print('Segmentation complete. Output saved to customer_segments_detailed.csv.')
