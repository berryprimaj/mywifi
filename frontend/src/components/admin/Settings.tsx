import { Palette, MessageCircle, Globe, Save, Eye, Shield, Trash2 } from 'lucide-react';
import Layout from './Layout';
import toast from 'react-hot-toast';
import { useSettings } from '../../contexts/SettingsContext';
import { useEffect } from 'react';

const Settings = () => {
  const { 
    settings, 
    updateSettings, 
    adminSettings,
    updateAdminSettings,
    apiKeys,
    updateApiKeys,
    fetchSettingsFromBackend,
    saveSettingsToBackend
  } = useSettings();

  useEffect(() => {
    fetchSettingsFromBackend();
  }, [fetchSettingsFromBackend]);

  const handleHotspotInputChange = (key: string, value: string) => {
    updateSettings({ [key]: value });
  };

  const handleHotspotFileChange = (key: 'logo' | 'backgroundImage', file: File | null) => {
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        updateSettings({ [key]: e.target?.result as string });
      };
      reader.readAsDataURL(file);
    } else {
      updateSettings({ [key]: null });
    }
  };

  const handleAdminInputChange = (key: string, value: string) => {
    updateAdminSettings({ [key]: value });
  };

  const handleAdminFileChange = (key: 'logo' | 'backgroundImage', file: File | null) => {
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        updateAdminSettings({ [key]: e.target?.result as string });
      };
      reader.readAsDataURL(file);
    } else {
      updateAdminSettings({ [key]: null });
    }
  };

  const handleApiInputChange = (key: string, value: string) => {
    updateApiKeys({ [key]: value });
  };

  const handleSave = async () => {
    await saveSettingsToBackend();
  };

  const hotspotBackgroundStyle = {
    backgroundSize: 'cover',
    backgroundPosition: 'center center',
    backgroundImage: settings.backgroundImage
      ? `url(${settings.backgroundImage})`
      : `linear-gradient(135deg, ${settings.primaryColor}, ${settings.secondaryColor})`,
  };

  const adminBackgroundStyle = {
    backgroundSize: 'cover',
    backgroundPosition: 'center center',
    backgroundImage: adminSettings.backgroundImage
      ? `url(${adminSettings.backgroundImage})`
      : `linear-gradient(135deg, ${adminSettings.primaryColor}, ${adminSettings.secondaryColor})`,
  };

  return (
    <Layout>
      <div className="space-y-8">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Settings</h1>
          <p className="text-gray-600">Configure your hotspot system settings</p>
        </div>

        {/* Hotspot Login Appearance & Branding */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center mb-6">
            <Palette className="w-5 h-5 text-purple-600 mr-2" />
            <h3 className="text-lg font-semibold text-gray-800">Hotspot Login Appearance</h3>
          </div>
          
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                <input
                  type="text"
                  value={settings.siteName}
                  onChange={(e) => handleHotspotInputChange('siteName', e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                  <div className="flex items-center space-x-2">
                    <input
                      type="color"
                      value={settings.primaryColor}
                      onChange={(e) => handleHotspotInputChange('primaryColor', e.target.value)}
                      className="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                    <input
                      type="text"
                      value={settings.primaryColor}
                      onChange={(e) => handleHotspotInputChange('primaryColor', e.target.value)}
                      className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
                
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                  <div className="flex items-center space-x-2">
                    <input
                      type="color"
                      value={settings.secondaryColor}
                      onChange={(e) => handleHotspotInputChange('secondaryColor', e.target.value)}
                      className="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                    <input
                      type="text"
                      value={settings.secondaryColor}
                      onChange={(e) => handleHotspotInputChange('secondaryColor', e.target.value)}
                      className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>
              
              <div>
                <div className="flex justify-between items-center mb-2">
                  <label className="block text-sm font-medium text-gray-700">Logo</label>
                  {settings.logo && (
                    <button type="button" onClick={() => handleHotspotFileChange('logo', null)} className="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                      <Trash2 className="w-3 h-3" />
                      <span>Remove</span>
                    </button>
                  )}
                </div>
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleHotspotFileChange('logo', e.target.files ? e.target.files[0] : null)}
                  className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                />
              </div>
              
              <div>
                <div className="flex justify-between items-center mb-2">
                  <label className="block text-sm font-medium text-gray-700">Background Image</label>
                  {settings.backgroundImage && (
                    <button type="button" onClick={() => handleHotspotFileChange('backgroundImage', null)} className="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                      <Trash2 className="w-3 h-3" />
                      <span>Remove</span>
                    </button>
                  )}
                </div>
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleHotspotFileChange('backgroundImage', e.target.files ? e.target.files[0] : null)}
                  className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Welcome Message</label>
                <textarea
                  value={settings.welcomeMessage}
                  onChange={(e) => handleHotspotInputChange('welcomeMessage', e.target.value)}
                  rows={3}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>
            
            <div className="bg-gray-50 rounded-lg p-4">
              <div className="flex items-center mb-4">
                <Eye className="w-4 h-4 text-gray-600 mr-2" />
                <span className="text-sm font-medium text-gray-700">Hotspot Login Preview</span>
              </div>
              
              <div 
                className="rounded-lg p-6 text-white min-h-[300px] flex items-center justify-center relative overflow-hidden"
                style={hotspotBackgroundStyle}
              >
                <div className="absolute inset-0 bg-black bg-opacity-40"></div>
                <div className="text-center z-10">
                  <div className="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                    {settings.logo ? (
                      <img src={settings.logo} alt="Logo Preview" className="w-full h-full object-cover" />
                    ) : (
                      <span className="text-2xl">📶</span>
                    )}
                  </div>
                  <h2 className="text-2xl font-bold mb-2">{settings.siteName}</h2>
                  <p className="text-white text-opacity-90">{settings.welcomeMessage}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Admin Login Appearance & Branding */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center mb-6">
            <Shield className="w-5 h-5 text-blue-600 mr-2" />
            <h3 className="text-lg font-semibold text-gray-800">Admin Login Appearance</h3>
          </div>
          
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Admin Site Name</label>
                <input
                  type="text"
                  value={adminSettings.siteName}
                  onChange={(e) => handleAdminInputChange('siteName', e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                  <div className="flex items-center space-x-2">
                    <input
                      type="color"
                      value={adminSettings.primaryColor}
                      onChange={(e) => handleAdminInputChange('primaryColor', e.target.value)}
                      className="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                    <input
                      type="text"
                      value={adminSettings.primaryColor}
                      onChange={(e) => handleAdminInputChange('primaryColor', e.target.value)}
                      className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
                
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                  <div className="flex items-center space-x-2">
                    <input
                      type="color"
                      value={adminSettings.secondaryColor}
                      onChange={(e) => handleAdminInputChange('secondaryColor', e.target.value)}
                      className="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                    <input
                      type="text"
                      value={adminSettings.secondaryColor}
                      onChange={(e) => handleAdminInputChange('secondaryColor', e.target.value)}
                      className="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>
              
              <div>
                <div className="flex justify-between items-center mb-2">
                  <label className="block text-sm font-medium text-gray-700">Admin Logo</label>
                  {adminSettings.logo && (
                    <button type="button" onClick={() => handleAdminFileChange('logo', null)} className="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                      <Trash2 className="w-3 h-3" />
                      <span>Remove</span>
                    </button>
                  )}
                </div>
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleAdminFileChange('logo', e.target.files ? e.target.files[0] : null)}
                  className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                />
              </div>
              
              <div>
                <div className="flex justify-between items-center mb-2">
                  <label className="block text-sm font-medium text-gray-700">Admin Background Image</label>
                  {adminSettings.backgroundImage && (
                    <button type="button" onClick={() => handleAdminFileChange('backgroundImage', null)} className="text-xs text-red-600 hover:underline font-medium flex items-center space-x-1">
                      <Trash2 className="w-3 h-3" />
                      <span>Remove</span>
                    </button>
                  )}
                </div>
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleAdminFileChange('backgroundImage', e.target.files ? e.target.files[0] : null)}
                  className="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Admin Welcome Message</label>
                <textarea
                  value={adminSettings.welcomeMessage}
                  onChange={(e) => handleAdminInputChange('welcomeMessage', e.target.value)}
                  rows={3}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>
            
            <div className="bg-gray-50 rounded-lg p-4">
              <div className="flex items-center mb-4">
                <Eye className="w-4 h-4 text-gray-600 mr-2" />
                <span className="text-sm font-medium text-gray-700">Admin Login Preview</span>
              </div>
              
              <div 
                className="rounded-lg p-6 text-white min-h-[300px] flex items-center justify-center relative overflow-hidden"
                style={adminBackgroundStyle}
              >
                <div className="absolute inset-0 bg-black bg-opacity-40"></div>
                <div className="text-center z-10">
                  <div className="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden">
                    {adminSettings.logo ? (
                      <img src={adminSettings.logo} alt="Logo Preview" className="w-full h-full object-cover" />
                    ) : (
                      <Shield className="w-8 h-8" />
                    )}
                  </div>
                  <h2 className="text-2xl font-bold mb-2">{adminSettings.siteName}</h2>
                  <p className="text-white text-opacity-90">{adminSettings.welcomeMessage}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* API Keys Section ... (omitted for brevity) */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center mb-6">
            <MessageCircle className="w-5 h-5 text-green-600 mr-2" />
            <h3 className="text-lg font-semibold text-gray-800">Fonte WhatsApp API</h3>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">API Key</label>
              <input
                type="text"
                value={apiKeys.fonteApiKey}
                onChange={(e) => handleApiInputChange('fonteApiKey', e.target.value)}
                placeholder="Enter your Fonte API key"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Device ID</label>
              <input
                type="text"
                value={apiKeys.fonteDeviceId}
                onChange={(e) => handleApiInputChange('fonteDeviceId', e.target.value)}
                placeholder="Enter your WhatsApp device ID"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>
          
          <div className="mt-4 p-4 bg-green-50 rounded-lg">
            <p className="text-sm text-green-800">
              <strong>Note:</strong> Get your Fonte API credentials from{' '}
              <a href="https://fonte.id" target="_blank" rel="noopener noreferrer" className="text-green-600 hover:underline">
                fonte.id
              </a>
            </p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex items-center mb-6">
            <Globe className="w-5 h-5 text-red-600 mr-2" />
            <h3 className="text-lg font-semibold text-gray-800">Google Integration</h3>
          </div>
          
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Google Client ID</label>
              <input
                type="text"
                value={apiKeys.googleClientId}
                onChange={(e) => handleApiInputChange('googleClientId', e.target.value)}
                placeholder="Enter your Google Client ID"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Google Client Secret</label>
              <input
                type="password"
                value={apiKeys.googleClientSecret}
                onChange={(e) => handleApiInputChange('googleClientSecret', e.target.value)}
                placeholder="Enter your Google Client Secret"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Redirect URI</label>
              <input
                type="url"
                value={apiKeys.googleRedirectUri}
                onChange={(e) => handleApiInputChange('googleRedirectUri', e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              />
            </div>
          </div>
          
          <div className="mt-4 p-4 bg-red-50 rounded-lg">
            <p className="text-sm text-red-800">
              <strong>Setup Instructions:</strong> Create a new project in{' '}
              <a href="https://console.developers.google.com" target="_blank" rel="noopener noreferrer" className="text-red-600 hover:underline">
                Google Cloud Console
              </a>
              , enable Google+ API, and create OAuth 2.0 credentials.
            </p>
          </div>
        </div>

        <div className="flex justify-end">
          <button
            onClick={handleSave}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2"
          >
            <Save className="w-5 h-5" />
            <span>Save All Settings</span>
          </button>
        </div>
      </div>
    </Layout>
  );
};

export default Settings;