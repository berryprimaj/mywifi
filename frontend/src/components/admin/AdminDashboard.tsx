import { Users, Wifi, HardDrive, DollarSign, TrendingUp } from 'lucide-react';
import Layout from './Layout';
import { LineChart, Line, BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer } from 'recharts';

const AdminDashboard = () => {
  const stats = [
    {
      title: 'Total Users',
      value: '1,234',
      change: '+12% from yesterday',
      icon: Users,
      color: 'bg-blue-500',
      textColor: 'text-blue-600'
    },
    {
      title: 'Active Sessions',
      value: '89',
      change: '+5% from yesterday',
      icon: Wifi,
      color: 'bg-green-500',
      textColor: 'text-green-600'
    },
    {
      title: 'Data Usage',
      value: '2.4 TB',
      change: '+16% from yesterday',
      icon: HardDrive,
      color: 'bg-purple-500',
      textColor: 'text-purple-600'
    },
    {
      title: 'Revenue Today',
      value: '$156',
      change: '+8% from yesterday',
      icon: DollarSign,
      color: 'bg-orange-500',
      textColor: 'text-orange-600'
    }
  ];

  const recentActivity = [
    { user: 'John Doe', action: 'Connected via WhatsApp', time: '2 minutes ago', status: 'online' },
    { user: 'Jane Smith', action: 'Logged in with Google', time: '5 minutes ago', status: 'online' },
    { user: 'Bob Johnson', action: 'Disconnected', time: '10 minutes ago', status: 'offline' },
    { user: 'Alice Brown', action: 'Member login', time: '15 minutes ago', status: 'online' },
  ];

  // Sample data for charts
  const userActivityData = [
    { day: 'Mon', users: 65, sessions: 45 },
    { day: 'Tue', users: 78, sessions: 52 },
    { day: 'Wed', users: 90, sessions: 61 },
    { day: 'Thu', users: 81, sessions: 58 },
    { day: 'Fri', users: 95, sessions: 65 },
    { day: 'Sat', users: 115, sessions: 78 },
    { day: 'Sun', users: 88, sessions: 62 }
  ];

  const dailySessionsData = [
    { day: 'Mon', sessions: 45 },
    { day: 'Tue', sessions: 52 },
    { day: 'Wed', sessions: 61 },
    { day: 'Thu', sessions: 58 },
    { day: 'Fri', sessions: 65 },
    { day: 'Sat', sessions: 78 },
    { day: 'Sun', sessions: 62 }
  ];

  return (
    <Layout>
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-800">Dashboard</h1>
          <p className="text-gray-600">Welcome to MyHotspot management system</p>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {stats.map((stat) => (
            <div key={stat.title} className="bg-white rounded-lg shadow-sm p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">{stat.title}</p>
                  <p className="text-2xl font-bold text-gray-800">{stat.value}</p>
                </div>
                <div className={`p-3 rounded-full ${stat.color}`}>
                  <stat.icon className="w-6 h-6 text-white" />
                </div>
              </div>
              <div className="mt-4">
                <span className={`text-sm ${stat.textColor} flex items-center`}>
                  <TrendingUp className="w-4 h-4 mr-1" />
                  {stat.change}
                </span>
              </div>
            </div>
          ))}
        </div>

        {/* Charts Section */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4">User Activity</h3>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={userActivityData} margin={{ top: 5, right: 20, left: -10, bottom: 5 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e0e0e0" />
                  <XAxis dataKey="day" tick={{ fill: '#6b7280', fontSize: 12 }} />
                  <YAxis tick={{ fill: '#6b7280', fontSize: 12 }} />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: 'white',
                      border: '1px solid #e5e7eb',
                      borderRadius: '0.5rem',
                    }}
                  />
                  <Legend />
                  <Line type="monotone" dataKey="users" stroke="#3b82f6" strokeWidth={2} activeDot={{ r: 6 }} name="Users" />
                  <Line type="monotone" dataKey="sessions" stroke="#10b981" strokeWidth={2} activeDot={{ r: 6 }} name="Sessions" />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4">Daily Sessions</h3>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={dailySessionsData} margin={{ top: 5, right: 20, left: -10, bottom: 5 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e0e0e0" />
                  <XAxis dataKey="day" tick={{ fill: '#6b7280', fontSize: 12 }} />
                  <YAxis tick={{ fill: '#6b7280', fontSize: 12 }} />
                  <Tooltip
                    contentStyle={{
                      backgroundColor: 'white',
                      border: '1px solid #e5e7eb',
                      borderRadius: '0.5rem',
                    }}
                    cursor={{fill: 'rgba(139, 92, 246, 0.1)'}}
                  />
                  <Bar dataKey="sessions" fill="#8b5cf6" name="Sessions" radius={[4, 4, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>

        {/* Recent Activity */}
        <div className="bg-white rounded-lg shadow-sm p-6">
          <h3 className="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
          <div className="space-y-4">
            {recentActivity.map((activity, index) => (
              <div key={index} className="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                <div className={`w-3 h-3 rounded-full ${activity.status === 'online' ? 'bg-green-500' : 'bg-gray-400'}`}></div>
                <div className="flex-1">
                  <p className="font-medium text-gray-800">{activity.user}</p>
                  <p className="text-sm text-gray-600">{activity.action}</p>
                </div>
                <p className="text-sm text-gray-500">{activity.time}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default AdminDashboard;