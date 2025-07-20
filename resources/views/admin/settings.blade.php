@extends('admin.layout')

@section('title', 'Settings')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
            <p class="text-gray-600">Configure your hotspot system settings</p>
        </div>

        {{-- Hotspot Login Appearance & Branding --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <i data-lucide="palette" class="w-5 h-5 text-purple-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Hotspot Login Appearance</h3>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                        <input
                            type="text"
                            id="hotspot-site-name"
                            value="{{ $hotspotSettings['siteName'] ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    id="hotspot-primary-color-picker"
                                    value="{{ $hotspotSettings['primaryColor'] ?? '#3B82F6' }}"
                                    class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                                />
                                <input
                                    type="text"
                                    id="hotspot-primary-color-text"
                                    value="{{ $hotspotSettings['primaryColor'] ?? '#3B82F6' }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    id="hotspot-secondary-color-picker"
                                    value="{{ $hotspotSettings['secondaryColor'] ?? '#8B5CF6' }}"
                                    class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                                />
                                <input
                                    type="text"
                                    id="hotspot-secondary-color-text"
                                    value="{{ $hotspotSettings['secondaryColor'] ?? '#8B5CF6' }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Logo</label>
                            @if($hotspotSettings['logo'])
                                <button type="button" id="remove-hotspot-logo" class="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <input
                            type="file"
                            id="hotspot-logo-file"
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                        />
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Background Image</label>
                            @if($hotspotSettings['backgroundImage'])
                                <button type="button" id="remove-hotspot-background" class="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <input
                            type="file"
                            id="hotspot-background-file"
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                        />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Welcome Message</label>
                        <textarea
                            id="hotspot-welcome-message"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >{{ $hotspotSettings['welcomeMessage'] ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <i data-lucide="eye" class="w-4 h-4 text-gray-600 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">Hotspot Login Preview</span>
                    </div>
                    
                    <div 
                        id="hotspot-preview"
                        class="rounded-lg p-6 text-white min-h-[300px] flex items-center justify-center relative overflow-hidden"
                        style="background-size: cover; background-position: center center; background-image: {{ $hotspotSettings['backgroundImage'] ? 'url(' . $hotspotSettings['backgroundImage'] . ')' : 'linear-gradient(135deg, ' . ($hotspotSettings['primaryColor'] ?? '#3B82F6') . ', ' . ($hotspotSettings['secondaryColor'] ?? '#8B5CF6') . ')' }}"
                    >
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="text-center z-10">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                                @if($hotspotSettings['logo'])
                                    <img src="{{ $hotspotSettings['logo'] }}" alt="Logo Preview" class="w-full h-full object-cover" id="hotspot-logo-preview" />
                                @else
                                    <span class="text-2xl">📶</span>
                                @endif
                            </div>
                            <h2 class="text-2xl font-bold mb-2" id="hotspot-site-name-preview">{{ $hotspotSettings['siteName'] ?? 'MyHotspot-WiFi' }}</h2>
                            <p class="text-white text-opacity-90" id="hotspot-welcome-message-preview">{{ $hotspotSettings['welcomeMessage'] ?? 'Welcome to MyHotspot Free WiFi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Login Appearance & Branding --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <i data-lucide="shield" class="w-5 h-5 text-blue-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Admin Login Appearance</h3>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Site Name</label>
                        <input
                            type="text"
                            id="admin-site-name"
                            value="{{ $adminSettings['siteName'] ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    id="admin-primary-color-picker"
                                    value="{{ $adminSettings['primaryColor'] ?? '#1E3A8A' }}"
                                    class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                                />
                                <input
                                    type="text"
                                    id="admin-primary-color-text"
                                    value="{{ $adminSettings['primaryColor'] ?? '#1E3A8A' }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    id="admin-secondary-color-picker"
                                    value="{{ $adminSettings['secondaryColor'] ?? '#475569' }}"
                                    class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                                />
                                <input
                                    type="text"
                                    id="admin-secondary-color-text"
                                    value="{{ $adminSettings['secondaryColor'] ?? '#475569' }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Admin Logo</label>
                            @if($adminSettings['logo'])
                                <button type="button" id="remove-admin-logo" class="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <input
                            type="file"
                            id="admin-logo-file"
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                        />
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Admin Background Image</label>
                            @if($adminSettings['backgroundImage'])
                                <button type="button" id="remove-admin-background" class="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <input
                            type="file"
                            id="admin-background-file"
                            accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                        />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Welcome Message</label>
                        <textarea
                            id="admin-welcome-message"
                            rows={3}
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >{{ $adminSettings['welcomeMessage'] ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <i data-lucide="eye" class="w-4 h-4 text-gray-600 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">Admin Login Preview</span>
                    </div>
                    
                    <div 
                        id="admin-preview"
                        class="rounded-lg p-6 text-white min-h-[300px] flex items-center justify-center relative overflow-hidden"
                        style="background-size: cover; background-position: center center; background-image: {{ $adminSettings['backgroundImage'] ? 'url(' . $adminSettings['backgroundImage'] . ')' : 'linear-gradient(135deg, ' . ($adminSettings['primaryColor'] ?? '#1E3A8A') . ', ' . ($adminSettings['secondaryColor'] ?? '#475569') . ')' }}"
                    >
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="text-center z-10">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                                @if($adminSettings['logo'])
                                    <img src="{{ $adminSettings['logo'] }}" alt="Logo Preview" class="w-full h-full object-cover" id="admin-logo-preview" />
                                @else
                                    <i data-lucide="shield" class="w-8 h-8"></i>
                                @endif
                            </div>
                            <h2 class="text-2xl font-bold mb-2" id="admin-site-name-preview">{{ $adminSettings['siteName'] ?? 'MYHOTSPOT' }}</h2>
                            <p class="text-white text-opacity-90" id="admin-welcome-message-preview">{{ $adminSettings['welcomeMessage'] ?? 'Administrator Panel' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- API Keys Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <i data-lucide="message-circle" class="w-5 h-5 text-green-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Fonte WhatsApp API</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input
                        type="text"
                        id="fonte-api-key"
                        value="{{ $apiKeys['fonteApiKey'] ?? '' }}"
                        placeholder="Enter your Fonte API key"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Device ID</label>
                    <input
                        type="text"
                        id="fonte-device-id"
                        value="{{ $apiKeys['fonteDeviceId'] ?? '' }}"
                        placeholder="Enter your WhatsApp device ID"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Note:</strong> Get your Fonte API credentials from{' '}
                    <a href="https://fonte.id" target="_blank" rel="noopener noreferrer" class="text-green-600 hover:underline">
                        fonte.id
                    </a>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <i data-lucide="globe" class="w-5 h-5 text-red-600 mr-2"></i>
                <h3 class="text-lg font-semibold text-gray-800">Google Integration</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Client ID</label>
                    <input
                        type="text"
                        id="google-client-id"
                        value="{{ $apiKeys['googleClientId'] ?? '' }}"
                        placeholder="Enter your Google Client ID"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Google Client Secret</label>
                    <input
                        type="password"
                        id="google-client-secret"
                        value="{{ $apiKeys['googleClientSecret'] ?? '' }}"
                        placeholder="Enter your Google Client Secret"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Redirect URI</label>
                    <input
                        type="url"
                        id="google-redirect-uri"
                        value="{{ $apiKeys['googleRedirectUri'] ?? 'https://yourdomain.com/auth/google/callback' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    />
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-red-50 rounded-lg">
                <p class="text-sm text-red-800">
                    <strong>Setup Instructions:</strong> Create a new project in{' '}
                    <a href="https://console.developers.google.com" target="_blank" rel="noopener noreferrer" class="text-red-600 hover:underline">
                        Google Cloud Console
                    </a>
                    , enable Google+ API, and create OAuth 2.0 credentials.
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                type="button"
                id="save-all-settings-button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2"
            >
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    lucide.createIcons();

    // Hotspot Settings Elements
    const hotspotSiteName = document.getElementById('hotspot-site-name');
    const hotspotPrimaryColorPicker = document.getElementById('hotspot-primary-color-picker');
    const hotspotPrimaryColorText = document.getElementById('hotspot-primary-color-text');
    const hotspotSecondaryColorPicker = document.getElementById('hotspot-secondary-color-picker');
    const hotspotSecondaryColorText = document.getElementById('hotspot-secondary-color-text');
    const hotspotLogoFile = document.getElementById('hotspot-logo-file');
    const removeHotspotLogoBtn = document.getElementById('remove-hotspot-logo');
    const hotspotBackgroundFile = document.getElementById('hotspot-background-file');
    const removeHotspotBackgroundBtn = document.getElementById('remove-hotspot-background');
    const hotspotWelcomeMessage = document.getElementById('hotspot-welcome-message');

    // Hotspot Preview Elements
    const hotspotPreview = document.getElementById('hotspot-preview');
    const hotspotLogoPreview = document.getElementById('hotspot-logo-preview');
    const hotspotSiteNamePreview = document.getElementById('hotspot-site-name-preview');
    const hotspotWelcomeMessagePreview = document.getElementById('hotspot-welcome-message-preview');

    // Admin Settings Elements
    const adminSiteName = document.getElementById('admin-site-name');
    const adminPrimaryColorPicker = document.getElementById('admin-primary-color-picker');
    const adminPrimaryColorText = document.getElementById('admin-primary-color-text');
    const adminSecondaryColorPicker = document.getElementById('admin-secondary-color-picker');
    const adminSecondaryColorText = document.getElementById('admin-secondary-color-text');
    const adminLogoFile = document.getElementById('admin-logo-file');
    const removeAdminLogoBtn = document.getElementById('remove-admin-logo');
    const adminBackgroundFile = document.getElementById('admin-background-file');
    const removeAdminBackgroundBtn = document.getElementById('remove-admin-background');
    const adminWelcomeMessage = document.getElementById('admin-welcome-message');

    // Admin Preview Elements
    const adminPreview = document.getElementById('admin-preview');
    const adminLogoPreview = document.getElementById('admin-logo-preview');
    const adminSiteNamePreview = document.getElementById('admin-site-name-preview');
    const adminWelcomeMessagePreview = document.getElementById('admin-welcome-message-preview');

    // API Keys Elements
    const fonteApiKey = document.getElementById('fonte-api-key');
    const fonteDeviceId = document.getElementById('fonte-device-id');
    const googleClientId = document.getElementById('google-client-id');
    const googleClientSecret = document.getElementById('google-client-secret');
    const googleRedirectUri = document.getElementById('google-redirect-uri');

    const saveAllSettingsButton = document.getElementById('save-all-settings-button');

    // --- Hotspot Preview Logic ---
    function updateHotspotPreview() {
        const primary = hotspotPrimaryColorText.value;
        const secondary = hotspotSecondaryColorText.value;
        const backgroundImage = hotspotBackgroundFile.files[0] ? URL.createObjectURL(hotspotBackgroundFile.files[0]) : '{{ $hotspotSettings['backgroundImage'] ?? '' }}';
        const logoImage = hotspotLogoFile.files[0] ? URL.createObjectURL(hotspotLogoFile.files[0]) : '{{ $hotspotSettings['logo'] ?? '' }}';

        hotspotSiteNamePreview.textContent = hotspotSiteName.value;
        hotspotWelcomeMessagePreview.textContent = hotspotWelcomeMessage.value;

        if (backgroundImage) {
            hotspotPreview.style.backgroundImage = `url(${backgroundImage})`;
        } else {
            hotspotPreview.style.backgroundImage = `linear-gradient(135deg, ${primary}, ${secondary})`;
        }

        if (hotspotLogoPreview) {
            if (logoImage) {
                hotspotLogoPreview.src = logoImage;
                hotspotLogoPreview.style.display = 'block';
                hotspotLogoPreview.parentElement.innerHTML = `<img src="${logoImage}" alt="Logo Preview" class="w-full h-full object-cover" id="hotspot-logo-preview" />`;
            } else {
                hotspotLogoPreview.parentElement.innerHTML = `<span class="text-2xl">📶</span>`;
            }
            lucide.createIcons();
        }
    }

    hotspotSiteName.addEventListener('input', updateHotspotPreview);
    hotspotPrimaryColorPicker.addEventListener('input', (e) => { hotspotPrimaryColorText.value = e.target.value; updateHotspotPreview(); });
    hotspotPrimaryColorText.addEventListener('input', updateHotspotPreview);
    hotspotSecondaryColorPicker.addEventListener('input', (e) => { hotspotSecondaryColorText.value = e.target.value; updateHotspotPreview(); });
    hotspotSecondaryColorText.addEventListener('input', updateHotspotPreview);
    hotspotLogoFile.addEventListener('change', updateHotspotPreview);
    if (removeHotspotLogoBtn) {
        removeHotspotLogoBtn.addEventListener('click', () => {
            hotspotLogoFile.value = ''; // Clear file input
            hotspotLogoPreview.parentElement.innerHTML = `<span class="text-2xl">📶</span>`;
            lucide.createIcons();
            updateHotspotPreview();
        });
    }
    hotspotBackgroundFile.addEventListener('change', updateHotspotPreview);
    if (removeHotspotBackgroundBtn) {
        removeHotspotBackgroundBtn.addEventListener('click', () => {
            hotspotBackgroundFile.value = ''; // Clear file input
            updateHotspotPreview();
        });
    }
    hotspotWelcomeMessage.addEventListener('input', updateHotspotPreview);

    // --- Admin Preview Logic ---
    function updateAdminPreview() {
        const primary = adminPrimaryColorText.value;
        const secondary = adminSecondaryColorText.value;
        const backgroundImage = adminBackgroundFile.files[0] ? URL.createObjectURL(adminBackgroundFile.files[0]) : '{{ $adminSettings['backgroundImage'] ?? '' }}';
        const logoImage = adminLogoFile.files[0] ? URL.createObjectURL(adminLogoFile.files[0]) : '{{ $adminSettings['logo'] ?? '' }}';

        adminSiteNamePreview.textContent = adminSiteName.value;
        adminWelcomeMessagePreview.textContent = adminWelcomeMessage.value;

        if (backgroundImage) {
            adminPreview.style.backgroundImage = `url(${backgroundImage})`;
        } else {
            adminPreview.style.backgroundImage = `linear-gradient(135deg, ${primary}, ${secondary})`;
        }

        if (adminLogoPreview) {
            if (logoImage) {
                adminLogoPreview.src = logoImage;
                adminLogoPreview.style.display = 'block';
                adminLogoPreview.parentElement.innerHTML = `<img src="${logoImage}" alt="Logo Preview" class="w-full h-full object-cover" id="admin-logo-preview" />`;
            } else {
                adminLogoPreview.parentElement.innerHTML = `<i data-lucide="shield" class="w-8 h-8"></i>`;
            }
            lucide.createIcons();
        }
    }

    adminSiteName.addEventListener('input', updateAdminPreview);
    adminPrimaryColorPicker.addEventListener('input', (e) => { adminPrimaryColorText.value = e.target.value; updateAdminPreview(); });
    adminPrimaryColorText.addEventListener('input', updateAdminPreview);
    adminSecondaryColorPicker.addEventListener('input', (e) => { adminSecondaryColorText.value = e.target.value; updateAdminPreview(); });
    adminSecondaryColorText.addEventListener('input', updateAdminPreview);
    adminLogoFile.addEventListener('change', updateAdminPreview);
    if (removeAdminLogoBtn) {
        removeAdminLogoBtn.addEventListener('click', () => {
            adminLogoFile.value = ''; // Clear file input
            adminLogoPreview.parentElement.innerHTML = `<i data-lucide="shield" class="w-8 h-8"></i>`;
            lucide.createIcons();
            updateAdminPreview();
        });
    }
    adminBackgroundFile.addEventListener('change', updateAdminPreview);
    if (removeAdminBackgroundBtn) {
        removeAdminBackgroundBtn.addEventListener('click', () => {
            adminBackgroundFile.value = ''; // Clear file input
            updateAdminPreview();
        });
    }
    adminWelcomeMessage.addEventListener('input', updateAdminPreview);

    // Initial preview update on page load
    updateHotspotPreview();
    updateAdminPreview();

    // --- Save All Settings Logic ---
    saveAllSettingsButton.addEventListener('click', async function() {
        const settingsData = {
            hotspot_site_name: hotspotSiteName.value,
            hotspot_primary_color: hotspotPrimaryColorText.value,
            hotspot_secondary_color: hotspotSecondaryColorText.value,
            hotspot_welcome_message: hotspotWelcomeMessage.value,
            
            admin_site_name: adminSiteName.value,
            admin_primary_color: adminPrimaryColorText.value,
            admin_secondary_color: adminSecondaryColorText.value,
            admin_welcome_message: adminWelcomeMessage.value,

            fonte_api_key: fonteApiKey.value,
            fonte_device_id: fonteDeviceId.value,
            google_client_id: googleClientId.value,
            google_client_secret: googleClientSecret.value,
            google_redirect_uri: googleRedirectUri.value,
        };

        // Handle file uploads (convert to base64 for simplicity, or send as multipart/form-data)
        const readFileAsBase64 = (file) => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
                reader.readAsDataURL(file);
            });
        };

        const toastId = showToast('info', 'Saving...', 'Saving all settings...');

        try {
            if (hotspotLogoFile.files[0]) {
                settingsData.hotspot_logo = await readFileAsBase64(hotspotLogoFile.files[0]);
            } else if (removeHotspotLogoBtn && removeHotspotLogoBtn.style.display !== 'none') { // Check if remove button was clicked
                settingsData.hotspot_logo = null;
            } else {
                settingsData.hotspot_logo = '{{ $hotspotSettings['logo'] ?? '' }}'; // Keep existing if no new file and not removed
            }

            if (hotspotBackgroundFile.files[0]) {
                settingsData.hotspot_background_image = await readFileAsBase64(hotspotBackgroundFile.files[0]);
            } else if (removeHotspotBackgroundBtn && removeHotspotBackgroundBtn.style.display !== 'none') {
                settingsData.hotspot_background_image = null;
            } else {
                settingsData.hotspot_background_image = '{{ $hotspotSettings['backgroundImage'] ?? '' }}';
            }

            if (adminLogoFile.files[0]) {
                settingsData.admin_logo = await readFileAsBase64(adminLogoFile.files[0]);
            } else if (removeAdminLogoBtn && removeAdminLogoBtn.style.display !== 'none') {
                settingsData.admin_logo = null;
            } else {
                settingsData.admin_logo = '{{ $adminSettings['logo'] ?? '' }}';
            }

            if (adminBackgroundFile.files[0]) {
                settingsData.admin_background_image = await readFileAsBase64(adminBackgroundFile.files[0]);
            } else if (removeAdminBackgroundBtn && removeAdminBackgroundBtn.style.display !== 'none') {
                settingsData.admin_background_image = null;
            } else {
                settingsData.admin_background_image = '{{ $adminSettings['backgroundImage'] ?? '' }}';
            }

            const response = await fetch('{{ route('admin.settings.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(settingsData)
            });
            const result = await response.json();

            if (result.success) {
                Swal.update({ icon: 'success', title: 'Saved!', text: result.message, timer: 3000 });
                // Reload page to reflect changes from AppServiceProvider
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.update({ icon: 'error', title: 'Error', text: result.message, timer: 3000 });
            }
        } catch (error) {
            console.error('Error saving settings:', error);
            Swal.update({ icon: 'error', title: 'Error', text: 'Failed to save settings.', timer: 3000 });
        }
    });
</script>
@endpush