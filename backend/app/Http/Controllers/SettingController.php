<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Default settings to be used if no settings are found in the database
    private $defaultSettings = [
        'hotspot' => [
            'siteName' => 'MyHotspot-WiFi',
            'primaryColor' => '#3B82F6',
            'secondaryColor' => '#8B5CF6',
            'logo' => null,
            'backgroundImage' => null,
            'welcomeMessage' => 'Welcome to MyHotspot Free WiFi',
        ],
        'admin' => [
            'siteName' => 'MYHOTSPOT',
            'primaryColor' => '#1E3A8A',
            'secondaryColor' => '#475569',
            'logo' => null,
            'backgroundImage' => null,
            'welcomeMessage' => 'Administrator Panel',
        ],
        'apiKeys' => [
            'fonteApiKey' => '',
            'fonteDeviceId' => '',
            'googleClientId' => '',
            'googleClientSecret' => '',
            'googleRedirectUri' => 'https://yourdomain.com/auth/google/callback',
            'mikrotikHost' => '',
            'mikrotikPort' => '8728',
            'mikrotikUsername' => 'admin',
            'mikrotikPassword' => '',
        ],
    ];

    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        $response = [];
        foreach ($this->defaultSettings as $key => $defaultValue) {
            $response[$key] = $settings->has($key) ? $settings[$key]->value : $defaultValue;
        }

        return response()->json($response);
    }

    /**
     * Store a newly created or update existing settings in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hotspot' => 'required|array',
            'admin' => 'required|array',
            'apiKeys' => 'required|array',
            // Add more specific validation rules for each setting if needed
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings saved successfully!'], 200);
    }
}