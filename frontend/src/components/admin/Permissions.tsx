import React, { useState } from 'react';
import { Plus, Edit, Trash2, X, Key, Eye } from 'lucide-react';
import Layout from './Layout';
import Modal from '../common/Modal';
import toast from 'react-hot-toast';
import { useAuth, Admin } from '../../contexts/AuthContext';

type Role = {
  id: number;
  name: string;
  value: 'super_admin' | 'administrator' | 'moderator' | 'viewer';
  users: number;
  permissions: string[];
  description: string;
  color: string;
};

const Permissions = () => {
  const { user, admins, addAdmin, updateUser, deleteAdmin } = useAuth();
  const [showAddForm, setShowAddForm] = useState(false);
  const [showPasswordSettings, setShowPasswordSettings] = useState(false);
  const [newAdmin, setNewAdmin] = useState<{ username: string; email: string; password: string; role: Role['value'] }>({ username: '', email: '', password: '', role: 'administrator' });

  const [isEditRoleModalOpen, setIsEditRoleModalOpen] = useState(false);
  const [isDeleteRoleModalOpen, setIsDeleteRoleModalOpen] = useState(false);
  const [isViewAdminModalOpen, setIsViewAdminModalOpen] = useState(false);
  const [isEditAdminModalOpen, setIsEditAdminModalOpen] = useState(false);
  const [isDeleteAdminModalOpen, setIsDeleteAdminModalOpen] = useState(false);

  const [selectedRole, setSelectedRole] = useState<Role | null>(null);
  const [editedRole, setEditedRole] = useState<Role | null>(null);
  const [selectedAdmin, setSelectedAdmin] = useState<Admin | null>(null);
  const [editedAdmin, setEditedAdmin] = useState<Admin | null>(null);

  const roles: Role[] = [
    { id: 1, name: 'Super Administrator', value: 'super_admin', users: admins.filter(a => a.role === 'super_admin').length, permissions: ['*'], description: 'Full system access', color: 'bg-red-100 text-red-800' },
    { id: 2, name: 'Administrator', value: 'administrator', users: admins.filter(a => a.role === 'administrator').length, permissions: ['users.view', 'users.create', 'users.edit', 'settings.view', 'settings.edit'], description: 'Manage users and basic settings', color: 'bg-blue-100 text-blue-800' },
    { id: 3, name: 'Moderator', value: 'moderator', users: admins.filter(a => a.role === 'moderator').length, permissions: ['users.view', 'users.edit', 'reports.view'], description: 'View and moderate users', color: 'bg-green-100 text-green-800' },
    { id: 4, name: 'Viewer', value: 'viewer', users: admins.filter(a => a.role === 'viewer').length, permissions: ['users.view', 'reports.view'], description: 'Read-only access', color: 'bg-gray-100 text-gray-800' }
  ];

  const handleEditRole = (roleId: number) => {
    const role = roles.find(r => r.id === roleId);
    if (role) {
      setSelectedRole(role);
      setEditedRole({ ...role });
      setIsEditRoleModalOpen(true);
    }
  };

  const handleDeleteRole = (roleId: number) => {
    const role = roles.find(r => r.id === roleId);
    if (role) {
      if (role.name === 'Super Administrator') {
        toast.error('Cannot delete Super Administrator role');
        return;
      }
      setSelectedRole(role);
      setIsDeleteRoleModalOpen(true);
    }
  };

  const handleViewAdmin = (admin: Admin) => {
    setSelectedAdmin(admin);
    setIsViewAdminModalOpen(true);
  };

  const handleEditAdmin = (admin: Admin) => {
    if (admin.role === 'super_admin') {
      toast.error('Super Administrator cannot be edited.');
      return;
    }
    setEditedAdmin({ ...admin });
    setIsEditAdminModalOpen(true);
  };

  const handleDeleteAdmin = (admin: Admin) => {
    if (admin.role === 'super_admin') {
      toast.error('Super Administrator cannot be deleted.');
      return;
    }
    if (user?.id === admin.id) {
      toast.error("You cannot delete your own account.");
      return;
    }
    setSelectedAdmin(admin);
    setIsDeleteAdminModalOpen(true);
  };

  const confirmEditRole = () => {
    toast.success(`Role ${editedRole?.name} updated successfully!`);
    setIsEditRoleModalOpen(false);
  };

  const confirmDeleteRole = () => {
    toast.success(`Role ${selectedRole?.name} deleted successfully!`);
    setIsDeleteRoleModalOpen(false);
  };

  const confirmEditAdmin = () => {
    if (editedAdmin) {
      updateUser(editedAdmin.id, editedAdmin);
      toast.success(`Administrator ${editedAdmin.username} updated successfully!`);
      setIsEditAdminModalOpen(false);
      setEditedAdmin(null);
    }
  };

  const confirmDeleteAdmin = () => {
    if (selectedAdmin) {
      deleteAdmin(selectedAdmin.id);
      toast.success(`Administrator ${selectedAdmin.username} deleted successfully!`);
      setIsDeleteAdminModalOpen(false);
      setSelectedAdmin(null);
    }
  };

  const handleEditAdminFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    if (editedAdmin) {
      const { name, value } = e.target;
      setEditedAdmin({
        ...editedAdmin,
        [name]: value,
      });
    }
  };

  const handleAddAdmin = () => {
    if (newAdmin.username && newAdmin.email && newAdmin.password) {
      addAdmin(newAdmin);
      toast.success('New administrator added successfully!');
      setShowAddForm(false);
      setNewAdmin({ username: '', email: '', password: '', role: 'administrator' });
    } else {
      toast.error('Please fill all required fields including password.');
    }
  };

  const handleSavePasswordSettings = () => {
    toast.success('Password settings saved successfully!');
    setShowPasswordSettings(false);
  };

  return (
    <Layout>
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
            <p className="text-gray-600">Manage administrator roles and permissions</p>
          </div>
          {user?.role === 'super_admin' && (
            <div className="flex space-x-2">
              <button
                onClick={() => setShowPasswordSettings(true)}
                className="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
              >
                <Key className="w-4 h-4" />
                <span>Password Settings</span>
              </button>
              <button
                onClick={() => setShowAddForm(!showAddForm)}
                className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
              >
                <Plus className="w-4 h-4" />
                <span>Add Administrator</span>
              </button>
            </div>
          )}
        </div>

        {user?.role === 'super_admin' && showAddForm && (
          <div className="bg-white rounded-lg shadow-sm p-6">
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-lg font-semibold text-gray-800">Add New Administrator</h3>
              <button onClick={() => setShowAddForm(false)} className="text-gray-500 hover:text-gray-700"><X className="w-5 h-5" /></button>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Username</label>
                  <input type="text" placeholder="Username" value={newAdmin.username} onChange={e => setNewAdmin(p => ({...p, username: e.target.value}))} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input type="email" placeholder="Email" value={newAdmin.email} onChange={e => setNewAdmin(p => ({...p, email: e.target.value}))} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
                  <input type="password" placeholder="Password" value={newAdmin.password} onChange={e => setNewAdmin(p => ({...p, password: e.target.value}))} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Role</label>
                  <select value={newAdmin.role} onChange={e => setNewAdmin(p => ({...p, role: e.target.value as Role['value']}))} className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                    {roles.filter(r => r.value !== 'super_admin').map(r => <option key={r.id} value={r.value}>{r.name}</option>)}
                  </select>
                </div>
              </div>
              <div className="bg-gray-50 p-4 rounded-lg">
                <h4 className="font-semibold text-gray-800 mb-2">Permissions</h4>
                <p className="text-sm text-gray-600">Permissions are inherited from the selected role. Custom permissions can be configured in the role editor.</p>
              </div>
            </div>
            <div className="flex justify-end space-x-4 mt-6 border-t pt-4">
              <button onClick={() => setShowAddForm(false)} className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
              <button onClick={handleAddAdmin} className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Add Administrator</button>
            </div>
          </div>
        )}

        {user?.role === 'super_admin' && showPasswordSettings && (
          <div className="bg-white rounded-lg shadow-sm p-6">
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-lg font-semibold text-gray-800">Password Security Settings</h3>
              <button onClick={() => setShowPasswordSettings(false)} className="text-gray-500 hover:text-gray-700"><X className="w-5 h-5" /></button>
            </div>
            {/* Password settings form fields */}
            <div className="flex justify-end mt-6">
              <button onClick={() => setShowPasswordSettings(false)} className="px-4 py-2 border rounded-lg">Cancel</button>
              <button onClick={handleSavePasswordSettings} className="px-4 py-2 bg-purple-600 text-white rounded-lg">Save Settings</button>
            </div>
          </div>
        )}

        <div className="bg-white rounded-lg shadow-sm">
          <div className="px-6 py-4 border-b border-gray-200">
            <h3 className="text-lg font-semibold text-gray-800">Static Role-Based Access Control (Static RBAC)</h3>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            {roles.map((role) => (
              <div key={role.id} className="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col">
                <div className="flex items-start justify-between mb-3">
                  <div>
                    <h4 className="font-bold text-gray-900">{role.name}</h4>
                    <span className="text-sm text-gray-500">{role.users} Users</span>
                  </div>
                  <div className="flex items-center space-x-1">
                    <button onClick={() => handleEditRole(role.id)} className="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="Edit"><Edit className="w-4 h-4" /></button>
                    {role.name !== 'Super Administrator' && (
                      <button onClick={() => handleDeleteRole(role.id)} className="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete"><Trash2 className="w-4 h-4" /></button>
                    )}
                  </div>
                </div>
                <div className="flex-grow">
                  <p className="text-xs text-gray-600 mb-2">{role.description}</p>
                  <p className="text-xs font-medium text-gray-800 mb-1">Permissions:</p>
                  {role.permissions[0] === '*' ? (
                    <span className="text-xs font-semibold text-red-600">All Permissions</span>
                  ) : (
                    <div className="flex flex-wrap gap-1">
                      {role.permissions.slice(0, 2).map(p => <span key={p} className="text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">{p}</span>)}
                      {role.permissions.length > 2 && <span className="text-xs bg-gray-200 text-gray-800 px-1.5 py-0.5 rounded">+{role.permissions.length - 2} more</span>}
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Administrator List Table */}
        {user?.role === 'super_admin' && (
          <div className="bg-white rounded-lg shadow-sm">
            <div className="px-6 py-4 border-b border-gray-200">
              <h3 className="text-lg font-semibold text-gray-800">Administrator List</h3>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {admins.map((admin) => (
                    <tr key={admin.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{admin.username}</td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{admin.email}</td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                          {admin.role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div className="flex items-center space-x-2">
                          <button onClick={() => handleViewAdmin(admin)} className="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="View Admin"><Eye className="w-4 h-4" /></button>
                          <button onClick={() => handleEditAdmin(admin)} className="p-1.5 rounded-md text-green-600 hover:bg-green-100" title="Edit Admin"><Edit className="w-4 h-4" /></button>
                          {admin.role !== 'super_admin' && <button onClick={() => handleDeleteAdmin(admin)} className="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete Admin"><Trash2 className="w-4 h-4" /></button>}
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </div>

      <Modal isOpen={isViewAdminModalOpen} onClose={() => setIsViewAdminModalOpen(false)} title={`View Administrator: ${selectedAdmin?.username}`}>
        {selectedAdmin && (
          <div className="space-y-3 text-sm">
            <p><strong>Username:</strong> {selectedAdmin.username}</p>
            <p><strong>Email:</strong> {selectedAdmin.email}</p>
            <p><strong>Role:</strong> {selectedAdmin.role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
            <p><strong>Password:</strong> {selectedAdmin.password || 'Not Set'}</p>
            <div className="flex justify-end pt-4">
              <button onClick={() => setIsViewAdminModalOpen(false)} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
            </div>
          </div>
        )}
      </Modal>

      <Modal isOpen={isEditAdminModalOpen} onClose={() => setIsEditAdminModalOpen(false)} title={`Edit Administrator: ${editedAdmin?.username}`}>
        {editedAdmin && (
          <form onSubmit={(e) => { e.preventDefault(); confirmEditAdmin(); }}>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value={editedAdmin.username} onChange={handleEditAdminFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value={editedAdmin.email} onChange={handleEditAdminFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" value={editedAdmin.role} onChange={handleEditAdminFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                  {roles.filter(r => r.value !== 'super_admin').map(r => <option key={r.id} value={r.value}>{r.name}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password" onChange={handleEditAdminFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
              </div>
            </div>
            <div className="flex justify-end space-x-4 pt-6 mt-4 border-t">
              <button type="button" onClick={() => setIsEditAdminModalOpen(false)} className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
              <button type="submit" className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
          </form>
        )}
      </Modal>

      <Modal isOpen={isDeleteAdminModalOpen} onClose={() => setIsDeleteAdminModalOpen(false)} title="Confirm Deletion">
        {selectedAdmin && (
          <div>
            <p>Are you sure you want to delete the administrator <strong>{selectedAdmin.username}</strong>? This action cannot be undone.</p>
            <div className="flex justify-end space-x-4 pt-6">
              <button onClick={() => setIsDeleteAdminModalOpen(false)} className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
              <button onClick={confirmDeleteAdmin} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
            </div>
          </div>
        )}
      </Modal>

      <Modal isOpen={isEditRoleModalOpen} onClose={() => setIsEditRoleModalOpen(false)} title={`Edit Role: ${selectedRole?.name}`}>
        {editedRole && (
          <form onSubmit={(e) => { e.preventDefault(); confirmEditRole(); }}>
            {/* Edit form can be built out here */}
            <div className="flex justify-end space-x-4 pt-6">
              <button type="button" onClick={() => setIsEditRoleModalOpen(false)} className="px-4 py-2 border rounded-lg">Cancel</button>
              <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
          </form>
        )}
      </Modal>
      <Modal isOpen={isDeleteRoleModalOpen} onClose={() => setIsDeleteRoleModalOpen(false)} title="Confirm Deletion">
        <p>Are you sure you want to delete role <strong>{selectedRole?.name}</strong>?</p>
        <div className="flex justify-end space-x-4 pt-6">
          <button onClick={() => setIsDeleteRoleModalOpen(false)} className="px-4 py-2 border rounded-lg">Cancel</button>
          <button onClick={confirmDeleteRole} className="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
        </div>
      </Modal>
    </Layout>
  );
};

export default Permissions;