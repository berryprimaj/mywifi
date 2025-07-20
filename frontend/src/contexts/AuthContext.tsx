import React, { createContext, useContext, useState, ReactNode, useEffect } from 'react';
import toast from 'react-hot-toast';

// Represents the currently logged-in user (without password)
export interface User {
  id: string;
  username: string;
  email: string;
  role: 'super_admin' | 'administrator' | 'moderator' | 'viewer';
  permissions: string[];
}

// Represents an admin user in our system (with password for login check)
export interface Admin extends User {
  password?: string;
}

interface AuthContextType {
  user: User | null;
  admins: Admin[];
  login: (username: string, password: string) => Promise<boolean>;
  logout: () => void;
  addAdmin: (admin: Omit<Admin, 'id' | 'permissions'>) => void;
  updateUser: (userId: string, updates: Partial<Admin>) => void;
  deleteAdmin: (userId: string) => void;
  isAuthenticated: boolean;
  hasPermission: (permission: string) => boolean;
  resetAuth: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

const defaultAdmins: Admin[] = [
  {
    id: '1',
    username: 'admin',
    email: 'admin@example.com',
    password: 'admin',
    role: 'super_admin',
    permissions: ['*']
  }
];

export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(() => {
    try {
      const savedUser = localStorage.getItem('auth-user');
      return savedUser ? JSON.parse(savedUser) : null;
    } catch {
      return null;
    }
  });

  const [admins, setAdmins] = useState<Admin[]>(() => {
    try {
      const savedAdmins = localStorage.getItem('auth-admins');
      return savedAdmins ? JSON.parse(savedAdmins) : defaultAdmins;
    } catch {
      return defaultAdmins;
    }
  });

  // Effect to persist user session
  useEffect(() => {
    if (user) {
      localStorage.setItem('auth-user', JSON.stringify(user));
    } else {
      localStorage.removeItem('auth-user');
    }
  }, [user]);

  // Effect to persist the list of admins
  useEffect(() => {
    localStorage.setItem('auth-admins', JSON.stringify(admins));
  }, [admins]);

  // Effect to sync user state when the admins list changes
  useEffect(() => {
    if (user) {
      const currentUserInAdmins = admins.find(a => a.id === user.id);
      if (currentUserInAdmins) { // <-- Perbaikan di sini
        const { password, ...userToSet } = currentUserInAdmins;
        // Prevent infinite loops by comparing before setting
        if (JSON.stringify(user) !== JSON.stringify(userToSet)) {
          setUser(userToSet);
        }
      } else {
        // If the user was deleted from the admin list, log them out
        setUser(null);
      }
    }
  }, [admins, user?.id]); // Rerun only when admins list or logged-in user ID changes

  const login = async (username: string, password: string): Promise<boolean> => {
    const adminToLogin = admins.find(
      (admin) => admin.username === username && admin.password === password
    );

    if (adminToLogin) {
      const { password, ...userToSet } = adminToLogin;
      setUser(userToSet);
      // Simulate storing a token for API calls
      localStorage.setItem('auth-token', 'dummy-jwt-token-for-simulated-backend'); 
      return true;
    }
    return false;
  };

  const logout = () => {
    setUser(null);
    localStorage.removeItem('auth-token'); // Remove token on logout
  };

  const addAdmin = (adminData: Omit<Admin, 'id' | 'permissions'>) => {
    const newAdmin: Admin = {
      id: Date.now().toString(),
      ...adminData,
      permissions: [], // Default to no permissions for simplicity
    };
    setAdmins((prevAdmins) => [...prevAdmins, newAdmin]);
  };

  const updateUser = (userId: string, updates: Partial<Admin>) => {
    setAdmins((prevAdmins) =>
      prevAdmins.map((admin) =>
        admin.id === userId ? { ...admin, ...updates } : admin
      )
    );
  };

  const deleteAdmin = (userId: string) => {
    setAdmins(prevAdmins => prevAdmins.filter(admin => admin.id !== userId));
  };

  const resetAuth = () => {
    localStorage.removeItem('auth-user');
    localStorage.removeItem('auth-admins');
    localStorage.removeItem('auth-token'); // Also remove the token
    setUser(null);
    setAdmins(defaultAdmins);
    toast.success('Akun telah direset ke default (admin/admin).');
  };

  const hasPermission = (permission: string) => {
    if (!user) return false;
    return user.permissions.includes('*') || user.permissions.includes(permission);
  };

  return (
    <AuthContext.Provider value={{
      user,
      admins,
      login,
      logout,
      addAdmin,
      updateUser,
      deleteAdmin,
      isAuthenticated: !!user,
      hasPermission,
      resetAuth
    }}>
      {children}
    </AuthContext.Provider>
  );
};