import os
import pandas as pd

# List all CSV and Excel files in the current directory
files = [f for f in os.listdir('.') if f.endswith('.csv') or f.endswith('.xlsx')]

if not files:
    print('No CSV or Excel files found in the current directory.')
else:
    for file in files:
        print(f'\n===== Inspecting {file} =====')
        if file.endswith('.csv'):
            print('File type: CSV')
            df = pd.read_csv(file)
        elif file.endswith('.xlsx'):
            print('File type: Excel')
            df = pd.read_excel(file)
        else:
            print('Unsupported file type.')
            continue
        print('\nFirst 5 rows:')
        print(df.head())
        print('\nInfo:')
        print(df.info())
        print('\nSummary statistics:')
        print(df.describe(include="all"))
        print('\nMissing values per column:')
        print(df.isnull().sum())
