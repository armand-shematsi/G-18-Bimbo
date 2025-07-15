import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score
import matplotlib.pyplot as plt
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

# Load the new dataset
df = pd.read_csv('customer_purchase_data.csv')

# Select features for clustering
features = ['total_orders', 'avg_order_value', 'bread_orders', 'cake_orders', 'pastry_orders']
X = df[features]

# Perform KMeans clustering
kmeans = KMeans(n_clusters=3, random_state=42)
df['segment'] = kmeans.fit_predict(X)

# Save the results
df.to_csv('customer_segments_labeled.csv', index=False)

print('Segmentation complete. Output saved to customer_segments_labeled.csv.')
