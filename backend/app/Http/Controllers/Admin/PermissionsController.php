<?php
// app/Http/Controllers/Admin/PermissionsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin; // Pastikan model Admin sudah ada
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PermissionsController extends Controller
{
    public function showRolesAndPermissions()
    {
        $admins = Admin::all();
        $roles = [
            ['id' => 1, 'name' => 'Super Administrator', 'value' => 'super_admin', 'permissions' => ['*'], 'description' => 'Full system access', 'color' => 'bg-red-100 text-red-800'],
            ['id' => 2, 'name' => 'Administrator', 'value' => 'administrator', 'permissions' => ['users.view', 'users.create', 'users.edit', 'settings.view', 'settings.edit'], 'description' => 'Manage users and basic settings', 'color' => 'bg-blue-100 text-blue-800'],
            ['id' => 3, 'name' => 'Moderator', 'value' => 'moderator', 'permissions' => ['users.view', 'users.edit', 'reports.view'], 'description' => 'View and moderate users', 'color' => 'bg-green-100 text-green-800'],
            ['id' => 4, 'name' => 'Viewer', 'value' => 'viewer', 'permissions' => ['users.view', 'reports.view'], 'description' => 'Read-only access', 'color' => 'bg-gray-100 text-gray-800']
        ];

        // Update user count for each role
        foreach ($roles as &$role) {
            $role['users'] = Admin::where('role', $role['value'])->count();
        }

        return view('admin.roles_and_permissions', compact('admins', 'roles'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins,username',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:super_admin,administrator,moderator,viewer',
        ]);

        if ($request->role === 'super_admin' && Auth::guard('admin')->user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Only Super Administrator can create another Super Administrator.'], 403);
        }

        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        // Assign role using Spatie/laravel-permission
        $admin->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'Administrator added successfully!', 'admin' => $admin]);
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->role === 'super_admin' && Auth::guard('admin')->user()->role !== 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Administrator cannot be edited by non-super admin.'], 403);
        }
        if (Auth::guard('admin')->user()->id == $admin->id && $request->role !== 'super_admin' && $admin->role === 'super_admin') {
             return response()->json(['success' => false, 'message' => 'You cannot change your own Super Administrator role.'], 403);
        }

        $request->validate([
            'username' => 'required|string|unique:admins,username,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required|string|in:super_admin,administrator,moderator,viewer',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->only(['username', 'email', 'role']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        // Sync roles
        $admin->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'Administrator updated successfully!', 'admin' => $admin]);
    }

    public function destroyAdmin($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->role === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Administrator cannot be deleted.'], 403);
        }
        if (Auth::guard('admin')->user()->id == $admin->id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        $admin->delete();

        return response()->json(['success' => true, 'message' => 'Administrator deleted successfully.']);
    }

    // You can add methods for editing/deleting roles if they are dynamic
    // For now, roles are static as per React component
}