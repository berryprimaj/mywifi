<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        // Simulate data for the dashboard
        $stats = [
            [
                'title' => 'Total Users',
                'value' => '1,234',
                'change' => '+12% from yesterday',
                'icon' => 'users',
                'color' => 'bg-blue-500',
                'textColor' => 'text-blue-600'
            ],
            [
                'title' => 'Active Sessions',
                'value' => '89',
                'change' => '+5% from yesterday',
                'icon' => 'wifi',
                'color' => 'bg-green-500',
                'textColor' => 'text-green-600'
            ],
            [
                'title' => 'Data Usage',
                'value' => '2.4 TB',
                'change' => '+16% from yesterday',
                'icon' => 'hard-drive',
                'color' => 'bg-purple-500',
                'textColor' => 'text-purple-600'
            ],
            [
                'title' => 'Revenue Today',
                'value' => '$156',
                'change' => '+8% from yesterday',
                'icon' => 'dollar-sign',
                'color' => 'bg-orange-500',
                'textColor' => 'text-orange-600'
            ]
        ];

        $recentActivity = [
            ['user' => 'John Doe', 'action' => 'Connected via WhatsApp', 'time' => '2 minutes ago', 'status' => 'online'],
            ['user' => 'Jane Smith', 'action' => 'Logged in with Google', 'time' => '5 minutes ago', 'status' => 'online'],
            ['user' => 'Bob Johnson', 'action' => 'Disconnected', 'time' => '10 minutes ago', 'status' => 'offline'],
            ['user' => 'Alice Brown', 'action' => 'Member login', 'time' => '15 minutes ago', 'status' => 'online'],
        ];

        // Sample data for charts (Recharts is a React library, so we'll just pass data for potential future use or simple display)
        $userActivityData = [
            ['day' => 'Mon', 'users' => 65, 'sessions' => 45],
            ['day' => 'Tue', 'users' => 78, 'sessions' => 52],
            ['day' => 'Wed', 'users' => 90, 'sessions' => 61],
            ['day' => 'Thu', 'users' => 81, 'sessions' => 58],
            ['day' => 'Fri', 'users' => 95, 'sessions' => 65],
            ['day' => 'Sat', 'users' => 115, 'sessions' => 78],
            ['day' => 'Sun', 'users' => 88, 'sessions' => 62]
        ];

        $dailySessionsData = [
            ['day' => 'Mon', 'sessions' => 45],
            ['day' => 'Tue', 'sessions' => 52],
            ['day' => 'Wed', 'sessions' => 61],
            ['day' => 'Thu', 'sessions' => 58],
            ['day' => 'Fri', 'sessions' => 65],
            ['day' => 'Sat', 'sessions' => 78],
            ['day' => 'Sun', 'sessions' => 62]
        ];

        return view('admin.admin_dashboard', compact('stats', 'recentActivity', 'userActivityData', 'dailySessionsData'));
    }
}