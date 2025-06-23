<x-guest-layout>
    <form method="POST" action="{{ route('vendor.store') }}" class="space-y-6">
        @csrf

        <!-- Company Information -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
            
            <!-- Company Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Company Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Business Type -->
            <div class="mb-4">
                <x-input-label for="business_type" :value="__('Business Type')" />
                <select id="business_type" name="business_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select Business Type</option>
                    <option value="bakery" {{ old('business_type') == 'bakery' ? 'selected' : '' }}>Bakery</option>
                    <option value="supplier" {{ old('business_type') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    <option value="distributor" {{ old('business_type') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                </select>
                <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
            </div>
        </div>

        <!-- Contact Information -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
            
            <!-- Email -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>

        <!-- Address Information -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
            
            <!-- Street Address -->
            <div class="mb-4">
                <x-input-label for="address" :value="__('Street Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- City -->
            <div class="mb-4">
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <!-- State -->
            <div class="mb-4">
                <x-input-label for="state" :value="__('State')" />
                <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" required />
                <x-input-error :messages="$errors->get('state')" class="mt-2" />
            </div>

            <!-- ZIP Code -->
            <div class="mb-4">
                <x-input-label for="zip_code" :value="__('ZIP Code')" />
                <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" required />
                <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
            </div>
        </div>

        <!-- Business Details -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Details</h3>
            
            <!-- Tax ID -->
            <div class="mb-4">
                <x-input-label for="tax_id" :value="__('Tax ID / EIN')" />
                <x-text-input id="tax_id" class="block mt-1 w-full" type="text" name="tax_id" :value="old('tax_id')" required />
                <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
            </div>

            <!-- Business License -->
            <div class="mb-4">
                <x-input-label for="business_license" :value="__('Business License Number')" />
                <x-text-input id="business_license" class="block mt-1 w-full" type="text" name="business_license" :value="old('business_license')" required />
                <x-input-error :messages="$errors->get('business_license')" class="mt-2" />
            </div>

            <!-- Sales -->
            <div class="mb-4">
                <x-input-label for="sales" :value="__('Sales (USD)')" />
                <x-text-input id="sales" class="block mt-1 w-full" type="number" name="sales" :value="old('sales')" min="0" step="0.01" required />
                <x-input-error :messages="$errors->get('sales')" class="mt-2" />
            </div>

            <!-- Annual Revenue -->
            <div class="mb-4">
                <x-input-label for="annual_revenue" :value="__('Annual Revenue (USD)')" />
                <x-text-input id="annual_revenue" class="block mt-1 w-full" type="number" name="annual_revenue" :value="old('annual_revenue')" min="0" step="0.01" required />
                <x-input-error :messages="$errors->get('annual_revenue')" class="mt-2" />
            </div>

            <!-- Years in Business -->
            <div class="mb-4">
                <x-input-label for="years_in_business" :value="__('Years in Business')" />
                <x-text-input id="years_in_business" class="block mt-1 w-full" type="number" name="years_in_business" :value="old('years_in_business')" min="0" step="1" required />
                <x-input-error :messages="$errors->get('years_in_business')" class="mt-2" />
            </div>

            <!-- Regulatory Certification -->
            <div class="mb-4">
                <x-input-label for="regulatory_certification" :value="__('Regulatory Certification')" />
                <x-text-input id="regulatory_certification" class="block mt-1 w-full" type="text" name="regulatory_certification" :value="old('regulatory_certification')" required />
                <x-input-error :messages="$errors->get('regulatory_certification')" class="mt-2" />
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-center mt-4">
            <input id="terms" type="checkbox" name="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" required>
            <label for="terms" class="ml-2 block text-sm text-gray-900">
                I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-900">Terms and Conditions</a>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register as Vendor') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> 