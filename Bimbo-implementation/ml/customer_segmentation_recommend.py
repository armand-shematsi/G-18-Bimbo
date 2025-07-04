import pandas as pd
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans

# Load data
df = pd.read_csv('large_customers.csv')

# Select features for clustering
X = df[['PurchaseFrequency', 'AvgSpending']]

# Standardize features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# KMeans clustering (choose 3 segments for illustration)
kmeans = KMeans(n_clusters=3, random_state=42)
df['Segment'] = kmeans.fit_predict(X_scaled)

# Analyze segments
segment_summary = df.groupby('Segment').agg({
    'PurchaseFrequency': ['mean', 'min', 'max'],
    'AvgSpending': ['mean', 'min', 'max'],
    'PreferredBreadType': lambda x: x.value_counts().index[0],
    'Location': lambda x: x.value_counts().index[0]
}).reset_index()

print("\nSegment Summary:")
print(segment_summary)

# Generate recommendations for each segment
recommendations = []
for idx, row in segment_summary.iterrows():
    bread = row[('PreferredBreadType', '<lambda>')]
    location = row[('Location', '<lambda>')]
    freq = row[('PurchaseFrequency', 'mean')]
    spend = row[('AvgSpending', 'mean')]
    if freq > df['PurchaseFrequency'].mean() and spend > df['AvgSpending'].mean():
        rec = f"Segment {idx}: High-value, frequent buyers in {location} who prefer {bread}. Recommend loyalty rewards and exclusive previews of new {bread} products."
    elif freq > df['PurchaseFrequency'].mean():
        rec = f"Segment {idx}: Frequent buyers in {location} who prefer {bread}. Recommend bundle offers and personalized discounts on {bread}."
    else:
        rec = f"Segment {idx}: Less frequent buyers in {location} who prefer {bread}. Recommend win-back campaigns and introductory offers on {bread}."
    recommendations.append(rec)

print("\nPersonalization Recommendations:")
for rec in recommendations:
    print(rec)

# Save segmented data
df.to_csv('customer_segments_with_recommendations.csv', index=False)
