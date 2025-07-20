<?php
// app/Http/Controllers/Admin/RouterConfigController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MikrotikService; // Asumsi MikrotikService sudah ada
use App\Models\Setting; // Untuk menyimpan konfigurasi MikroTik

class RouterConfigController extends Controller
{
    protected $mikrotikService;

    public function __construct(MikrotikService $mikrotikService)
    {
        $this->mikrotikService = $mikrotikService;
    }

    public function showRouterConfiguration()
    {
        // Load MikroTik config from settings
        $mikrotikSettings = [
            'online' => [
                'host' => Setting::where('key', 'mikrotik_online_host')->value('value') ?? '',
                'port' => Setting::where('key', 'mikrotik_online_port')->value('value') ?? '8728',
                'username' => Setting::where('key', 'mikrotik_online_username')->value('value') ?? 'admin',
                'password' => Setting::where('key', 'mikrotik_online_password')->value('value') ?? '',
            ],
            'offline' => [
                'host' => Setting::where('key', 'mikrotik_offline_host')->value('value') ?? '192.168.1.1',
                'port' => Setting::where('key', 'mikrotik_offline_port')->value('value') ?? '8728',
                'username' => Setting::where('key', 'mikrotik_offline_username')->value('value') ?? 'admin',
                'password' => Setting::where('key', 'mikrotik_offline_password')->value('value') ?? '',
            ]
        ];

        // Simulate data from MikroTik (or fetch using $this->mikrotikService)
        $interfaces = [
            ['name' => 'ether1-gateway', 'mac' => 'D4:CA:6D:11:22:31', 'type' => 'Ethernet', 'ip' => '10.0.0.15/24', 'status' => 'running', 'rx' => '15.7 GB', 'tx' => '4.2 GB'],
            ['name' => 'ether2-master', 'mac' => 'D4:CA:6D:11:22:32', 'type' => 'Ethernet', 'ip' => '192.168.88.1/24', 'status' => 'running', 'rx' => '3.1 GB', 'tx' => '8.9 GB'],
            ['name' => 'ether3-slave', 'mac' => 'D4:CA:6D:11:22:33', 'type' => 'Ethernet', 'ip' => '-', 'status' => 'running', 'rx' => '1.2 GB', 'tx' => '500 MB'],
            ['name' => 'ether4-slave', 'mac' => 'D4:CA:6D:11:22:34', 'type' => 'Ethernet', 'ip' => '-', 'status' => 'disabled', 'rx' => '0 B', 'tx' => '0 B'],
            ['name' => 'ether5-slave', 'mac' => 'D4:CA:6D:11:22:35', 'type' => 'Ethernet', 'ip' => '-', 'status' => 'running', 'rx' => '800 MB', 'tx' => '250 MB']
        ];

        $profiles = [
            ['name' => 'Default', 'sessionTimeout' => '1h', 'idleTimeout' => '30m', 'sharedUsers' => 1, 'rateLimit' => '2M/1M', 'status' => 'active'],
            ['name' => 'Premium', 'sessionTimeout' => '4h', 'idleTimeout' => '1h', 'sharedUsers' => 2, 'rateLimit' => '10M/5M', 'status' => 'active'],
            ['name' => 'Guest', 'sessionTimeout' => '30m', 'idleTimeout' => '15m', 'sharedUsers' => 1, 'rateLimit' => '1M/512K', 'status' => 'inactive']
        ];

        return view('admin.router_configuration', compact('mikrotikSettings', 'interfaces', 'profiles'));
    }

    public function saveMikrotikConfig(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:online,offline',
            'host' => 'required|string',
            'port' => 'required|numeric',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $mode = $request->mode;
        Setting::updateOrCreate(['key' => "mikrotik_{$mode}_host"], ['value' => $request->host, 'type' => 'string']);
        Setting::updateOrCreate(['key' => "mikrotik_{$mode}_port"], ['value' => $request->port, 'type' => 'string']);
        Setting::updateOrCreate(['key' => "mikrotik_{$mode}_username"], ['value' => $request->username, 'type' => 'string']);
        Setting::updateOrCreate(['key' => "mikrotik_{$mode}_password"], ['value' => $request->password, 'type' => 'string']);

        return response()->json(['success' => true, 'message' => 'MikroTik configuration saved successfully!']);
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'host' => 'required|string',
            'port' => 'required|numeric',
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        // Simulate connection test
        // In a real app, you'd use $this->mikrotikService->connect()
        $success = true; // Simulate success
        if ($success) {
            return response()->json(['success' => true, 'message' => 'Connection to MikroTik successful!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Connection failed. Please check settings.'], 400);
        }
    }

    public function updateInterface(Request $request, $name)
    {
        $request->validate([
            'ip' => 'required|string',
            'status' => 'required|in:running,disabled',
        ]);

        // Simulate update
        // In a real app, you'd use MikrotikService to update the interface
        return response()->json(['success' => true, 'message' => "Interface {$name} updated successfully!"]);
    }

    public function deleteInterface($name)
    {
        // Simulate delete
        // In a real app, you'd use MikrotikService to delete the interface
        return response()->json(['success' => true, 'message' => "Interface {$name} deleted successfully!"]);
    }
}