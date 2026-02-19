<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;

class SettingController extends Controller
{
    public function index()
    {
        // Kinukuha ang lahat ng settings at ginagawang key-value pair para madaling gamitin sa Blade
        $settings = SystemSetting::pluck('setting_value', 'setting_key')->toArray();
        
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Kinukuha lahat ng input mula sa form maliban sa CSRF token
        $data = $request->except(['_token', '_method']);

        // I-save o I-update sa database
        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(
                ['setting_key' => $key], // Hahanapin ang key
                ['setting_value' => $value] // I-uupdate ang value
            );
        }

        return redirect()->back()->with('success', 'System settings updated successfully!');
    }
}