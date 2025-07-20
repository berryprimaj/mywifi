@extends('admin.layout')

@section('title', 'Router Configuration')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Router Configuration</h1>
            <p class="text-gray-600">Configure and monitor your MikroTik Router</p>
        </div>

        {{-- MikroTik Connection Settings --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i data-lucide="router" class="w-5 h-5 mr-2 text-blue-600"></i>
                    MikroTik API Connection
                </h3>
                <div class="flex items-center bg-gray-200 rounded-lg p-1">
                    <button
                        type="button"
                        id="online-mode-button"
                        class="px-4 py-1.5 text-sm font-medium rounded-md flex items-center transition-colors bg-white text-blue-600 shadow"
                        data-mode="online"
                    >
                        <i data-lucide="globe" class="w-4 h-4 mr-2"></i>
                        Online (Hosting)
                    </button>
                    <button
                        type="button"
                        id="offline-mode-button"
                        class="px-4 py-1.5 text-sm font-medium rounded-md flex items-center transition-colors text-gray-600 hover:bg-gray-300"
                        data-mode="offline"
                    >
                        <i data-lucide="server" class="w-4 h-4 mr-2"></i>
                        Offline (Lokal)
                    </button>
                </div>
            </div>
            <form id="mikrotik-config-form">
                @csrf
                <input type="hidden" name="mode" id="mikrotik-config-mode" value="online">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" id="host-label">
                            Host (IP Publik / DDNS)
                        </label>
                        <input type="text" name="host" id="mikrotik-host" value="{{ $mikrotikSettings['online']['host'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g., 123.45.67.89" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">API Port</label>
                        <input type="text" name="port" id="mikrotik-port" value="{{ $mikrotikSettings['online']['port'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g., 8728" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="mikrotik-username" value="{{ $mikrotikSettings['online']['username'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="mikrotik-password" value="{{ $mikrotikSettings['online']['password'] }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                </div>
                <div class="flex justify-end items-center mt-6 space-x-4">
                    <button type="button" id="test-connection-button" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-100">Test Connection</button>
                    <button
                        type="submit"
                        id="save-config-button"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700"
                    >
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Save Connection Settings</span>
                    </button>
                </div>
            </form>
        </div>
        {{-- END: MikroTik Connection Settings --}}

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-base font-semibold text-blue-600 uppercase tracking-wider">Connection Status</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="font-semibold text-green-600">Connected</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="flex items-center justify-center space-x-4 p-4">
                    <i data-lucide="clock" class="w-10 h-10 text-blue-500"></i>
                    <div>
                        <p class="text-sm text-gray-500">Uptime</p>
                        <p class="text-xl font-bold text-gray-800">2d 14h 32m</p>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-4 p-4">
                    <i data-lucide="users" class="w-10 h-10 text-green-500"></i>
                    <div>
                        <p class="text-sm text-gray-500">Active Users</p>
                        <p class="text-xl font-bold text-gray-800">89</p>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-4 p-4">
                    <i data-lucide="arrow-left-right" class="w-10 h-10 text-purple-500"></i>
                    <div>
                        <p class="text-sm text-gray-500">Bandwidth Usage</p>
                        <p class="text-xl font-bold text-gray-800">67 Mbps / 100 Mbps</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                <h3 class="text-lg font-semibold text-gray-800">Router Status & Management</h3>
                <div class="flex space-x-2">
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><i data-lucide="database-backup" class="w-4 h-4"></i><span>Backup</span></button>
                    <button type="button" class="px-4 py-2 bg-orange-500 text-white rounded-lg flex items-center space-x-2 hover:bg-orange-600 text-sm"><i data-lucide="rotate-ccw" class="w-4 h-4"></i><span>Restart Hotspot</span></button>
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center space-x-2 hover:bg-red-700 text-sm"><i data-lucide="power" class="w-4 h-4"></i><span>Reboot</span></button>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-600">CPU Usage</span>
                        <span class="text-sm font-bold text-blue-600">23%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-blue-600 h-2 rounded-full" style="width: 23%;"></div></div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-600">Memory Usage</span>
                        <span class="text-sm font-bold text-green-600">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-green-600 h-2 rounded-full" style="width: 45%;"></div></div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-600">Temperature</span>
                        <span class="text-sm font-bold text-orange-600">42°C</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-orange-500 h-2 rounded-full" style="width: 42%;"></div></div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-600">Disk Usage</span>
                        <span class="text-sm font-bold text-purple-600">12%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-purple-600 h-2 rounded-full" style="width: 12%;"></div></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Network Interfaces</h3>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><i data-lucide="plus" class="w-4 h-4"></i><span>Add Interface</span></button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interface</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Traffic</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($interfaces as $iface)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $iface['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $iface['mac'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($iface['type'] === 'Wireless') <i data-lucide="wifi" class="w-4 h-4 text-gray-500"></i> @else <i data-lucide="network" class="w-4 h-4 text-gray-500"></i> @endif
                                        <span>{{ $iface['type'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $iface['ip'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-800">RX: {{ $iface['rx'] }}</div>
                                    <div class="text-xs text-gray-500">TX: {{ $iface['tx'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $iface['status'] === 'running' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $iface['status'] }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editInterface({{ json_encode($iface) }})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="Edit Interface"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                        <button onclick="deleteInterface('{{ $iface['name'] }}')" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete Interface"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Hotspot Profiles</h3>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><i data-lucide="plus" class="w-4 h-4"></i><span>Add Profile</span></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($profiles as $profile)
                    <div class="border rounded-lg p-4 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-800">{{ $profile['name'] }}</h4>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $profile['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $profile['status'] }}</span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 flex-grow">
                            <div class="flex justify-between"><span>Session Timeout:</span> <span class="font-medium text-gray-800">{{ $profile['sessionTimeout'] }}</span></div>
                            <div class="flex justify-between"><span>Idle Timeout:</span> <span class="font-medium text-gray-800">{{ $profile['idleTimeout'] }}</span></div>
                            <div class="flex justify-between"><span>Shared Users:</span> <span class="font-medium text-gray-800">{{ $profile['sharedUsers'] }}</span></div>
                            <div class="flex justify-between"><span>Rate Limit:</span> <span class="font-medium text-gray-800">{{ $profile['rateLimit'] }}</span></div>
                        </div>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">Edit</button>
                            <button type="button" class="px-3 py-1 border border-red-500 text-red-500 rounded-md text-sm hover:bg-red-50">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('components.modal', ['id' => 'editInterfaceModal', 'title' => 'Edit Interface'])
        <form id="edit-interface-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" id="edit-interface-name">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interface Name</label>
                    <input type="text" id="display-interface-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Address (CIDR)</label>
                    <input type="text" name="ip" id="edit-interface-ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="edit-interface-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                        <option value="running">Running</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6 mt-4 border-t">
                <button type="button" onclick="closeModal('editInterfaceModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'deleteInterfaceModal', 'title' => 'Confirm Deletion'])
        <div id="delete-interface-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" onclick="closeModal('deleteInterfaceModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="button" id="confirm-delete-interface-button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
        </div>
    @endinclude
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    const mikrotikSettings = @json($mikrotikSettings);
    let currentMode = 'online'; // Default mode

    const onlineModeButton = document.getElementById('online-mode-button');
    const offlineModeButton = document.getElementById('offline-mode-button');
    const mikrotikHostInput = document.getElementById('mikrotik-host');
    const mikrotikPortInput = document.getElementById('mikrotik-port');
    const mikrotikUsernameInput = document.getElementById('mikrotik-username');
    const mikrotikPasswordInput = document.getElementById('mikrotik-password');
    const hostLabel = document.getElementById('host-label');
    const mikrotikConfigForm = document.getElementById('mikrotik-config-form');
    const mikrotikConfigModeInput = document.getElementById('mikrotik-config-mode');
    const testConnectionButton = document.getElementById('test-connection-button');

    function updateMikrotikForm(mode) {
        currentMode = mode;
        mikrotikConfigModeInput.value = mode;

        const config = mikrotikSettings[mode];
        mikrotikHostInput.value = config.host;
        mikrotikPortInput.value = config.port;
        mikrotikUsernameInput.value = config.username;
        mikrotikPasswordInput.value = config.password; // Note: password might not be returned from backend for security

        if (mode === 'online') {
            onlineModeButton.classList.add('bg-white', 'text-blue-600', 'shadow');
            onlineModeButton.classList.remove('text-gray-600', 'hover:bg-gray-300');
            offlineModeButton.classList.remove('bg-white', 'text-blue-600', 'shadow');
            offlineModeButton.classList.add('text-gray-600', 'hover:bg-gray-300');
            hostLabel.textContent = 'Host (IP Publik / DDNS)';
            mikrotikHostInput.placeholder = 'e.g., 123.45.67.89';
        } else {
            offlineModeButton.classList.add('bg-white', 'text-blue-600', 'shadow');
            offlineModeButton.classList.remove('text-gray-600', 'hover:bg-gray-300');
            onlineModeButton.classList.remove('bg-white', 'text-blue-600', 'shadow');
            onlineModeButton.classList.add('text-gray-600', 'hover:bg-gray-300');
            hostLabel.textContent = 'Host (IP Lokal)';
            mikrotikHostInput.placeholder = 'e.g., 192.168.1.1';
        }
    }

    onlineModeButton.addEventListener('click', () => updateMikrotikForm('online'));
    offlineModeButton.addEventListener('click', () => updateMikrotikForm('offline'));

    // Initial load
    updateMikrotikForm(currentMode);

    mikrotikConfigForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.mode = currentMode; // Ensure correct mode is sent

        const toastId = showToast('info', 'Saving...', 'Saving MikroTik configuration...');
        try {
            const response = await fetch('{{ route('admin.router-config.save') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                Swal.update({ icon: 'success', title: 'Saved!', text: result.message, timer: 3000 });
            } else {
                Swal.update({ icon: 'error', title: 'Error', text: result.message, timer: 3000 });
            }
        } catch (error) {
            Swal.update({ icon: 'error', title: 'Error', text: 'Failed to save configuration.', timer: 3000 });
        }
    });

    testConnectionButton.addEventListener('click', async function() {
        const data = {
            host: mikrotikHostInput.value,
            port: mikrotikPortInput.value,
            username: mikrotikUsernameInput.value,
            password: mikrotikPasswordInput.value,
        };

        const toastId = showToast('info', 'Testing...', 'Testing connection to MikroTik...');
        try {
            const response = await fetch('{{ route('admin.router-config.test-connection') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                Swal.update({ icon: 'success', title: 'Connected!', text: result.message, timer: 3000 });
            } else {
                Swal.update({ icon: 'error', title: 'Failed', text: result.message, timer: 3000 });
            }
        } catch (error) {
            Swal.update({ icon: 'error', title: 'Error', text: 'An unexpected error occurred during connection test.', timer: 3000 });
        }
    });

    // Interface Management
    const interfacesData = @json($interfaces); // Pass interfaces data from PHP

    window.editInterface = function(iface) {
        document.getElementById('edit-interface-name').value = iface.name;
        document.getElementById('display-interface-name').value = iface.name;
        document.getElementById('edit-interface-ip').value = iface.ip;
        document.getElementById('edit-interface-status').value = iface.status;
        openModal('editInterfaceModal');
    };

    window.deleteInterface = function(ifaceName) {
        document.getElementById('delete-interface-details').innerHTML = `
            <p>Are you sure you want to delete the interface <strong>${ifaceName}</strong>? This action cannot be undone.</p>
        `;
        document.getElementById('confirm-delete-interface-button').onclick = async () => {
            try {
                const response = await fetch(`/admin/router-config/interfaces/${ifaceName}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast('success', 'Deleted!', result.message);
                    closeModal('deleteInterfaceModal');
                    location.reload(); // Simple reload for now
                } else {
                    showToast('error', 'Error', result.message);
                }
            } catch (error) {
                showToast('error', 'Error', 'Failed to delete interface.');
            }
        };
        openModal('deleteInterfaceModal');
    };

    document.getElementById('edit-interface-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const ifaceName = document.getElementById('edit-interface-name').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/admin/router-config/interfaces/${ifaceName}`, {
                method: 'POST', // Laravel uses POST for PUT/PATCH with _method field
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast('success', 'Saved!', result.message);
                closeModal('editInterfaceModal');
                location.reload(); // Simple reload for now
            } else {
                showToast('error', 'Error', result.message);
            }
        } catch (error) {
            showToast('error', 'Error', 'Failed to save changes.');
        }
    });
</script>
@endpush