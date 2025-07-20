<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MikroTik Hotspot</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Untuk toast/alert --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased">
    @php
        $adminSettings = json_decode(session('admin_settings', json_encode(['siteName' => 'MYHOTSPOT', 'welcomeMessage' => 'Administrator Panel', 'logo' => null, 'backgroundImage' => null, 'primaryColor' => '#1E3A8A', 'secondaryColor' => '#475569'])), true);
        $primaryColor = $adminSettings['primaryColor'] ?? '#1E3A8A';
        $secondaryColor = $adminSettings['secondaryColor'] ?? '#475569';
        $backgroundImage = $adminSettings['backgroundImage'] ?? null;
    @endphp

    <div class="min-h-screen flex items-center justify-center"
         style="background-size: cover; background-position: center center; background-attachment: fixed;
                background-image: {{ $backgroundImage ? 'url(' . $backgroundImage . ')' : 'linear-gradient(135deg, ' . $primaryColor . ', ' . $secondaryColor . ')' }}">
        <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-2xl">
            <div class="text-center mb-8">
                @if($adminSettings['logo'])
                    <img src="{{ $adminSettings['logo'] }}" alt="Logo" class="h-24 w-auto mx-auto mb-4" />
                @else
                    <div class="mx-auto w-16 h-16 bg-gradient-to-r from-blue-600 to-slate-700 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="shield" class="w-8 h-8 text-white"></i>
                    </div>
                @endif
                <h1 class="text-2xl font-bold text-gray-800">{{ $adminSettings['siteName'] ?? 'MYHOTSPOT' }}</h1>
                <p class="text-gray-600 mt-2">{{ $adminSettings['welcomeMessage'] ?? 'Administrator Panel' }}</p>
            </div>

            <div id="error-message" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg hidden"></div>

            <form id="admin-login-form" class="space-y-6">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter username"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                            required
                        />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter password"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                            required
                        />
                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600"
                        >
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    id="login-button"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center"
                >
                    <i data-lucide="shield" class="w-5 h-5 mr-2"></i>
                    <span>Sign In to Admin Panel</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons(); // Initialize Lucide icons

        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('admin-login-form');
        const loginButton = document.getElementById('login-button');
        const errorMessage = document.getElementById('error-message');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').setAttribute('data-lucide', type === 'password' ? 'eye' : 'eye-off');
            lucide.createIcons(); // Re-render icon
        });

        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            loginButton.disabled = true;
            loginButton.querySelector('span').textContent = 'Signing in...';
            errorMessage.classList.add('hidden');

            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('{{ route('admin.login') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Redirecting to dashboard...',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    errorMessage.textContent = result.message || 'Login failed. Please try again.';
                    errorMessage.classList.remove('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: result.message || 'Invalid username or password.',
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                errorMessage.textContent = 'An unexpected error occurred. Please try again later.';
                errorMessage.classList.remove('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                });
            } finally {
                loginButton.disabled = false;
                loginButton.querySelector('span').textContent = 'Sign In to Admin Panel';
            }
        });
    </script>
</body>
</html>