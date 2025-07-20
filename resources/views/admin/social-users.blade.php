@extends('admin.layout')

@section('title', 'Social Users')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Social Users</h1>
                <p class="text-gray-600">Manage users connected via social media</p>
            </div>
            <button
                id="export-data-button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
            >
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Export Data</span>
            </button>
        </div>

        {{-- Statistics, Auto Delete, Filters --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <i data-lucide="calendar" class="w-5 h-5 text-orange-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Auto Delete Settings</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="date-range" class="block text-sm font-medium text-gray-700 mb-2">Select Date Range</label>
                    <select
                        id="date-range"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option>Custom Range</option>
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 3 months</option>
                    </select>
                </div>
                
                <div>
                    <label for="start-date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input
                        type="date"
                        id="start-date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label for="end-date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input
                        type="date"
                        id="end-date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div class="flex flex-col justify-end">
                    <div class="flex space-x-2">
                        <button
                            id="search-button"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                        >
                            <i data-lucide="search" class="w-4 h-4"></i>
                            <span>Search</span>
                        </button>
                        <button
                            id="delete-range-button"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        <input
                            type="text"
                            id="search-term"
                            placeholder="Search users by name, email, or WhatsApp number..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <i data-lucide="filter" class="w-4 h-4 text-gray-400"></i>
                    <select
                        id="selected-provider"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option>All Providers</option>
                        <option>Google</option>
                        <option>WhatsApp</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="social-users-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Connected At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50" data-user-id="{{ $user['id'] }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $user['email'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $user['ip'] }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i data-lucide="message-circle" class="w-4 h-4 text-green-600 mr-2"></i>
                                        <span class="text-sm text-gray-900">{{ $user['whatsapp'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user['provider'] === 'Google' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $user['provider'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user['connectedAt'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user['session'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user['dataUsage'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user['status'] === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewUser({{ json_encode($user) }})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="View User"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                        <button onclick="editUser({{ json_encode($user) }})" class="p-1.5 rounded-md text-green-600 hover:bg-green-100" title="Edit User"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                        <button onclick="sendWhatsApp({{ json_encode($user) }})" class="p-1.5 rounded-md text-orange-600 hover:bg-orange-100" title="Send WhatsApp"><i data-lucide="message-circle" class="w-4 h-4"></i></button>
                                        <button onclick="deleteUser({{ json_encode($user) }})" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete User"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.modal', ['id' => 'viewUserModal', 'title' => 'View User Details'])
        <div class="space-y-3 text-sm" id="view-user-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end pt-4">
            <button onclick="closeModal('viewUserModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
        </div>
    @endinclude

    @include('components.modal', ['id' => 'editUserModal', 'title' => 'Edit User'])
        <form id="edit-user-form">
            @csrf
            <input type="hidden" name="id" id="edit-user-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="edit-user-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-user-email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="tel" name="whatsapp" id="edit-user-whatsapp" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'whatsAppModal', 'title' => 'Send WhatsApp Message'])
        <form id="send-whatsapp-form">
            @csrf
            <input type="hidden" name="id" id="whatsapp-user-id">
            <p class="mb-4">To: <strong id="whatsapp-phone-display"></strong></p>
            <textarea
                name="message"
                id="whatsapp-message"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                placeholder="Type your message here..."
            ></textarea>
            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="closeModal('whatsAppModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Send Message</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'deleteUserModal', 'title' => 'Confirm Deletion'])
        <div id="delete-user-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" onclick="closeModal('deleteUserModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="button" id="confirm-delete-user-button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
        </div>
    @endinclude
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    const usersData = @json($users); // Pass users data from PHP to JS

    // Global functions for modals (defined in components/modal.blade.php)
    // function openModal(id) { ... }
    // function closeModal(id) { ... }

    function populateUserDetails(user) {
        return `
            <p><strong>Name:</strong> ${user.name}</p>
            <p><strong>Email:</strong> ${user.email}</p>
            <p><strong>WhatsApp:</strong> ${user.whatsapp}</p>
            <p><strong>IP Address:</strong> ${user.ip}</p>
            <p><strong>Provider:</strong> ${user.provider}</p>
            <p><strong>Connected At:</strong> ${user.connectedAt}</p>
            <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${user.status === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${user.status}</span></p>
        `;
    }

    window.viewUser = function(user) {
        document.getElementById('view-user-details').innerHTML = populateUserDetails(user);
        openModal('viewUserModal');
    };

    window.editUser = function(user) {
        document.getElementById('edit-user-id').value = user.id;
        document.getElementById('edit-user-name').value = user.name;
        document.getElementById('edit-user-email').value = user.email;
        document.getElementById('edit-user-whatsapp').value = user.whatsapp;
        openModal('editUserModal');
    };

    window.sendWhatsApp = function(user) {
        document.getElementById('whatsapp-user-id').value = user.id;
        document.getElementById('whatsapp-phone-display').textContent = user.whatsapp;
        document.getElementById('whatsapp-message').value = ''; // Clear previous message
        openModal('whatsAppModal');
    };

    window.deleteUser = function(user) {
        document.getElementById('delete-user-details').innerHTML = `
            <p>Are you sure you want to delete the user <strong>${user.name}</strong>? This action cannot be undone.</p>
        `;
        document.getElementById('confirm-delete-user-button').onclick = async () => {
            try {
                const response = await fetch(`/admin/social-users/${user.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast('success', 'Deleted!', result.message);
                    closeModal('deleteUserModal');
                    // Reload page or remove row from table
                    location.reload(); // Simple reload for now
                } else {
                    showToast('error', 'Error', result.message);
                }
            } catch (error) {
                showToast('error', 'Error', 'Failed to delete user.');
            }
        };
        openModal('deleteUserModal');
    };

    // Form Submissions
    document.getElementById('edit-user-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const userId = document.getElementById('edit-user-id').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/admin/social-users/${userId}`, {
                method: 'PUT', // Or POST with _method: 'PUT'
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast('success', 'Saved!', result.message);
                closeModal('editUserModal');
                location.reload();
            } else {
                showToast('error', 'Error', result.message);
            }
        } catch (error) {
            showToast('error', 'Error', 'Failed to save changes.');
        }
    });

    document.getElementById('send-whatsapp-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const userId = document.getElementById('whatsapp-user-id').value;
        const message = document.getElementById('whatsapp-message').value;

        try {
            const response = await fetch(`/admin/social-users/${userId}/send-whatsapp`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });
            const result = await response.json();
            if (result.success) {
                showToast('success', 'Sent!', result.message);
                closeModal('whatsAppModal');
            } else {
                showToast('error', 'Error', result.message);
            }
        } catch (error) {
            showToast('error', 'Error', 'Failed to send WhatsApp message.');
        }
    });

    // Filter and Search Logic (client-side for simplicity)
    const searchTermInput = document.getElementById('search-term');
    const providerSelect = document.getElementById('selected-provider');
    const usersTableBody = document.querySelector('#social-users-table tbody');

    function renderTable(filteredUsers) {
        usersTableBody.innerHTML = ''; // Clear existing rows
        filteredUsers.forEach(user => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.setAttribute('data-user-id', user.id);
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        <div class="text-sm text-gray-500">${user.email}</div>
                        <div class="text-xs text-gray-400">${user.ip}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <i data-lucide="message-circle" class="w-4 h-4 text-green-600 mr-2"></i>
                        <span class="text-sm text-gray-900">${user.whatsapp}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.provider === 'Google' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${user.provider}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${user.connectedAt}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${user.session}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${user.dataUsage}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status === 'online' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${user.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <button onclick="viewUser(${JSON.stringify(user)})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="View User"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        <button onclick="editUser(${JSON.stringify(user)})" class="p-1.5 rounded-md text-green-600 hover:bg-green-100" title="Edit User"><i data-lucide="edit" class="w-4 h-4"></i></button>
                        <button onclick="sendWhatsApp(${JSON.stringify(user)})" class="p-1.5 rounded-md text-orange-600 hover:bg-orange-100" title="Send WhatsApp"><i data-lucide="message-circle" class="w-4 h-4"></i></button>
                        <button onclick="deleteUser(${JSON.stringify(user)})" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete User"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </td>
            `;
            usersTableBody.appendChild(row);
        });
        lucide.createIcons(); // Re-render icons after updating table
    }

    function applyFilters() {
        const searchTerm = searchTermInput.value.toLowerCase();
        const selectedProvider = providerSelect.value;

        const filtered = usersData.filter(user => {
            const matchesSearch = user.name.toLowerCase().includes(searchTerm) ||
                                 user.email.toLowerCase().includes(searchTerm) ||
                                 user.whatsapp.includes(searchTerm);
            const matchesProvider = selectedProvider === 'All Providers' || user.provider === selectedProvider;
            return matchesSearch && matchesProvider;
        });
        renderTable(filtered);
    }

    searchTermInput.addEventListener('input', applyFilters);
    providerSelect.addEventListener('change', applyFilters);

    // Initial render
    applyFilters();

    // Auto Delete / Date Range Logic (client-side simulation)
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const dateRangeSelect = document.getElementById('date-range');
    const searchDateButton = document.getElementById('search-button');
    const deleteRangeButton = document.getElementById('delete-range-button');

    dateRangeSelect.addEventListener('change', function() {
        const today = new Date();
        let start = '';
        let end = today.toISOString().split('T')[0]; // YYYY-MM-DD

        if (this.value === 'Last 7 days') {
            const sevenDaysAgo = new Date(today);
            sevenDaysAgo.setDate(today.getDate() - 7);
            start = sevenDaysAgo.toISOString().split('T')[0];
        } else if (this.value === 'Last 30 days') {
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            start = thirtyDaysAgo.toISOString().split('T')[0];
        } else if (this.value === 'Last 3 months') {
            const threeMonthsAgo = new Date(today);
            threeMonthsAgo.setMonth(today.getMonth() - 3);
            start = threeMonthsAgo.toISOString().split('T')[0];
        }
        startDateInput.value = start;
        endDateInput.value = end;
    });

    searchDateButton.addEventListener('click', function() {
        const start = startDateInput.value;
        const end = endDateInput.value;
        if (start && end) {
            showToast('info', 'Searching', `Searching users from ${start} to ${end}`);
            // In a real app, you'd send these dates to the backend to filter
        } else {
            showToast('error', 'Error', 'Please select both start and end dates.');
        }
    });

    deleteRangeButton.addEventListener('click', function() {
        const start = startDateInput.value;
        const end = endDateInput.value;
        if (start && end) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete users from ${start} to ${end}. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showToast('success', 'Deleting', `Simulating deletion of users from ${start} to ${end}`);
                    // In a real app, you'd send these dates to the backend to delete
                }
            });
        } else {
            showToast('error', 'Error', 'Please select both start and end dates.');
        }
    });

    document.getElementById('export-data-button').addEventListener('click', function() {
        const headers = ['Name', 'Email', 'WhatsApp', 'Provider', 'Connected At', 'Session', 'Data Usage', 'Status'];
        const csvContent = [
            headers.join(','),
            ...usersData.map(user => [
                user.name,
                user.email,
                user.whatsapp,
                user.provider,
                user.connectedAt,
                user.session,
                user.dataUsage,
                user.status
            ].map(field => `"${field}"`).join(',')) // Wrap fields in quotes to handle commas
        ].join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `social-users-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('success', 'Exported!', 'Social users data exported successfully!');
    });
</script>
@endpush