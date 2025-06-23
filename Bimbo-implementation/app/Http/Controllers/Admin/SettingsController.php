<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'setting_name' => 'required|string|max:255',
            'setting_value' => 'nullable|string',
        ]);
        Setting::updateOrCreate(
            ['key' => $request->setting_name],
            ['value' => $request->setting_value]
        );
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }
} 