@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome to MyHotspot management system</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $stats = [
                    ['title' => 'Total Users', 'value' => '1,234', 'change' => '+12% from yesterday', 'icon' => 'users', 'color' => 'bg-blue-500', 'textColor' => 'text-blue-600'],
                    ['title' => 'Active Sessions', 'value' => $activeUsers, 'change' => '+5% from yesterday', 'icon' => 'wifi', 'color' => 'bg-green-500', 'textColor' => 'text-green-600'],
                    ['title' => 'Data Usage', 'value' => '2.4 TB', 'change' => '+16% from yesterday', 'icon' => 'hard-drive', 'color' => 'bg-purple-500', 'textColor' => 'text-purple-600'],
                    ['title' => 'Revenue Today', 'value' => '$156', 'change' => '+8% from yesterday', 'icon' => 'dollar-sign', 'color' => 'bg-orange-500', 'textColor' => 'text-orange-600']
                ];
            @endphp
            @foreach($stats as $stat)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">{{ $stat['title'] }}</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</p>
                        </div>
                        <div class="p-3 rounded-full {{ $stat['color'] }}">
                            <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm {{ $stat['textColor'] }} flex items-center">
                            <i data-lucide="trending-up" class="w-4 h-4 mr-1"></i>
                            {{ $stat['change'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity</h3>
                <div class="h-64" id="user-activity-chart"></div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Sessions</h3>
                <div class="h-64" id="daily-sessions-chart"></div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($recentActivity as $activity)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-3 h-3 rounded-full {{ $activity['status'] === 'online' ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $activity['user'] }}</p>
                            <p class="text-sm text-gray-600">{{ $activity['action'] }}</p>
                        </div>
                        <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/recharts/umd/Recharts.min.js"></script>
    <script>
        // Data passed from Laravel Controller
        const userActivityData = @json($userActivityData);
        const dailySessionsData = @json($dailySessionsData);

        // Render User Activity Chart
        const userActivityChart = Recharts.render(
            Recharts.LineChart,
            document.getElementById('user-activity-chart'),
            {
                width: document.getElementById('user-activity-chart').offsetWidth,
                height: document.getElementById('user-activity-chart').offsetHeight,
                data: userActivityData,
                margin: { top: 5, right: 20, left: -10, bottom: 5 }
            },
            [
                Recharts.CartesianGrid({ strokeDasharray: "3 3", stroke: "#e0e0e0" }),
                Recharts.XAxis({ dataKey: "day", tick: { fill: '#6b7280', fontSize: 12 } }),
                Recharts.YAxis({ tick: { fill: '#6b7280', fontSize: 12 } }),
                Recharts.Tooltip({
                    contentStyle: {
                        backgroundColor: 'white',
                        border: '1px solid #e5e7eb',
                        borderRadius: '0.5rem',
                    }
                }),
                Recharts.Legend(),
                Recharts.Line({ type: "monotone", dataKey: "users", stroke: "#3b82f6", strokeWidth: 2, activeDot: { r: 6 }, name: "Users" }),
                Recharts.Line({ type: "monotone", dataKey: "sessions", stroke: "#10b981", strokeWidth: 2, activeDot: { r: 6 }, name: "Sessions" })
            ]
        );

        // Render Daily Sessions Chart
        const dailySessionsChart = Recharts.render(
            Recharts.BarChart,
            document.getElementById('daily-sessions-chart'),
            {
                width: document.getElementById('daily-sessions-chart').offsetWidth,
                height: document.getElementById('daily-sessions-chart').offsetHeight,
                data: dailySessionsData,
                margin: { top: 5, right: 20, left: -10, bottom: 5 }
            },
            [
                Recharts.CartesianGrid({ strokeDasharray: "3 3", stroke: "#e0e0e0" }),
                Recharts.XAxis({ dataKey: "day", tick: { fill: '#6b7280', fontSize: 12 } }),
                Recharts.YAxis({ tick: { fill: '#6b7280', fontSize: 12 } }),
                Recharts.Tooltip({
                    contentStyle: {
                        backgroundColor: 'white',
                        border: '1px solid #e5e7eb',
                        borderRadius: '0.5rem',
                    },
                    cursor: {fill: 'rgba(139, 92, 246, 0.1)'}
                }),
                Recharts.Bar({ dataKey: "sessions", fill: "#8b5cf6", name: "Sessions", radius: [4, 4, 0, 0] })
            ]
        );

        // Re-render charts on window resize
        window.addEventListener('resize', () => {
            userActivityChart.updateDimensions();
            dailySessionsChart.updateDimensions();
        });
    </script>
@endpush