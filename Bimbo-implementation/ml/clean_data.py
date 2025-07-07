import pandas as pd
import numpy as np
from datetime import datetime

def clean_sales_data():
    """Clean the sales dataset and fix encoding issues"""

    try:
        # Try to read with different encodings
        try:
            df = pd.read_csv('large_sales.csv', encoding='utf-8')
        except UnicodeDecodeError:
            try:
                df = pd.read_csv('large_sales.csv', encoding='latin-1')
            except UnicodeDecodeError:
                df = pd.read_csv('large_sales.csv', encoding='cp1252')

        print(f"Original dataset shape: {df.shape}")
        print(f"Original columns: {df.columns.tolist()}")

        # Check for missing values
        print(f"\nMissing values:\n{df.isnull().sum()}")

        # Remove any rows with missing values
        df_clean = df.dropna()
        print(f"\nAfter removing missing values: {df_clean.shape}")

        # Convert Date column to datetime
        df_clean['Date'] = pd.to_datetime(df_clean['Date'], errors='coerce')
        df_clean = df_clean.dropna(subset=['Date'])
        print(f"After date conversion: {df_clean.shape}")

        # Ensure QuantitySold is numeric
        df_clean['QuantitySold'] = pd.to_numeric(df_clean['QuantitySold'], errors='coerce')
        df_clean = df_clean.dropna(subset=['QuantitySold'])
        print(f"After quantity conversion: {df_clean.shape}")

        # Remove negative quantities
        df_clean = df_clean[df_clean['QuantitySold'] > 0]
        print(f"After removing negative quantities: {df_clean.shape}")

        # Remove outliers (quantities > 3 standard deviations from mean)
        Q1 = df_clean['QuantitySold'].quantile(0.25)
        Q3 = df_clean['QuantitySold'].quantile(0.75)
        IQR = Q3 - Q1
        lower_bound = Q1 - 1.5 * IQR
        upper_bound = Q3 + 1.5 * IQR

        df_clean = df_clean[(df_clean['QuantitySold'] >= lower_bound) &
                           (df_clean['QuantitySold'] <= upper_bound)]
        print(f"After removing outliers: {df_clean.shape}")

        # Sort by date
        df_clean = df_clean.sort_values('Date')

        # Save cleaned data
        df_clean.to_csv('large_sales_cleaned.csv', index=False, encoding='utf-8')

        print(f"\nCleaned dataset saved to 'large_sales_cleaned.csv'")
        print(f"Final shape: {df_clean.shape}")
        print(f"Date range: {df_clean['Date'].min()} to {df_clean['Date'].max()}")
        print(f"Product types: {df_clean['ProductType'].unique()}")
        print(f"Locations: {df_clean['Location'].unique()}")
        print(f"Quantity range: {df_clean['QuantitySold'].min()} to {df_clean['QuantitySold'].max()}")

        return True

    except Exception as e:
        print(f"Error cleaning data: {e}")
        return False

if __name__ == "__main__":
    print("Starting data cleaning process...")
    success = clean_sales_data()
    if success:
        print("Data cleaning completed successfully!")
    else:
        print("Data cleaning failed!")
