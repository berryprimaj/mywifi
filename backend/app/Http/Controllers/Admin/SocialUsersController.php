<?php
// app/Http/Controllers/Admin/SocialUsersController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialUser; // Pastikan model SocialUser sudah ada

class SocialUsersController extends Controller
{
    public function showSocialUsers()
    {
        // Simulate data from SocialUser model
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'ip' => '192.168.1.101',
                'whatsapp' => '+6281234567890',
                'provider' => 'Google',
                'connectedAt' => '2025-01-07 09:30:00',
                'session' => '2h 15m',
                'dataUsage' => '450MB',
                'status' => 'online'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@gmail.com',
                'ip' => '192.168.1.102',
                'whatsapp' => '+6281234567891',
                'provider' => 'Google',
                'connectedAt' => '2025-01-07 10:15:00',
                'session' => '1h 45m',
                'dataUsage' => '320MB',
                'status' => 'online'
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'ip' => '192.168.1.103',
                'whatsapp' => '+6281234567892',
                'provider' => 'WhatsApp',
                'connectedAt' => '2025-01-07 08:45:00',
                'session' => '3h 20m',
                'dataUsage' => '680MB',
                'status' => 'offline'
            ]
        ];

        // In a real application, you would fetch from the database:
        // $users = SocialUser::all();

        return view('admin.social_users', compact('users'));
    }

    // Anda bisa menambahkan method untuk API calls (edit, delete, send WhatsApp) di sini
    // Contoh:
    public function update(Request $request, $id)
    {
        // Logic to update social user
        $user = SocialUser::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }
        $user->update($request->all()); // Be careful with mass assignment
        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        // Logic to delete social user
        $user = SocialUser::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function sendWhatsApp(Request $request, $id)
    {
        // Logic to send WhatsApp message
        $user = SocialUser::find($id);
        if (!$user || !$user->phone) {
            return response()->json(['success' => false, 'message' => 'User or phone number not found.'], 404);
        }
        // Use FonteService here
        // $this->fonteService->sendMessage($user->phone, $request->message);
        return response()->json(['success' => true, 'message' => 'WhatsApp message sent.']);
    }
}