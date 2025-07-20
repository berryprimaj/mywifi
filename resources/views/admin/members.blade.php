@extends('admin.layout')

@section('title', 'Members')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Members</h1>
                <p class="text-gray-600">Manage employee member accounts</p>
            </div>
            <div class="flex space-x-2">
                <button 
                    id="export-excel-button"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                >
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Export Excel</span>
                </button>
                <button 
                    id="upload-excel-toggle"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                >
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    <span>Upload Excel</span>
                </button>
                <button 
                    id="add-member-toggle"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                >
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Add Member</span>
                </button>
            </div>
        </div>

        <div id="add-member-form-container" class="bg-white rounded-lg shadow-sm p-6 hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Add New Member</h3>
                <button type="button" onclick="toggleAddForm()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="add-member-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                        <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter username" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter full name" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter email address" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter department" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter password" required />
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="toggleAddForm()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Add Member</button>
                </div>
            </form>
        </div>

        <div id="upload-excel-form-container" class="bg-white rounded-lg shadow-sm p-6 hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Upload Members from Excel</h3>
                <button type="button" onclick="toggleUploadForm()" class="text-gray-500 hover:text-gray-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="upload-excel-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Excel File</label>
                        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-medium text-blue-800 mb-2">Excel Format Requirements:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Column A: Username</li>
                            <li>• Column B: Full Name</li>
                            <li>• Column C: Email</li>
                            <li>• Column D: Department</li>
                            <li>• Column E: Password</li>
                        </ul>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="toggleUploadForm()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">Upload Members</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                        <input type="text" id="search-term" placeholder="Search members..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg" />
                    </div>
                </div>
                <div>
                    <select id="selected-status" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option>All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Members</p>
                        <p class="text-2xl font-bold text-gray-800" id="total-members-count">{{ count($members) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full"><i data-lucide="user-check" class="w-6 h-6 text-blue-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Active Members</p>
                        <p class="text-2xl font-bold text-gray-800" id="active-members-count">{{ collect($members)->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full"><i data-lucide="user-check" class="w-6 h-6 text-green-600"></i></div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Inactive Members</p>
                        <p class="text-2xl font-bold text-gray-800" id="inactive-members-count">{{ collect($members)->where('status', 'inactive')->count() }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full"><i data-lucide="user-check" class="w-6 h-6 text-red-600"></i></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="members-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Members will be rendered by JS --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.modal', ['id' => 'viewMemberModal', 'title' => 'View Member Details'])
        <div class="space-y-3 text-sm" id="view-member-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end pt-4">
            <button onclick="closeModal('viewMemberModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
        </div>
    @endinclude

    @include('components.modal', ['id' => 'passwordMemberModal', 'title' => 'Member Password'])
        <div class="space-y-4 text-sm">
            <div>
                <label class="font-semibold text-gray-700">Username</label>
                <p class="mt-1 p-2 bg-gray-100 rounded-md" id="password-username-display"></p>
            </div>
            <div>
                <label class="font-semibold text-gray-700">Password</label>
                <p class="mt-1 p-2 bg-gray-100 rounded-md font-mono" id="password-display"></p>
            </div>
            <div class="flex justify-end pt-4">
                <button onclick="closeModal('passwordMemberModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
            </div>
        </div>
    @endinclude

    @include('components.modal', ['id' => 'editMemberModal', 'title' => 'Edit Member'])
        <form id="edit-member-form">
            @csrf
            @method('PUT') {{-- Use PUT method for update --}}
            <input type="hidden" name="id" id="edit-member-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="edit-member-username" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="edit-member-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-member-email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" id="edit-member-department" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="edit-member-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                    <input type="password" name="password" id="edit-member-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Leave blank to keep current" />
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="closeModal('editMemberModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'deleteMemberModal', 'title' => 'Confirm Deletion'])
        <div id="delete-member-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" onclick="closeModal('deleteMemberModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="button" id="confirm-delete-member-button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
        </div>
    @endinclude
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    let membersData = @json($members); // Initial data from PHP

    const addMemberFormContainer = document.getElementById('add-member-form-container');
    const uploadExcelFormContainer = document.getElementById('upload-excel-form-container');
    const addMemberToggleBtn = document.getElementById('add-member-toggle');
    const uploadExcelToggleBtn = document.getElementById('upload-excel-toggle');
    const addMemberForm = document.getElementById('add-member-form');
    const editMemberForm = document.getElementById('edit-member-form');
    const membersTableBody = document.querySelector('#members-table tbody');
    const searchTermInput = document.getElementById('search-term');
    const selectedStatusSelect = document.getElementById('selected-status');

    function toggleAddForm() {
        addMemberFormContainer.classList.toggle('hidden');
        uploadExcelFormContainer.classList.add('hidden'); // Hide upload form if add form is shown
        addMemberForm.reset(); // Clear form on toggle
        lucide.createIcons();
    }

    function toggleUploadForm() {
        uploadExcelFormContainer.classList.toggle('hidden');
        addMemberFormContainer.classList.add('hidden'); // Hide add form if upload form is shown
        lucide.createIcons();
    }

    addMemberToggleBtn.addEventListener('click', toggleAddForm);
    uploadExcelToggleBtn.addEventListener('click', toggleUploadForm);

    // Render table and update counts
    function renderMembersTable(filteredMembers) {
        membersTableBody.innerHTML = '';
        filteredMembers.forEach(member => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${member.name}</div>
                        <div class="text-sm text-gray-500">${member.username}</div>
                        <div class="text-xs text-gray-400">${member.email}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">${member.department}</span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.lastLogin}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.dataUsage}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${member.sessionTime}</td>
                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${member.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${member.status}</span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <button onclick="viewMember(${JSON.stringify(member)})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="View Member"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        <button onclick="viewPassword(${JSON.stringify(member)})" class="p-1.5 rounded-md text-yellow-600 hover:bg-yellow-100" title="View Password"><i data-lucide="key" class="w-4 h-4"></i></button>
                        <button onclick="editMember(${JSON.stringify(member)})" class="p-1.5 rounded-md text-green-600 hover:bg-green-100" title="Edit Member"><i data-lucide="edit" class="w-4 h-4"></i></button>
                        <button onclick="deleteMember(${JSON.stringify(member)})" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete Member"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </td>
            `;
            membersTableBody.appendChild(row);
        });
        lucide.createIcons(); // Re-render icons
        updateCounts(filteredMembers);
    }

    function updateCounts(currentMembers) {
        document.getElementById('total-members-count').textContent = currentMembers.length;
        document.getElementById('active-members-count').textContent = currentMembers.filter(m => m.status === 'active').length;
        document.getElementById('inactive-members-count').textContent = currentMembers.filter(m => m.status === 'inactive').length;
    }

    function applyFiltersAndSearch() {
        const searchTerm = searchTermInput.value.toLowerCase();
        const selectedStatus = selectedStatusSelect.value;

        const filtered = membersData.filter(member => {
            const matchesSearch = member.username.toLowerCase().includes(searchTerm) ||
                                 member.name.toLowerCase().includes(searchTerm) ||
                                 member.email.toLowerCase().includes(searchTerm);
            const matchesStatus = selectedStatus === 'All Status' || member.status.toLowerCase() === selectedStatus.toLowerCase();
            return matchesSearch && matchesStatus;
        });
        renderMembersTable(filtered);
    }

    searchTermInput.addEventListener('input', applyFiltersAndSearch);
    selectedStatusSelect.addEventListener('change', applyFiltersAndSearch);

    // Initial render
    applyFiltersAndSearch();

    // Modal functions
    window.viewMember = function(member) {
        document.getElementById('view-member-details').innerHTML = `
            <p><strong>Username:</strong> ${member.username}</p>
            <p><strong>Full Name:</strong> ${member.name}</p>
            <p><strong>Email:</strong> ${member.email}</p>
            <p><strong>Department:</strong> ${member.department}</p>
            <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${member.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${member.status}</span></p>
            <p><strong>Last Login:</strong> ${member.lastLogin}</p>
            <p><strong>Data Usage:</strong> ${member.dataUsage}</p>
            <p><strong>Session Time:</strong> ${member.sessionTime}</p>
        `;
        openModal('viewMemberModal');
    };

    window.viewPassword = function(member) {
        document.getElementById('password-username-display').textContent = member.username;
        document.getElementById('password-display').textContent = member.password || 'Not set';
        openModal('passwordMemberModal');
    };

    window.editMember = function(member) {
        document.getElementById('edit-member-id').value = member.id;
        document.getElementById('edit-member-username').value = member.username;
        document.getElementById('edit-member-name').value = member.name;
        document.getElementById('edit-member-email').value = member.email;
        document.getElementById('edit-member-department').value = member.department;
        document.getElementById('edit-member-status').value = member.status;
        document.getElementById('edit-member-password').value = ''; // Clear password field for security
        openModal('editMemberModal');
    };

    window.deleteMember = function(member) {
        document.getElementById('delete-member-details').innerHTML = `
            <p>Are you sure you want to delete the member <strong>${member.name}</strong>? This action cannot be undone.</p>
        `;
        document.getElementById('confirm-delete-member-button').onclick = async () => {
            try {
                const response = await fetch(`/admin/members/${member.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast('success', 'Deleted!', result.message);
                    closeModal('deleteMemberModal');
                    membersData = membersData.filter(m => m.id !== member.id);
                    applyFiltersAndSearch(); // Re-render table and update counts
                } else {
                    showToast('error', 'Error', result.message);
                }
            } catch (error) {
                showToast('error', 'Error', 'Failed to delete member.');
            }
        };
        openModal('deleteMemberModal');
    };

    // Form Submissions
    addMemberForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('{{ route('admin.members.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                showToast('success', 'Added!', result.message);
                addMemberForm.reset();
                addMemberFormContainer.classList.add('hidden');
                // Add new member to local data and re-render
                membersData.push({
                    id: result.member.id,
                    username: result.member.username,
                    name: result.member.name,
                    email: result.member.email,
                    department: result.member.department,
                    password: result.member.password, // For simulation
                    status: result.member.is_active ? 'active' : 'inactive',
                    lastLogin: 'Never',
                    dataUsage: '0 MB',
                    sessionTime: '0m',
                });
                applyFiltersAndSearch();
            } else {
                showToast('error', 'Error', result.message || 'Failed to add member.');
            }
        } catch (error) {
            showToast('error', 'Error', 'An unexpected error occurred.');
        }
    });

    editMemberForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const memberId = document.getElementById('edit-member-id').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/admin/members/${memberId}`, {
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
                closeModal('editMemberModal');
                // Update member in local data and re-render
                membersData = membersData.map(m => m.id == memberId ? {
                    ...m,
                    username: result.member.username,
                    name: result.member.name,
                    email: result.member.email,
                    department: result.member.department,
                    status: result.member.is_active ? 'active' : 'inactive',
                    password: result.member.password || m.password // Update password if provided
                } : m);
                applyFiltersAndSearch();
            } else {
                showToast('error', 'Error', result.message || 'Failed to save changes.');
            }
        } catch (error) {
            showToast('error', 'Error', 'An unexpected error occurred.');
        }
    });

    document.getElementById('export-excel-button').addEventListener('click', function() {
        const headers = ['Username', 'Name', 'Email', 'Department', 'Status', 'Last Login', 'Data Usage', 'Session Time'];
        const csvContent = [
            headers.join(','),
            ...membersData.map(member => [
                member.username,
                member.name,
                member.email,
                member.department,
                member.status,
                member.lastLogin,
                member.dataUsage,
                member.sessionTime
            ].map(field => `"${field}"`).join(','))
        ].join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `members-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('success', 'Exported!', 'Members data exported successfully!');
    });

    document.getElementById('upload-excel-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const fileInput = this.querySelector('input[type="file"]');
        if (fileInput.files.length > 0) {
            showToast('success', 'Uploading', `Simulating upload of ${fileInput.files[0].name}`);
            toggleUploadForm();
            // In a real app, you'd send the file to a Laravel route for processing
        } else {
            showToast('error', 'Error', 'Please select a file to upload.');
        }
    });
</script>
@endpush