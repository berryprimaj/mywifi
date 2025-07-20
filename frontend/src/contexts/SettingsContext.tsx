import React, { createContext, useContext, useState, ReactNode, useEffect, useCallback } from 'react';
import toast from 'react-hot-toast';
import { useAuth } from './AuthContext'; // Import useAuth

interface Settings {
  siteName: string;
  primaryColor: string;
  secondaryColor: string;
  logo: string | null;
  backgroundImage: string | null;
  welcomeMessage: string;
}

export interface ApiKeys {
  fonteApiKey: string;
  fonteDeviceId: string;
  googleClientId: string;
  googleClientSecret: string;
  googleRedirectUri: string;
  mikrotikHost: string;
  mikrotikPort: string;
  mikrotikUsername: string;
  mikrotikPassword: string;
}

interface SettingsContextType {
  settings: Settings;
  updateSettings: (newSettings: Partial<Settings>) => void;
  adminSettings: Settings;
  updateAdminSettings: (newSettings: Partial<Settings>) => void;
  apiKeys: ApiKeys;
  updateApiKeys: (newKeys: Partial<ApiKeys>) => void;
  fetchSettingsFromBackend: () => Promise<void>;
  saveSettingsToBackend: () => Promise<void>;
}

// Default values for initial state before fetching from backend
const defaultSettings: Settings = {
  siteName: 'MyHotspot-WiFi',
  primaryColor: '#3B82F6',
  secondaryColor: '#8B5CF6',
  logo: null,
  backgroundImage: null,
  welcomeMessage: 'Welcome to MyHotspot Free WiFi',
};

const defaultAdminSettings: Settings = {
  siteName: 'MYHOTSPOT',
  primaryColor: '#1E3A8A',
  secondaryColor: '#475569',
  logo: null,
  backgroundImage: null,
  welcomeMessage: 'Administrator Panel',
};

const defaultApiKeys: ApiKeys = {
  fonteApiKey: '',
  fonteDeviceId: '',
  googleClientId: '',
  googleClientSecret: '',
  googleRedirectUri: 'https://yourdomain.com/auth/google/callback',
  mikrotikHost: '',
  mikrotikPort: '8728',
  mikrotikUsername: 'admin',
  mikrotikPassword: '',
};

const SettingsContext = createContext<SettingsContextType | undefined>(undefined);

export const useSettings = () => {
  const context = useContext(SettingsContext);
  if (context === undefined) {
    throw new Error('useSettings must be used within a SettingsProvider');
  }
  return context;
};

// Mengambil BASE_API_URL dari variabel lingkungan Vite
// Pastikan variabel ini diawali dengan VITE_
const BASE_API_URL = '/api';

export const SettingsProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const { user, logout } = useAuth(); // Dapatkan user dan logout dari AuthContext
  const [settings, setSettings] = useState<Settings>(defaultSettings);
  const [adminSettings, setAdminSettings] = useState<Settings>(defaultAdminSettings);
  const [apiKeys, setApiKeys] = useState<ApiKeys>(defaultApiKeys);

  // Dapatkan token dari localStorage atau state AuthContext
  // Untuk kesederhanaan, kita bisa langsung ambil dari localStorage di sini
  // atau Anda bisa menambahkan `token` ke `AuthContextType` dan mengambilnya via `useAuth()`
  const getToken = useCallback(() => {
    return localStorage.getItem('auth-token');
  }, []);

  const fetchSettingsFromBackend = useCallback(async () => {
    const token = getToken();
    if (!token && user) { // Jika ada user tapi tidak ada token, mungkin sesi habis
      logout(); // Paksa logout
      toast.error('Session expired. Please log in again.');
      return;
    }
    
    try {
      const response = await fetch(`${BASE_API_URL}/settings`, {
        headers: {
          'Authorization': `Bearer ${token}`, // Sertakan token di sini
          'Accept': 'application/json',
        },
      });

      if (response.status === 401) { // Unauthorized
        logout();
        toast.error('Unauthorized. Please log in again.');
        return;
      }
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      
      setSettings(data.hotspot || defaultSettings);
      setAdminSettings(data.admin || defaultAdminSettings);
      setApiKeys(data.apiKeys || defaultApiKeys);

      toast.success('Settings loaded from backend.');
    } catch (error) {
      console.error('Failed to fetch settings from backend:', error);
      toast.error('Failed to load settings. Using default values.');
      // Fallback to defaults if fetching fails
      setSettings(defaultSettings);
      setAdminSettings(defaultAdminSettings);
      setApiKeys(defaultApiKeys);
    }
  }, [getToken, user, logout]); // Tambahkan getToken, user, logout sebagai dependencies

  const saveSettingsToBackend = useCallback(async () => {
    const token = getToken();
    if (!token && user) {
      logout();
      toast.error('Session expired. Please log in again.');
      return;
    }

    const allSettings = {
      hotspot: settings,
      admin: adminSettings,
      apiKeys: apiKeys,
    };
    try {
      const response = await fetch(`${BASE_API_URL}/settings`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`, // Sertakan token di sini
          'Accept': 'application/json',
        },
        body: JSON.stringify(allSettings),
      });

      if (response.status === 401) { // Unauthorized
        logout();
        toast.error('Unauthorized. Please log in again.');
        return;
      }
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      await response.json(); // Parse response if needed, e.g., for success message
      toast.success('Settings saved to backend!');
    } catch (error) {
      console.error('Failed to save settings to backend:', error);
      toast.error('Failed to save settings.');
    }
  }, [settings, adminSettings, apiKeys, getToken, user, logout]); // Tambahkan dependencies

  // Fetch settings on initial load
  useEffect(() => {
    // Hanya fetch jika user sudah login (ada token)
    if (user && getToken()) {
      fetchSettingsFromBackend();
    }
  }, [fetchSettingsFromBackend, user, getToken]);

  const updateSettings = (newSettings: Partial<Settings>) => {
    setSettings(prevSettings => ({ ...prevSettings, ...newSettings }));
  };

  const updateAdminSettings = (newSettings: Partial<Settings>) => {
    setAdminSettings(prevSettings => ({ ...prevSettings, ...newSettings }));
  };

  const updateApiKeys = (newKeys: Partial<ApiKeys>) => {
    setApiKeys(prevKeys => ({ ...prevKeys, ...newKeys }));
  };

  const value = { 
    settings, 
    updateSettings, 
    adminSettings, 
    updateAdminSettings,
    apiKeys,
    updateApiKeys,
    fetchSettingsFromBackend,
    saveSettingsToBackend
  };

  return (
    <SettingsContext.Provider value={value}>
      {children}
    </SettingsContext.Provider>
  );
};