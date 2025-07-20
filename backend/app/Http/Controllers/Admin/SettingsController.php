<?php
// app/Http/Controllers/Admin/SettingsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting; // Pastikan model Setting sudah ada

class SettingsController extends Controller
{
    public function showApplicationSettings()
    {
        // Settings are already shared globally via AppServiceProvider
        return view('admin.application_settings');
    }

    public function updateSettings(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            // Handle base64 encoded images
            if (str_ends_with($key, '_logo') || str_ends_with($key, '_background_image')) {
                if ($value === null) { // If value is null, it means remove the image
                    Setting::where('key', $key)->delete();
                    continue;
                }
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => gettype($value)] // Simple type detection
            );
        }

        // After updating, refresh the settings in session/global scope if needed
        // For simplicity, we'll just return success. AppServiceProvider will load fresh on next request.
        return response()->json(['success' => true, 'message' => 'Settings saved successfully!']);
    }
}