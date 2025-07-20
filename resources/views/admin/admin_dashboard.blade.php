@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome to MyHotspot management system</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

        {{-- Charts Section (Placeholder for Recharts, as it's React-specific) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity (Chart Placeholder)</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 text-gray-500 rounded-lg border border-dashed border-gray-300">
                    <p>Chart will be displayed here (requires a JS charting library)</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Sessions (Chart Placeholder)</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 text-gray-500 rounded-lg border border-dashed border-gray-300">
                    <p>Chart will be displayed here (requires a JS charting library)</p>
                </div>
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
<script>
    lucide.createIcons();
    // You can add JavaScript for dynamic charts here using a library like Chart.js or ApexCharts
    // For now, the chart areas are placeholders.
</script>
@endpush