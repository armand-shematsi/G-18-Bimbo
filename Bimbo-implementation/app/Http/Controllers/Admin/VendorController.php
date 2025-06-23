<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Show the vendor registration form.
     */
    public function register()
    {
        return view('vendor.register');
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'business_type' => ['required', 'string', 'in:bakery,supplier,distributor'],
            'tax_id' => ['required', 'string', 'max:50'],
            'business_license' => ['required', 'string', 'max:50'],
            'terms' => ['required', 'accepted'],
            'sales' => ['required', 'numeric', 'min:0'],
            'annual_revenue' => ['required', 'numeric', 'min:0'],
            'years_in_business' => ['required', 'integer', 'min:0'],
            'regulatory_certification' => ['required', 'string', 'max:255'],
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'business_type' => $request->business_type,
            'tax_id' => $request->tax_id,
            'business_license' => $request->business_license,
            'status' => 'pending', // New vendors start as pending
            'sales' => $request->sales,
            'annual_revenue' => $request->annual_revenue,
            'years_in_business' => $request->years_in_business,
            'regulatory_certification' => $request->regulatory_certification,
        ]);

        // Onboarding logic: create user if not exists
        $user = \App\Models\User::where('email', $vendor->email)->first();
        if (!$user) {
            \App\Models\User::create([
                'name' => $vendor->name,
                'email' => $vendor->email,
                'password' => bcrypt('changeme123'), // You may want to send a reset link instead
                'role' => 'pending',
            ]);
        }

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor registration submitted successfully. Your account will be reviewed by our team.');
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('orders');
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors,email,' . $vendor->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'business_type' => ['required', 'string', 'in:bakery,supplier,distributor'],
            'tax_id' => ['required', 'string', 'max:50'],
            'business_license' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,inactive,pending'],
        ]);

        $vendor->update($request->all());

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully');
    }
} 