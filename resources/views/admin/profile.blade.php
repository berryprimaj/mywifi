@extends('admin.layout')

@section('title', 'Profile')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
            <p class="text-gray-600">Manage your account settings and security</p>
        </div>

        {{-- Combined Profile and Password Settings --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form id="profile-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                    {{-- Profile Information Section --}}
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 text-blue-600 mr-2"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input
                                type="text"
                                name="username"
                                id="profile-username"
                                value="{{ $user->username ?? '' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="profile-email"
                                value="{{ $user->email ?? '' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>

                    {{-- Change Password Section --}}
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <i data-lucide="lock" class="w-5 h-5 text-red-600 mr-2"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Change Password</h3>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="current_password"
                                    id="current-password"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter current password"
                                />
                                <button
                                    type="button"
                                    id="toggle-current-password"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="new_password"
                                    id="new-password"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Leave blank to keep current"
                                />
                                <button
                                    type="button"
                                    id="toggle-new-password"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="new_password_confirmation" {{-- Laravel's convention for password confirmation --}}
                                    id="confirm-password"
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Confirm new password"
                                />
                                <button
                                    type="button"
                                    id="toggle-confirm-password"
                                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Save Button --}}
                <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                    <button
                        type="submit"
                        id="save-profile-button"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2"
                    >
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Save All Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    const profileForm = document.getElementById('profile-form');
    const profileUsername = document.getElementById('profile-username');
    const profileEmail = document.getElementById('profile-email');
    const currentPassword = document.getElementById('current-password');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    const saveProfileButton = document.getElementById('save-profile-button');

    // Password visibility toggles
    function setupPasswordToggle(toggleBtnId, passwordInputId) {
        const toggleBtn = document.getElementById(toggleBtnId);
        const passwordInput = document.getElementById(passwordInputId);
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
                lucide.createIcons();
            });
        }
    }

    setupPasswordToggle('toggle-current-password', 'current-password');
    setupPasswordToggle('toggle-new-password', 'new-password');
    setupPasswordToggle('toggle-confirm-password', 'confirm-password');

    profileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        saveProfileButton.disabled = true;
        saveProfileButton.querySelector('span').textContent = 'Saving...';

        const profileData = {
            username: profileUsername.value,
            email: profileEmail.value,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        const passwordData = {
            current_password: currentPassword.value,
            new_password: newPassword.value,
            new_password_confirmation: confirmPassword.value,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        let profileUpdateSuccess = true;
        let passwordUpdateSuccess = true;

        // Update Profile Information
        try {
            const response = await fetch('{{ route('admin.profile.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(profileData)
            });
            const result = await response.json();
            if (result.success) {
                showToast('success', 'Profile Updated!', result.message);
            } else {
                profileUpdateSuccess = false;
                showToast('error', 'Profile Update Failed', result.message || 'Failed to update profile information.');
            }
        } catch (error) {
            profileUpdateSuccess = false;
            showToast('error', 'Error', 'An unexpected error occurred during profile update.');
        }

        // Update Password (if new password fields are filled)
        if (newPassword.value || currentPassword.value || confirmPassword.value) {
            if (!currentPassword.value) {
                showToast('error', 'Password Change Failed', 'Please enter your current password to set a new one.');
                passwordUpdateSuccess = false;
            } else if (newPassword.value !== confirmPassword.value) {
                showToast('error', 'Password Change Failed', 'New passwords do not match!');
                passwordUpdateSuccess = false;
            } else if (!newPassword.value) {
                showToast('error', 'Password Change Failed', 'Please enter a new password.');
                passwordUpdateSuccess = false;
            } else {
                try {
                    const response = await fetch('{{ route('admin.profile.update-password') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(passwordData)
                    });
                    const result = await response.json();
                    if (result.success) {
                        showToast('success', 'Password Updated!', result.message);
                        currentPassword.value = '';
                        newPassword.value = '';
                        confirmPassword.value = '';
                    } else {
                        passwordUpdateSuccess = false;
                        showToast('error', 'Password Change Failed', result.message || 'Failed to change password.');
                    }
                } catch (error) {
                    passwordUpdateSuccess = false;
                    showToast('error', 'Error', 'An unexpected error occurred during password change.');
                }
            }
        }

        saveProfileButton.disabled = false;
        saveProfileButton.querySelector('span').textContent = 'Save All Changes';
    });
</script>
@endpush