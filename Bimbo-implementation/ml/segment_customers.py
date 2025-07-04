import pandas as pd

# Load the dataset
file = 'large_customers.csv'
df = pd.read_csv(file)

# Show columns and first 10 rows for inspection
print('Columns:', df.columns.tolist())
print('\nFirst 10 rows:')
print(df.head(10))

# Show info about data types and missing values
print('\nInfo:')
print(df.info())

print('\nMissing values per column:')
print(df.isnull().sum())
