import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

class CustomerDataCleaner:
    def __init__(self, file_path=None):
        """Initialize the data cleaner"""
        self.file_path = file_path
        self.original_data = None
        self.cleaned_data = None
        self.cleaning_report = {}

    def load_data(self, file_path=None):
        """Load customer data from file"""
        if file_path:
            self.file_path = file_path

        try:
            if self.file_path.endswith('.csv'):
                self.original_data = pd.read_csv(self.file_path)
            elif self.file_path.endswith('.xlsx'):
                self.original_data = pd.read_excel(self.file_path)
            else:
                print("❌ Unsupported file format. Please use CSV or Excel files.")
                return False

            print(f"✅ Data loaded successfully from {self.file_path}")
            print(f"📊 Original data shape: {self.original_data.shape}")
            return True

        except Exception as e:
            print(f"❌ Error loading data: {str(e)}")
            return False

    def inspect_data(self):
        """Inspect the data for initial assessment"""
        print("\n🔍 DATA INSPECTION REPORT")
        print("=" * 50)

        # Basic info
        print(f"Dataset shape: {self.original_data.shape}")
        print(f"Columns: {list(self.original_data.columns)}")

        # Data types
        print("\n📋 Data Types:")
        print(self.original_data.dtypes)

        # Missing values
        print("\n❓ Missing Values:")
        missing_data = self.original_data.isnull().sum()
        missing_percent = (missing_data / len(self.original_data)) * 100
        missing_df = pd.DataFrame({
            'Missing Count': missing_data,
            'Missing Percentage': missing_percent
        })
        print(missing_df[missing_df['Missing Count'] > 0])

        # Duplicate rows
        duplicates = self.original_data.duplicated().sum()
        print(f"\n🔄 Duplicate rows: {duplicates}")

        # Unique values in each column
        print("\n🎯 Unique values per column:")
        for col in self.original_data.columns:
            unique_count = self.original_data[col].nunique()
            print(f"  {col}: {unique_count} unique values")

        # Store inspection results
        self.cleaning_report['inspection'] = {
            'shape': self.original_data.shape,
            'columns': list(self.original_data.columns),
            'missing_values': missing_df.to_dict(),
            'duplicates': duplicates,
            'data_types': self.original_data.dtypes.to_dict()
        }

    def handle_missing_values(self, strategy='auto'):
        """Handle missing values based on strategy"""
        print(f"\n🧹 Handling missing values (strategy: {strategy})")

        self.cleaned_data = self.original_data.copy()
        missing_handled = {}

        for column in self.cleaned_data.columns:
            missing_count = self.cleaned_data[column].isnull().sum()

            if missing_count > 0:
                print(f"  Processing {column}: {missing_count} missing values")

                if strategy == 'auto':
                    # Auto-detect strategy based on data type and missing percentage
                    missing_percent = (missing_count / len(self.cleaned_data)) * 100

                    if missing_percent > 50:
                        # If more than 50% missing, drop the column
                        self.cleaned_data = self.cleaned_data.drop(columns=[column])
                        missing_handled[column] = 'dropped_column'
                        print(f"    Dropped column {column} (>50% missing)")

                    elif self.cleaned_data[column].dtype in ['int64', 'float64']:
                        # Numeric columns: fill with median
                        median_val = self.cleaned_data[column].median()
                        self.cleaned_data[column].fillna(median_val, inplace=True)
                        missing_handled[column] = f'filled_with_median_{median_val}'
                        print(f"    Filled with median: {median_val}")

                    else:
                        # Categorical columns: fill with mode
                        mode_val = self.cleaned_data[column].mode().iloc[0] if len(self.cleaned_data[column].mode()) > 0 else 'Unknown'
                        self.cleaned_data[column].fillna(mode_val, inplace=True)
                        missing_handled[column] = f'filled_with_mode_{mode_val}'
                        print(f"    Filled with mode: {mode_val}")

                elif strategy == 'drop':
                    # Drop rows with missing values
                    self.cleaned_data = self.cleaned_data.dropna(subset=[column])
                    missing_handled[column] = 'dropped_rows'
                    print(f"    Dropped rows with missing values")

                elif strategy == 'fill':
                    # Fill with appropriate values
                    if self.cleaned_data[column].dtype in ['int64', 'float64']:
                        median_val = self.cleaned_data[column].median()
                        self.cleaned_data[column].fillna(median_val, inplace=True)
                        missing_handled[column] = f'filled_with_median_{median_val}'
                    else:
                        mode_val = self.cleaned_data[column].mode().iloc[0] if len(self.cleaned_data[column].mode()) > 0 else 'Unknown'
                        self.cleaned_data[column].fillna(mode_val, inplace=True)
                        missing_handled[column] = f'filled_with_mode_{mode_val}'

        self.cleaning_report['missing_values_handled'] = missing_handled
        print(f"✅ Missing values handled. New shape: {self.cleaned_data.shape}")

    def remove_duplicates(self):
        """Remove duplicate rows"""
        print("\n🔄 Removing duplicate rows...")

        initial_count = len(self.cleaned_data)
        self.cleaned_data = self.cleaned_data.drop_duplicates()
        final_count = len(self.cleaned_data)
        removed_count = initial_count - final_count

        print(f"  Removed {removed_count} duplicate rows")
        print(f"  New shape: {self.cleaned_data.shape}")

        self.cleaning_report['duplicates_removed'] = removed_count

    def handle_outliers(self, method='iqr', columns=None):
        """Handle outliers in numeric columns"""
        print(f"\n📊 Handling outliers (method: {method})")

        if columns is None:
            # Auto-detect numeric columns
            numeric_columns = self.cleaned_data.select_dtypes(include=[np.number]).columns
        else:
            numeric_columns = [col for col in columns if col in self.cleaned_data.columns]

        outliers_handled = {}

        for column in numeric_columns:
            if method == 'iqr':
                Q1 = self.cleaned_data[column].quantile(0.25)
                Q3 = self.cleaned_data[column].quantile(0.75)
                IQR = Q3 - Q1
                lower_bound = Q1 - 1.5 * IQR
                upper_bound = Q3 + 1.5 * IQR

                outliers = ((self.cleaned_data[column] < lower_bound) |
                           (self.cleaned_data[column] > upper_bound))
                outlier_count = outliers.sum()

                if outlier_count > 0:
                    print(f"  {column}: {outlier_count} outliers detected")
                    # Cap outliers instead of removing
                    self.cleaned_data[column] = self.cleaned_data[column].clip(lower_bound, upper_bound)
                    outliers_handled[column] = f'capped_{outlier_count}_outliers'
                    print(f"    Capped outliers to range [{lower_bound:.2f}, {upper_bound:.2f}]")

        self.cleaning_report['outliers_handled'] = outliers_handled
        print("✅ Outliers handled")

    def standardize_text_columns(self):
        """Standardize text columns (remove extra spaces, convert to title case, etc.)"""
        print("\n📝 Standardizing text columns...")

        text_columns = self.cleaned_data.select_dtypes(include=['object']).columns
        text_standardized = {}

        for column in text_columns:
            # Remove extra whitespace
            self.cleaned_data[column] = self.cleaned_data[column].astype(str).str.strip()

            # Convert to title case for names and locations
            if any(keyword in column.lower() for keyword in ['name', 'location', 'city', 'address', 'bread']):
                self.cleaned_data[column] = self.cleaned_data[column].str.title()

            # Handle common text issues
            self.cleaned_data[column] = self.cleaned_data[column].replace({
                'nan': 'Unknown',
                'None': 'Unknown',
                '': 'Unknown'
            })

            text_standardized[column] = 'standardized'

        self.cleaning_report['text_standardized'] = text_standardized
        print("✅ Text columns standardized")

    def validate_data_types(self):
        """Validate and convert data types"""
        print("\n🔧 Validating data types...")

        type_conversions = {}

        # Convert numeric columns that might be strings
        for column in self.cleaned_data.columns:
            if any(keyword in column.lower() for keyword in ['amount', 'price', 'spending', 'frequency', 'lifetime', 'satisfaction']):
                try:
                    self.cleaned_data[column] = pd.to_numeric(self.cleaned_data[column], errors='coerce')
                    type_conversions[column] = 'converted_to_numeric'
                except:
                    pass

        # Convert date columns
        for column in self.cleaned_data.columns:
            if any(keyword in column.lower() for keyword in ['date', 'created', 'updated', 'last']):
                try:
                    self.cleaned_data[column] = pd.to_datetime(self.cleaned_data[column], errors='coerce')
                    type_conversions[column] = 'converted_to_datetime'
                except:
                    pass

        self.cleaning_report['type_conversions'] = type_conversions
        print("✅ Data types validated")

    def generate_cleaning_report(self):
        """Generate a comprehensive cleaning report"""
        print("\n📋 CLEANING REPORT")
        print("=" * 50)

        print(f"Original data shape: {self.original_data.shape}")
        print(f"Cleaned data shape: {self.cleaned_data.shape}")
        print(f"Rows removed: {self.original_data.shape[0] - self.cleaned_data.shape[0]}")
        print(f"Columns removed: {self.original_data.shape[1] - self.cleaned_data.shape[1]}")

        print("\nCleaning actions performed:")
        for action, details in self.cleaning_report.items():
            print(f"  {action}: {details}")

        # Data quality metrics
        print("\n📊 Data Quality Metrics:")
        print(f"  Completeness: {(1 - self.cleaned_data.isnull().sum().sum() / (len(self.cleaned_data) * len(self.cleaned_data.columns))) * 100:.2f}%")
        print(f"  Uniqueness: {(1 - self.cleaned_data.duplicated().sum() / len(self.cleaned_data)) * 100:.2f}%")

        return self.cleaning_report

    def save_cleaned_data(self, output_path=None):
        """Save the cleaned data"""
        if output_path is None:
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            output_path = f"ml/cleaned_customer_data_{timestamp}.csv"

        self.cleaned_data.to_csv(output_path, index=False)
        print(f"✅ Cleaned data saved to: {output_path}")
        return output_path

    def clean_data(self, file_path=None, missing_strategy='auto', outlier_method='iqr'):
        """Complete data cleaning pipeline"""
        print("🧹 Starting Data Cleaning Pipeline...")

        # Load data
        if not self.load_data(file_path):
            return None

        # Inspect data
        self.inspect_data()

        # Handle missing values
        self.handle_missing_values(strategy=missing_strategy)

        # Remove duplicates
        self.remove_duplicates()

        # Handle outliers
        self.handle_outliers(method=outlier_method)

        # Standardize text
        self.standardize_text_columns()

        # Validate data types
        self.validate_data_types()

        # Generate report
        self.generate_cleaning_report()

        # Save cleaned data
        output_path = self.save_cleaned_data()

        print("\n🎉 Data cleaning completed successfully!")
        return self.cleaned_data

def main():
    """Main function to run data cleaning"""
    # Initialize cleaner
    cleaner = CustomerDataCleaner()

    # Try to load existing customer data files
    possible_files = [
        'ml/large_customers.csv',
        'ml/customer_segments_with_recommendations.csv',
        'ml/customer_segments_detailed.csv'
    ]

    file_to_clean = None
    for file_path in possible_files:
        try:
            test_df = pd.read_csv(file_path)
            print(f"Found existing data file: {file_path}")
            file_to_clean = file_path
            break
        except:
            continue

    if file_to_clean:
        print(f"Cleaning file: {file_to_clean}")
        cleaned_data = cleaner.clean_data(file_to_clean)
    else:
        print("No existing customer data files found. Creating sample data for demonstration...")
        # Create sample data for demonstration
        cleaner.original_data = cleaner.create_sample_data()
        cleaned_data = cleaner.clean_data()

    return cleaned_data

if __name__ == "__main__":
    main()
