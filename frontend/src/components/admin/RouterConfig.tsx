import React, { useState, useEffect } from 'react';
import { 
  Router, 
  Plus, 
  Edit, 
  Trash2, 
  RotateCcw, 
  Power, 
  Save, 
  Clock, 
  Users, 
  ArrowLeftRight, 
  DatabaseBackup,
  Wifi,
  Network,
  Globe,
  Server
} from 'lucide-react';
import Layout from './Layout';
import Modal from '../common/Modal';
import toast from 'react-hot-toast';
import { useSettings } from '../../contexts/SettingsContext';

type Interface = {
  name: string;
  mac: string;
  type: 'Ethernet' | 'Wireless';
  ip: string;
  status: 'running' | 'disabled';
  rx: string;
  tx: string;
};

type Profile = {
  name: string;
  sessionTimeout: string;
  idleTimeout: string;
  sharedUsers: number;
  rateLimit: string;
  status: 'active' | 'inactive';
};

const RouterConfig = () => {
  const { apiKeys, updateApiKeys, saveSettingsToBackend, fetchSettingsFromBackend } = useSettings();
  const [isOnlineMode, setIsOnlineMode] = useState(true); // This state will determine which host/port to display

  // Fetch settings on component mount
  useEffect(() => {
    fetchSettingsFromBackend();
  }, [fetchSettingsFromBackend]);

  const [interfaces, setInterfaces] = useState<Interface[]>([
    { name: 'ether1-gateway', mac: 'D4:CA:6D:11:22:31', type: 'Ethernet', ip: '10.0.0.15/24', status: 'running', rx: '15.7 GB', tx: '4.2 GB' },
    { name: 'ether2-master', mac: 'D4:CA:6D:11:22:32', type: 'Ethernet', ip: '192.168.88.1/24', status: 'running', rx: '3.1 GB', tx: '8.9 GB' },
    { name: 'ether3-slave', mac: 'D4:CA:6D:11:22:33', type: 'Ethernet', ip: '-', status: 'running', rx: '1.2 GB', tx: '500 MB' },
    { name: 'ether4-slave', mac: 'D4:CA:6D:11:22:34', type: 'Ethernet', ip: '-', status: 'disabled', rx: '0 B', tx: '0 B' },
    { name: 'ether5-slave', mac: 'D4:CA:6D:11:22:35', type: 'Ethernet', ip: '-', status: 'running', rx: '800 MB', tx: '250 MB' }
  ]);

  const [profiles] = useState<Profile[]>([
    { name: 'Default', sessionTimeout: '1h', idleTimeout: '30m', sharedUsers: 1, rateLimit: '2M/1M', status: 'active' },
    { name: 'Premium', sessionTimeout: '4h', idleTimeout: '1h', sharedUsers: 2, rateLimit: '10M/5M', status: 'active' },
    { name: 'Guest', sessionTimeout: '30m', idleTimeout: '15m', sharedUsers: 1, rateLimit: '1M/512K', status: 'inactive' }
  ]);

  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
  const [selectedInterface, setSelectedInterface] = useState<Interface | null>(null);
  const [editedInterface, setEditedInterface] = useState<Interface | null>(null);

  const handleMikrotikChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    // Update the corresponding API key in the context
    updateApiKeys({ [`mikrotik${name.charAt(0).toUpperCase() + name.slice(1)}`]: value });
  };

  const handleSaveMikrotikConfig = async () => {
    // Use the values directly from apiKeys context
    if (!apiKeys.mikrotikHost || !apiKeys.mikrotikPort || !apiKeys.mikrotikUsername || !apiKeys.mikrotikPassword) {
      toast.error('Please fill all MikroTik connection fields.');
      return;
    }
    const toastId = toast.loading('Saving MikroTik configuration...');
    try {
      // Call the global save settings function
      await saveSettingsToBackend();
      toast.success('MikroTik configuration saved successfully!', { id: toastId });
    } catch (error) {
      toast.error('Failed to save configuration.', { id: toastId });
    }
  };

  const handleTestConnection = async () => {
    // Use the values directly from apiKeys context
    if (!apiKeys.mikrotikHost || !apiKeys.mikrotikPort || !apiKeys.mikrotikUsername || !apiKeys.mikrotikPassword) {
      toast.error('Please fill all MikroTik connection fields before testing.');
      return;
    }
    const toastId = toast.loading('Testing connection...');
    try {
      console.log('Testing connection with:', {
        host: apiKeys.mikrotikHost,
        port: apiKeys.mikrotikPort,
        username: apiKeys.mikrotikUsername,
        password: apiKeys.mikrotikPassword,
      });
      // Simulate API call to MikroTik
      await new Promise(resolve => setTimeout(resolve, 2000));
      toast.success('Connection to MikroTik successful!', { id: toastId });
    } catch (error) {
      toast.error('Connection failed. Please check settings.', { id: toastId });
    }
  };

  const handleEditInterface = (ifaceName: string) => {
    const iface = interfaces.find(i => i.name === ifaceName);
    if (iface) {
      setSelectedInterface(iface);
      setEditedInterface({ ...iface });
      setIsEditModalOpen(true);
    }
  };

  const handleDeleteInterface = (ifaceName: string) => {
    const iface = interfaces.find(i => i.name === ifaceName);
    if (iface) {
      setSelectedInterface(iface);
      setIsDeleteModalOpen(true);
    }
  };

  const confirmEditInterface = () => {
    if (editedInterface) {
      // Here you would call the API to update the interface
      toast.success(`Interface ${editedInterface.name} updated successfully!`);
      // Update the state to reflect changes
      setInterfaces(prev => prev.map(i => i.name === editedInterface.name ? editedInterface : i));
      setIsEditModalOpen(false);
    }
  };

  const confirmDeleteInterface = () => {
    if (selectedInterface) {
      // Here you would call the API to delete the interface
      toast.success(`Interface ${selectedInterface.name} deleted successfully!`);
      // Update the state to reflect changes
      setInterfaces(prev => prev.filter(i => i.name !== selectedInterface.name));
      setIsDeleteModalOpen(false);
    }
  };

  const handleEditFormChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    if (editedInterface) {
      setEditedInterface({
        ...editedInterface,
        [e.target.name]: e.target.value,
      });
    }
  };

  return (
    <Layout>
      <div className="space-y-8">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Router Configuration</h1>
          <p className="text-gray-600">Configure and monitor your MikroTik Router</p>
        </div>

        {/* --- START: MikroTik Connection Settings --- */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <h3 className="text-lg font-semibold text-gray-800 flex items-center">
              <Router className="w-5 h-5 mr-2 text-blue-600" />
              MikroTik API Connection
            </h3>
            <div className="flex items-center bg-gray-200 rounded-lg p-1">
              <button
                onClick={() => setIsOnlineMode(true)}
                className={`px-4 py-1.5 text-sm font-medium rounded-md flex items-center transition-colors ${isOnlineMode ? 'bg-white text-blue-600 shadow' : 'text-gray-600 hover:bg-gray-300'}`}
              >
                <Globe className="w-4 h-4 mr-2" />
                Online (Hosting)
              </button>
              <button
                onClick={() => setIsOnlineMode(false)}
                className={`px-4 py-1.5 text-sm font-medium rounded-md flex items-center transition-colors ${!isOnlineMode ? 'bg-white text-blue-600 shadow' : 'text-gray-600 hover:bg-gray-300'}`}
              >
                <Server className="w-4 h-4 mr-2" />
                Offline (Lokal)
              </button>
            </div>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                {isOnlineMode ? 'Host (IP Publik / DDNS)' : 'Host (IP Lokal)'}
              </label>
              <input type="text" name="host" value={apiKeys.mikrotikHost} onChange={handleMikrotikChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder={isOnlineMode ? 'e.g., 123.45.67.89' : 'e.g., 192.168.1.1'} />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">API Port</label>
              <input type="text" name="port" value={apiKeys.mikrotikPort} onChange={handleMikrotikChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="e.g., 8728" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Username</label>
              <input type="text" name="username" value={apiKeys.mikrotikUsername} onChange={handleMikrotikChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input type="password" name="password" value={apiKeys.mikrotikPassword} onChange={handleMikrotikChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div className="flex justify-end items-center mt-6 space-x-4">
            <button onClick={handleTestConnection} className="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-100">Test Connection</button>
            <button
              onClick={handleSaveMikrotikConfig}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700"
            >
              <Save className="w-4 h-4" />
              <span>Save Connection Settings</span>
            </button>
          </div>
        </div>
        {/* --- END: MikroTik Connection Settings --- */}

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
            <h3 className="text-base font-semibold text-blue-600 uppercase tracking-wider">Connection Status</h3>
            <div className="flex items-center space-x-2">
              <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
              <span className="font-semibold text-green-600">Connected</span>
            </div>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div className="flex items-center justify-center space-x-4 p-4">
              <Clock className="w-10 h-10 text-blue-500" />
              <div>
                <p className="text-sm text-gray-500">Uptime</p>
                <p className="text-xl font-bold text-gray-800">2d 14h 32m</p>
              </div>
            </div>
            <div className="flex items-center justify-center space-x-4 p-4">
              <Users className="w-10 h-10 text-green-500" />
              <div>
                <p className="text-sm text-gray-500">Active Users</p>
                <p className="text-xl font-bold text-gray-800">89</p>
              </div>
            </div>
            <div className="flex items-center justify-center space-x-4 p-4">
              <ArrowLeftRight className="w-10 h-10 text-purple-500" />
              <div>
                <p className="text-sm text-gray-500">Bandwidth Usage</p>
                <p className="text-xl font-bold text-gray-800">67 Mbps / 100 Mbps</p>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
            <h3 className="text-lg font-semibold text-gray-800">Router Status & Management</h3>
            <div className="flex space-x-2">
              <button className="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><DatabaseBackup className="w-4 h-4" /><span>Backup</span></button>
              <button className="px-4 py-2 bg-orange-500 text-white rounded-lg flex items-center space-x-2 hover:bg-orange-600 text-sm"><RotateCcw className="w-4 h-4" /><span>Restart Hotspot</span></button>
              <button className="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center space-x-2 hover:bg-red-700 text-sm"><Power className="w-4 h-4" /><span>Reboot</span></button>
            </div>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
              <div className="flex justify-between items-center mb-1">
                <span className="text-sm font-medium text-gray-600">CPU Usage</span>
                <span className="text-sm font-bold text-blue-600">23%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2"><div className="bg-blue-600 h-2 rounded-full" style={{ width: '23%' }}></div></div>
            </div>
            <div>
              <div className="flex justify-between items-center mb-1">
                <span className="text-sm font-medium text-gray-600">Memory Usage</span>
                <span className="text-sm font-bold text-green-600">45%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2"><div className="bg-green-600 h-2 rounded-full" style={{ width: '45%' }}></div></div>
            </div>
            <div>
              <div className="flex justify-between items-center mb-1">
                <span className="text-sm font-medium text-gray-600">Temperature</span>
                <span className="text-sm font-bold text-orange-600">42°C</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2"><div className="bg-orange-500 h-2 rounded-full" style={{ width: '42%' }}></div></div>
            </div>
            <div>
              <div className="flex justify-between items-center mb-1">
                <span className="text-sm font-medium text-gray-600">Disk Usage</span>
                <span className="text-sm font-bold text-purple-600">12%</span>
              </div>
              <div className="w-full bg-gray-200 rounded-full h-2"><div className="bg-purple-600 h-2 rounded-full" style={{ width: '12%' }}></div></div>
            </div>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex justify-between items-center mb-4">
            <h3 className="text-lg font-semibold text-gray-800">Network Interfaces</h3>
            <button className="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><Plus className="w-4 h-4" /><span>Add Interface</span></button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interface</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Traffic</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {interfaces.map((iface) => (
                  <tr key={iface.name}>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="font-medium text-gray-900">{iface.name}</div>
                      <div className="text-xs text-gray-500">{iface.mac}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center space-x-2">
                        {iface.type === 'Wireless' ? <Wifi className="w-4 h-4 text-gray-500" /> : <Network className="w-4 h-4 text-gray-500" />}
                        <span>{iface.type}</span>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-gray-800">{iface.ip}</td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-gray-800">RX: {iface.rx}</div>
                      <div className="text-xs text-gray-500">TX: {iface.tx}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap"><span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${iface.status === 'running' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>{iface.status}</span></td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center space-x-2">
                        <button onClick={() => handleEditInterface(iface.name)} className="p-1.5 rounded-md text-blue-600 hover:bg-blue-100" title="Edit Interface"><Edit className="w-4 h-4" /></button>
                        <button onClick={() => handleDeleteInterface(iface.name)} className="p-1.5 rounded-md text-red-600 hover:bg-red-100" title="Delete Interface"><Trash2 className="w-4 h-4" /></button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow-sm p-6">
          <div className="flex justify-between items-center mb-6">
            <h3 className="text-lg font-semibold text-gray-800">Hotspot Profiles</h3>
            <button className="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700 text-sm"><Plus className="w-4 h-4" /><span>Add Profile</span></button>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {profiles.map((profile) => (
              <div key={profile.name} className="border rounded-lg p-4 flex flex-col">
                <div className="flex justify-between items-center mb-4">
                  <h4 className="font-bold text-gray-800">{profile.name}</h4>
                  <span className={`px-2 py-0.5 text-xs font-semibold rounded-full ${profile.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>{profile.status}</span>
                </div>
                <div className="space-y-2 text-sm text-gray-600 flex-grow">
                  <div className="flex justify-between"><span>Session Timeout:</span> <span className="font-medium text-gray-800">{profile.sessionTimeout}</span></div>
                  <div className="flex justify-between"><span>Idle Timeout:</span> <span className="font-medium text-gray-800">{profile.idleTimeout}</span></div>
                  <div className="flex justify-between"><span>Shared Users:</span> <span className="font-medium text-gray-800">{profile.sharedUsers}</span></div>
                  <div className="flex justify-between"><span>Rate Limit:</span> <span className="font-medium text-gray-800">{profile.rateLimit}</span></div>
                </div>
                <div className="flex justify-end space-x-2 mt-4">
                  <button className="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">Edit</button>
                  <button className="px-3 py-1 border border-red-500 text-red-500 rounded-md text-sm hover:bg-red-50">Delete</button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <Modal isOpen={isEditModalOpen} onClose={() => setIsEditModalOpen(false)} title={`Edit Interface: ${selectedInterface?.name}`}>
        {editedInterface && (
          <form onSubmit={(e) => { e.preventDefault(); confirmEditInterface(); }}>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Interface Name</label>
                <input type="text" value={editedInterface.name} readOnly className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">IP Address (CIDR)</label>
                <input type="text" name="ip" value={editedInterface.ip} onChange={handleEditFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" value={editedInterface.status} onChange={handleEditFormChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white">
                  <option value="running">Running</option>
                  <option value="disabled">Disabled</option>
                </select>
              </div>
            </div>
            <div className="flex justify-end space-x-4 pt-6 mt-4 border-t">
              <button type="button" onClick={() => setIsEditModalOpen(false)} className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
              <button type="submit" className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Changes</button>
            </div>
          </form>
        )}
      </Modal>

      <Modal isOpen={isDeleteModalOpen} onClose={() => setIsDeleteModalOpen(false)} title="Confirm Deletion">
        {selectedInterface && (
          <div>
            <p>Are you sure you want to delete the interface <strong>{selectedInterface.name}</strong>? This action cannot be undone.</p>
            <div className="flex justify-end space-x-4 pt-6">
              <button onClick={() => setIsDeleteModalOpen(false)} className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
              <button onClick={confirmDeleteInterface} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Delete</button>
            </div>
          </div>
        )}
      </Modal>
    </Layout>
  );
};

export default RouterConfig;