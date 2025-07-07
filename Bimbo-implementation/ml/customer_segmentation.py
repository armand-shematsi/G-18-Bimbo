import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score
import matplotlib.pyplot as plt
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

def create_sample_customer_data():
    """Create realistic sample customer data for segmentation"""
    np.random.seed(42)

    # Generate realistic customer data
    n_customers = 200

    # Customer IDs
    customer_ids = [f'CUST_{i:04d}' for i in range(1, n_customers + 1)]

    # Purchase frequency (times per month)
    purchase_frequency = np.random.poisson(8, n_customers) + np.random.randint(1, 5, n_customers)

    # Average spending per order (in Naira)
    avg_spending = np.random.normal(15000, 5000, n_customers)
    avg_spending = np.maximum(avg_spending, 5000)  # Minimum spending

    # Total spending
    total_spending = purchase_frequency * avg_spending

    # Customer lifetime (months)
    customer_lifetime = np.random.exponential(12, n_customers) + 3

    # Preferred bread types
    bread_types = ['White Bread', 'Brown Bread', 'Whole Grain', 'Sourdough', 'Multigrain']
    preferred_bread = np.random.choice(bread_types, n_customers, p=[0.3, 0.25, 0.2, 0.15, 0.1])

    # Locations
    locations = ['Kampala', 'Entebbe', 'Jinja', 'Mbarara', 'Fort Portal', 'Gulu', 'Arua']
    location = np.random.choice(locations, n_customers, p=[0.3, 0.2, 0.15, 0.15, 0.1, 0.05, 0.05])

    # Payment methods
    payment_methods = ['Cash', 'Mobile Money', 'Card', 'Bank Transfer']
    payment_method = np.random.choice(payment_methods, n_customers, p=[0.4, 0.35, 0.15, 0.1])

    # Customer satisfaction (1-5 scale)
    satisfaction = np.random.normal(4.2, 0.8, n_customers)
    satisfaction = np.clip(satisfaction, 1, 5)

    # Create DataFrame
    data = {
        'customer_id': customer_ids,
        'purchase_frequency': purchase_frequency,
        'avg_spending': avg_spending,
        'total_spending': total_spending,
        'customer_lifetime': customer_lifetime,
        'preferred_bread': preferred_bread,
        'location': location,
        'payment_method': payment_method,
        'satisfaction': satisfaction
    }

    df = pd.DataFrame(data)
    return df

def preprocess_data(df):
    """Preprocess data for clustering"""
    # Create features for clustering
    features = df.copy()

    # Encode categorical variables
    le_bread = LabelEncoder()
    le_location = LabelEncoder()
    le_payment = LabelEncoder()

    features['bread_encoded'] = le_bread.fit_transform(features['preferred_bread'])
    features['location_encoded'] = le_location.fit_transform(features['location'])
    features['payment_encoded'] = le_payment.fit_transform(features['payment_method'])

    # Create additional features
    features['spending_per_month'] = features['total_spending'] / features['customer_lifetime']
    features['value_score'] = features['purchase_frequency'] * features['avg_spending'] * features['satisfaction']

    # Select features for clustering
    clustering_features = [
        'purchase_frequency',
        'avg_spending',
        'spending_per_month',
        'customer_lifetime',
        'satisfaction',
        'value_score',
        'bread_encoded',
        'location_encoded',
        'payment_encoded'
    ]

    X = features[clustering_features]

    # Scale features
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    return X_scaled, features, scaler, le_bread, le_location, le_payment

def find_optimal_clusters(X, max_clusters=10):
    """Find optimal number of clusters using silhouette score"""
    silhouette_scores = []
    K_range = range(2, max_clusters + 1)

    for k in K_range:
        kmeans = KMeans(n_clusters=k, random_state=42, n_init=10)
        cluster_labels = kmeans.fit_predict(X)
        silhouette_avg = silhouette_score(X, cluster_labels)
        silhouette_scores.append(silhouette_avg)

    optimal_k = K_range[np.argmax(silhouette_scores)]
    return optimal_k, silhouette_scores

def perform_segmentation(X, n_clusters=4):
    """Perform customer segmentation"""
    kmeans = KMeans(n_clusters=n_clusters, random_state=42, n_init=10)
    cluster_labels = kmeans.fit_predict(X)

    return kmeans, cluster_labels

def analyze_segments(df, cluster_labels):
    """Analyze customer segments"""
    df['segment'] = cluster_labels

    # Segment analysis
    segment_analysis = df.groupby('segment').agg({
        'purchase_frequency': ['mean', 'std', 'count'],
        'avg_spending': ['mean', 'std'],
        'total_spending': ['mean', 'sum'],
        'customer_lifetime': ['mean', 'std'],
        'satisfaction': ['mean', 'std'],
        'spending_per_month': ['mean', 'std'],
        'value_score': ['mean', 'std']
    }).round(2)

    # Segment characteristics
    segment_chars = df.groupby('segment').agg({
        'preferred_bread': lambda x: x.mode().iloc[0] if len(x.mode()) > 0 else x.iloc[0],
        'location': lambda x: x.mode().iloc[0] if len(x.mode()) > 0 else x.iloc[0],
        'payment_method': lambda x: x.mode().iloc[0] if len(x.mode()) > 0 else x.iloc[0]
    })

    return segment_analysis, segment_chars

def generate_recommendations(segment_analysis, segment_chars):
    """Generate business recommendations for each segment"""
    recommendations = []

    for segment in segment_analysis.index:
        avg_spending = segment_analysis.loc[segment, ('avg_spending', 'mean')]
        purchase_freq = segment_analysis.loc[segment, ('purchase_frequency', 'mean')]
        satisfaction = segment_analysis.loc[segment, ('satisfaction', 'mean')]
        value_score = segment_analysis.loc[segment, ('value_score', 'mean')]
        customer_count = segment_analysis.loc[segment, ('purchase_frequency', 'count')]

        preferred_bread = segment_chars.loc[segment, 'preferred_bread']
        location = segment_chars.loc[segment, 'location']
        payment_method = segment_chars.loc[segment, 'payment_method']

        # Generate recommendations based on segment characteristics
        if value_score > 1000000 and purchase_freq > 10:
            rec_type = "Premium"
            recommendations.append({
                'segment': segment,
                'type': rec_type,
                'description': f"High-value customers ({customer_count} customers)",
                'characteristics': f"Avg spending: ₦{avg_spending:,.0f}, Frequency: {purchase_freq:.1f}/month",
                'preferences': f"Prefers {preferred_bread} in {location}",
                'recommendations': [
                    "Exclusive premium products and early access",
                    f"Loyalty rewards program for {preferred_bread}",
                    "Personalized VIP service",
                    "Referral bonuses and exclusive events"
                ]
            })
        elif avg_spending > 12000 and purchase_freq > 6:
            rec_type = "Regular"
            recommendations.append({
                'segment': segment,
                'type': rec_type,
                'description': f"Regular customers ({customer_count} customers)",
                'characteristics': f"Avg spending: ₦{avg_spending:,.0f}, Frequency: {purchase_freq:.1f}/month",
                'preferences': f"Prefers {preferred_bread} in {location}",
                'recommendations': [
                    "Bundle offers and volume discounts",
                    f"Personalized recommendations for {preferred_bread}",
                    "Mobile app promotions",
                    "Seasonal campaigns and special offers"
                ]
            })
        elif purchase_freq > 3:
            rec_type = "Growing"
            recommendations.append({
                'segment': segment,
                'type': rec_type,
                'description': f"Growing customers ({customer_count} customers)",
                'characteristics': f"Avg spending: ₦{avg_spending:,.0f}, Frequency: {purchase_freq:.1f}/month",
                'preferences': f"Prefers {preferred_bread} in {location}",
                'recommendations': [
                    "Educational content about bread varieties",
                    "Trial offers for new products",
                    "Social media engagement campaigns",
                    "Community events and tastings"
                ]
            })
        else:
            rec_type = "Occasional"
            recommendations.append({
                'segment': segment,
                'type': rec_type,
                'description': f"Occasional customers ({customer_count} customers)",
                'characteristics': f"Avg spending: ₦{avg_spending:,.0f}, Frequency: {purchase_freq:.1f}/month",
                'preferences': f"Prefers {preferred_bread} in {location}",
                'recommendations': [
                    "Win-back campaigns with special offers",
                    "Re-engagement emails and SMS",
                    "Survey to understand preferences",
                    "Introduction to new product lines"
                ]
            })

    return recommendations

def save_results(df, segment_analysis, segment_chars, recommendations):
    """Save segmentation results"""
    # Save detailed customer data with segments
    df.to_csv('ml/customer_segments_detailed.csv', index=False)

    # Save segment analysis
    segment_analysis.to_csv('ml/segment_analysis.csv')

    # Save segment characteristics
    segment_chars.to_csv('ml/segment_characteristics.csv')

    # Save recommendations
    rec_df = pd.DataFrame(recommendations)
    rec_df.to_csv('ml/segment_recommendations.csv', index=False)

    # Save summary for dashboard
    summary_data = []
    for rec in recommendations:
        summary_data.append({
            'segment': rec['segment'],
            'type': rec['type'],
            'customer_count': rec['description'].split('(')[1].split(' ')[0],
            'avg_spending': rec['characteristics'].split('₦')[1].split(',')[0],
            'purchase_frequency': rec['characteristics'].split('Frequency: ')[1].split('/')[0],
            'preferred_bread': rec['preferences'].split('Prefers ')[1].split(' in')[0],
            'location': rec['preferences'].split(' in ')[1],
            'recommendations': '; '.join(rec['recommendations'])
        })

    summary_df = pd.DataFrame(summary_data)
    summary_df.to_csv('ml/customer_segments_summary.csv', index=False)

    print("✅ Segmentation results saved to ML folder!")

def main():
    """Main function to run customer segmentation"""
    print("🤖 Starting Customer Segmentation Analysis...")

    # Create sample data (replace with your actual data loading)
    print("📊 Creating sample customer data...")
    df = create_sample_customer_data()

    # Preprocess data
    print("🔧 Preprocessing data...")
    X_scaled, features, scaler, le_bread, le_location, le_payment = preprocess_data(df)

    # Find optimal number of clusters
    print("🔍 Finding optimal number of clusters...")
    optimal_k, scores = find_optimal_clusters(X_scaled, max_clusters=8)
    print(f"   Optimal clusters: {optimal_k}")

    # Perform segmentation
    print("🎯 Performing customer segmentation...")
    kmeans, cluster_labels = perform_segmentation(X_scaled, n_clusters=optimal_k)

    # Analyze segments
    print("📈 Analyzing customer segments...")
    segment_analysis, segment_chars = analyze_segments(features, cluster_labels)

    # Generate recommendations
    print("💡 Generating business recommendations...")
    recommendations = generate_recommendations(segment_analysis, segment_chars)

    # Save results
    print("💾 Saving results...")
    save_results(features, segment_analysis, segment_chars, recommendations)

    # Print summary
    print("\n📋 SEGMENTATION SUMMARY:")
    print("=" * 50)
    for rec in recommendations:
        print(f"\n🎯 {rec['type']} Customers (Segment {rec['segment']})")
        print(f"   {rec['description']}")
        print(f"   {rec['characteristics']}")
        print(f"   {rec['preferences']}")
        print("   Recommendations:")
        for i, rec_text in enumerate(rec['recommendations'], 1):
            print(f"   {i}. {rec_text}")

    print(f"\n✅ Customer segmentation completed successfully!")
    print(f"📁 Results saved in 'ml/' folder")
    print(f"🎯 {len(recommendations)} customer segments identified")
    print(f"👥 Total customers analyzed: {len(df)}")

if __name__ == "__main__":
    main()
