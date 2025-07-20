@extends('layouts.admin_layout')

@section('title', 'Roles & Permissions')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
                <p class="text-gray-600">Manage administrator roles and permissions</p>
            </div>
            @if($authUser && $authUser->role === 'super_admin')
                <div class="flex space-x-2">
                    <button
                        type="button"
                        id="password-settings-toggle"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                    >
                        <i data-lucide="key" class="w-4 h-4"></i>
                        <span>Password Settings</span>
                    </button>
                    <button
                        type="button"
                        id="add-admin-toggle"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                    >
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Add Administrator</span>
                    </button>
                </div>
            @endif
        </div>

        @if($authUser && $authUser->role === 'super_admin')
            <div id="add-admin-form-container" class="bg-white rounded-lg shadow-sm p-6 hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Administrator</h3>
                    <button type="button" onclick="toggleAddAdminForm()" class="text-gray-500 hover:text-gray-700"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <form id="add-admin-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                <input type="text" name="username" placeholder="Username" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" placeholder="Password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                                    @foreach($roles as $role)
                                        @if($role['value'] !== 'super_admin' || ($authUser && $authUser->role === 'super_admin'))
                                            <option value="{{ $role['value'] }}">{{ $role['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Permissions</h4>
                            <p class="text-sm text-gray-600">Permissions are inherited from the selected role. Custom permissions can be configured in the role editor.</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4 mt-6 border-t pt-4">
                        <button type="button" onclick="toggleAddAdminForm()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Add Administrator</button>
                    </div>
                </form>
            </div>
        @endif

        @if($authUser && $authUser->role === 'super_admin')
            <div id="password-settings-container" class="bg-white rounded-lg shadow-sm p-6 hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Password Security Settings</h3>
                    <button type="button" onclick="togglePasswordSettings()" class="text-gray-500 hover:text-gray-700"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                {{-- Password settings form fields --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Password Length</label>
                        <input type="number" id="min-password-length" value="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Require Special Characters</label>
                        <input type="checkbox" id="require-special-chars" class="ml-2" />
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="togglePasswordSettings()" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button type="button" id="save-password-settings-button" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Save Settings</button>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Static Role-Based Access Control (Static RBAC)</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                @foreach($roles as $role)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $role['name'] }}</h4>
                                <span class="text-sm text-gray-500">{{ $role['users'] }} Users</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button onclick="editRole({{ json_encode($role) }})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="Edit"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                @if($role['name'] !== 'Super Administrator')
                                    <button onclick="deleteRole({{ json_encode($role) }})" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                @endif
                            </div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-xs text-gray-600 mb-2">{{ $role['description'] }}</p>
                            <p class="text-xs font-medium text-gray-800 mb-1">Permissions:</p>
                            @if($role['permissions'][0] === '*')
                                <span class="text-xs font-semibold text-red-600">All Permissions</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($role['permissions'], 0, 2) as $p)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">{{ $p }}</span>
                                    @endforeach
                                    @if(count($role['permissions']) > 2)
                                        <span class="text-xs bg-gray-200 text-gray-800 px-1.5 py-0.5 rounded">+{{ count($role['permissions']) - 2 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Administrator List Table --}}
        @if($authUser && $authUser->role === 'super_admin')
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Administrator List</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full" id="admin-list-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Admins will be rendered by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @include('components.modal', ['id' => 'viewAdminModal', 'title' => 'View Administrator Details'])
        <div class="space-y-3 text-sm" id="view-admin-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end pt-4">
            <button onclick="closeModal('viewAdminModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
        </div>
    @endinclude

    @include('components.modal', ['id' => 'editAdminModal', 'title' => 'Edit Administrator'])
        <form id="edit-admin-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-admin-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="edit-admin-username" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit-admin-email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="edit-admin-role" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                        @foreach($roles as $role)
                            @if($role['value'] !== 'super_admin' || ($authUser && $authUser->role === 'super_admin'))
                                <option value="{{ $role['value'] }}">{{ $role['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                    <input type="password" name="password" id="edit-admin-password" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Leave blank to keep current password" />
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6 mt-4 border-t">
                <button type="button" onclick="closeModal('editAdminModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'deleteAdminModal', 'title' => 'Confirm Deletion'])
        <div id="delete-admin-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" onclick="closeModal('deleteAdminModal')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="button" id="confirm-delete-admin-button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
        </div>
    @endinclude

    @include('components.modal', ['id' => 'editRoleModal', 'title' => 'Edit Role'])
        <form id="edit-role-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-role-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <input type="text" name="name" id="edit-role-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit-role-description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permissions (comma separated)</label>
                    <input type="text" name="permissions" id="edit-role-permissions" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                    <p class="text-xs text-gray-500 mt-1">Use '*' for all permissions. Example: users.view, users.create</p>
                </div>
            </div>
            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" onclick="closeModal('editRoleModal')" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    @endinclude

    @include('components.modal', ['id' => 'deleteRoleModal', 'title' => 'Confirm Deletion'])
        <div id="delete-role-details">
            {{-- Details will be populated by JS --}}
        </div>
        <div class="flex justify-end space-x-4 pt-6">
            <button type="button" onclick="closeModal('deleteRoleModal')" class="px-4 py-2 border rounded-lg">Cancel</button>
            <button type="button" id="confirm-delete-role-button" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
        </div>
    @endinclude
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    let adminsData = @json($admins);
    const rolesData = @json($roles);
    const currentAdminId = "{{ $authUser->id ?? '' }}";
    const currentAdminRole = "{{ $authUser->role ?? '' }}";

    const addAdminFormContainer = document.getElementById('add-admin-form-container');
    const passwordSettingsContainer = document.getElementById('password-settings-container');
    const addAdminToggleBtn = document.getElementById('add-admin-toggle');
    const passwordSettingsToggleBtn = document.getElementById('password-settings-toggle');
    const addAdminForm = document.getElementById('add-admin-form');
    const editAdminForm = document.getElementById('edit-admin-form');
    const adminListTableBody = document.querySelector('#admin-list-table tbody');

    function toggleAddAdminForm() {
        addAdminFormContainer.classList.toggle('hidden');
        passwordSettingsContainer.classList.add('hidden'); // Hide other form
        addAdminForm.reset();
        lucide.createIcons();
    }

    function togglePasswordSettings() {
        passwordSettingsContainer.classList.toggle('hidden');
        addAdminFormContainer.classList.add('hidden'); // Hide other form
        lucide.createIcons();
    }

    if (addAdminToggleBtn) addAdminToggleBtn.addEventListener('click', toggleAddAdminForm);
    if (passwordSettingsToggleBtn) passwordSettingsToggleBtn.addEventListener('click', togglePasswordSettings);

    // Render Admin List Table
    function renderAdminTable() {
        adminListTableBody.innerHTML = '';
        adminsData.forEach(admin => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            const roleName = rolesData.find(r => r.value === admin.role)?.name || admin.role;
            const canEdit = currentAdminRole === 'super_admin' || (admin.role !== 'super_admin' && admin.id !== currentAdminId);
            const canDelete = currentAdminRole === 'super_admin' && admin.role !== 'super_admin' && admin.id !== currentAdminId;

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${admin.username}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${admin.email}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        ${roleName}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <button onclick="viewAdmin(${JSON.stringify(admin)})" class="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="View Admin"><i data-lucide="eye" class="w-4 h-4"></i></button>
                        ${canEdit ? `<button onclick="editAdmin(${JSON.stringify(admin)})" class="p-1.5 rounded-md text-green-600 hover:bg-green-100" title="Edit Admin"><i data-lucide="edit" class="w-4 h-4"></i></button>` : ''}
                        ${canDelete ? `<button onclick="deleteAdmin(${JSON.stringify(admin)})" class="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete Admin"><i data-lucide="trash-2" class="w-4 h-4"></i></button>` : ''}
                    </div>
                </td>
            `;
            adminListTableBody.appendChild(row);
        });
        lucide.createIcons();
    }

    if (adminListTableBody) { // Only render if the table exists (i.e., super_admin is logged in)
        renderAdminTable();
    }

    // Modal functions for Admins
    window.viewAdmin = function(admin) {
        document.getElementById('view-admin-details').innerHTML = `
            <p><strong>Username:</strong> ${admin.username}</p>
            <p><strong>Email:</strong> ${admin.email}</p>
            <p><strong>Role:</strong> ${rolesData.find(r => r.value === admin.role)?.name || admin.role}</p>
            <p><strong>Password (hashed):</strong> ${admin.password || 'Not Set'}</p>
        `;
        openModal('viewAdminModal');
    };

    window.editAdmin = function(admin) {
        if (admin.role === 'super_admin' && currentAdminRole !== 'super_admin') {
            showToast('error', 'Permission Denied', 'Super Administrator cannot be edited by non-super admin.');
            return;
        }
        if (admin.id == currentAdminId && admin.role === 'super_admin' && document.getElementById('edit-admin-role').value !== 'super_admin') {
            showToast('error', 'Action Forbidden', 'You cannot change your own Super Administrator role.');
            return;
        }
        document.getElementById('edit-admin-id').value = admin.id;
        document.getElementById('edit-admin-username').value = admin.username;
        document.getElementById('edit-admin-email').value = admin.email;
        document.getElementById('edit-admin-role').value = admin.role;
        document.getElementById('edit-admin-password').value = ''; // Clear password field
        openModal('editAdminModal');
    };

    window.deleteAdmin = function(admin) {
        if (admin.role === 'super_admin') {
            showToast('error', 'Permission Denied', 'Super Administrator cannot be deleted.');
            return;
        }
        if (admin.id == currentAdminId) {
            showToast('error', 'Action Forbidden', 'You cannot delete your own account.');
            return;
        }
        document.getElementById('delete-admin-details').innerHTML = `
            <p>Are you sure you want to delete the administrator <strong>${admin.username}</strong>? This action cannot be undone.</p>
        `;
        document.getElementById('confirm-delete-admin-button').onclick = async () => {
            try {
                const response = await fetch(`/admin/permissions/admins/${admin.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast('success', 'Deleted!', result.message);
                    closeModal('deleteAdminModal');
                    // Remove from local data and re-render
                    adminsData = adminsData.filter(a => a.id !== admin.id);
                    renderAdminTable();
                } else {
                    showToast('error', 'Error', result.message);
                }
            } catch (error) {
                showToast('error', 'Error', 'Failed to delete administrator.');
            }
        };
        openModal('deleteAdminModal');
    };

    // Form Submissions for Admins
    if (addAdminForm) {
        addAdminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('{{ route('admin.permissions.store_admin') }}', {
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
                    addAdminForm.reset();
                    addAdminFormContainer.classList.add('hidden');
                    // Add new admin to local data and re-render
                    adminsData.push({
                        id: result.admin.id,
                        username: result.admin.username,
                        email: result.admin.email,
                        role: result.admin.role,
                        password: '***' // Don't expose actual password
                    });
                    renderAdminTable();
                } else {
                    showToast('error', 'Error', result.message || 'Failed to add administrator.');
                }
            } catch (error) {
                showToast('error', 'Error', 'An unexpected error occurred.');
            }
        });
    }

    if (editAdminForm) {
        editAdminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const adminId = document.getElementById('edit-admin-id').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(`/admin/permissions/admins/${adminId}`, {
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
                    closeModal('editAdminModal');
                    // Update admin in local data and re-render
                    adminsData = adminsData.map(a => a.id == adminId ? {
                        ...a,
                        username: result.admin.username,
                        email: result.admin.email,
                        role: result.admin.role,
                        password: result.admin.password || a.password // Update password if provided
                    } : a);
                    renderAdminTable();
                } else {
                    showToast('error', 'Error', result.message || 'Failed to save changes.');
                }
            } catch (error) {
                showToast('error', 'Error', 'An unexpected error occurred.');
            }
        });
    }

    // Modal functions for Roles (static for now)
    window.editRole = function(role) {
        document.getElementById('edit-role-id').value = role.id;
        document.getElementById('edit-role-name').value = role.name;
        document.getElementById('edit-role-description').value = role.description;
        document.getElementById('edit-role-permissions').value = role.permissions.join(', ');
        openModal('editRoleModal');
    };

    window.deleteRole = function(role) {
        if (role.name === 'Super Administrator') {
            showToast('error', 'Cannot Delete', 'Cannot delete Super Administrator role.');
            return;
        }
        document.getElementById('delete-role-details').innerHTML = `
            <p>Are you sure you want to delete role <strong>${role.name}</strong>?</p>
        `;
        document.getElementById('confirm-delete-role-button').onclick = () => {
            showToast('success', 'Deleted!', `Simulating deletion of role ${role.name}`);
            closeModal('deleteRoleModal');
            // In a real app, you'd send a delete request to the backend
        };
        openModal('deleteRoleModal');
    };

    document.getElementById('save-password-settings-button').addEventListener('click', function() {
        showToast('success', 'Saved!', 'Password settings saved successfully!');
        togglePasswordSettings();
    });

    document.getElementById('edit-role-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const roleName = document.getElementById('edit-role-name').value;
        showToast('success', 'Saved!', `Role ${roleName} updated successfully!`);
        closeModal('editRoleModal');
        // In a real app, you'd send an update request to the backend
    });
</script>
@endpush