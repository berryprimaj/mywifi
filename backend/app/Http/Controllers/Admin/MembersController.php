<?php
// app/Http/Controllers/Admin/MembersController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member; // Pastikan model Member sudah ada
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
{
    public function showMembersManagement()
    {
        // Simulate data from Member model or fetch from DB
        $members = [
            [
                'id' => 1,
                'username' => 'employee001',
                'name' => 'John Smith',
                'email' => 'john.smith@company.com',
                'department' => 'IT',
                'password' => 'password123', // For simulation, in real app, don't expose
                'status' => 'active',
                'lastLogin' => '2025-01-07 14:30:00',
                'dataUsage' => '1.2 GB',
                'sessionTime' => '4h 32m'
            ],
            [
                'id' => 2,
                'username' => 'employee002',
                'name' => 'Jane Doe',
                'email' => 'jane.doe@company.com',
                'department' => 'HR',
                'password' => 'password123',
                'status' => 'active',
                'lastLogin' => '2025-01-07 13:45:00',
                'dataUsage' => '890 MB',
                'sessionTime' => '2h 15m'
            ],
            [
                'id' => 3,
                'username' => 'employee003',
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@company.com',
                'department' => 'Finance',
                'password' => 'password123',
                'status' => 'inactive',
                'lastLogin' => '2025-01-06 16:20:00',
                'dataUsage' => '2.4 GB',
                'sessionTime' => '0m'
            ]
        ];

        // In a real application, you would fetch from the database:
        // $members = Member::all();

        return view('admin.members_management', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:members,username',
            'name' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'department' => 'nullable|string',
            'password' => 'required|string',
        ]);

        // In a real app, you'd hash the password: Hash::make($request->password)
        // For simulation, we'll just store it as is to match React's local storage behavior
        $member = Member::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'password' => $request->password, // Storing plain for simulation
            'is_active' => true,
            'last_login_at' => null,
            'total_data_usage' => 0,
            'total_session_time' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Member added successfully.', 'member' => $member]);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'username' => 'required|string|unique:members,username,' . $member->id,
            'name' => 'required|string',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'department' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string', // Allow password to be updated
        ]);

        $data = $request->only(['username', 'name', 'email', 'department', 'status']);
        if ($request->filled('password')) {
            $data['password'] = $request->password; // Storing plain for simulation
        }
        $data['is_active'] = ($request->status === 'active');

        $member->update($data);

        return response()->json(['success' => true, 'message' => 'Member updated successfully.', 'member' => $member]);
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['success' => true, 'message' => 'Member deleted successfully.']);
    }
}