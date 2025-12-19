import { Head, usePage } from '@inertiajs/react'
import { Activity, DollarSign, TrendingUp, Users } from 'lucide-react'
import { CartesianGrid, Legend, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts'
import DashboardLayout from '@/components/layouts/DashboardLayout'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'

export default function Dashboard({ user, stats }) {
  const { props } = usePage()
  const defaultStats = {
    totalUsers: 0,
    revenue: 0,
    growth: 0,
    activeUsers: 0,
  }

  const dashboardStats = stats || defaultStats

  const statCards = [
    {
      title: 'Total Revenue',
      value: `$${dashboardStats.revenue.toLocaleString()}`,
      description: '+20.1% from last month',
      icon: DollarSign,
      trend: 'up',
    },
    {
      title: 'Active Users',
      value: dashboardStats.activeUsers.toLocaleString(),
      description: '+180.1% from last month',
      icon: Users,
      trend: 'up',
    },
    {
      title: 'Total Users',
      value: dashboardStats.totalUsers.toLocaleString(),
      description: '+19% from last month',
      icon: Users,
      trend: 'up',
    },
    {
      title: 'Growth',
      value: `${dashboardStats.growth}%`,
      description: '+12.5% from last month',
      icon: TrendingUp,
      trend: 'up',
    },
  ]

  // Sample chart data
  const chartData = [
    { name: 'Jan', value: 400 },
    { name: 'Feb', value: 300 },
    { name: 'Mar', value: 200 },
    { name: 'Apr', value: 278 },
    { name: 'May', value: 189 },
    { name: 'Jun', value: 239 },
  ]

  return (
    <>
      <Head title="Dashboard | crenspire/yii2-react-starter" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p className="text-muted-foreground">
              Welcome back,
              {' '}
              {user?.username || 'User'}
              !
            </p>
          </div>

          {/* Stats Grid */}
          <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            {statCards.map((stat) => {
              const Icon = stat.icon
              return (
                <Card key={stat.title}>
                  <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle className="text-sm font-medium">
                      {stat.title}
                    </CardTitle>
                    <Icon className="h-4 w-4 text-muted-foreground" />
                  </CardHeader>
                  <CardContent>
                    <div className="text-2xl font-bold">{stat.value}</div>
                    <p className="text-xs text-muted-foreground">
                      {stat.description}
                    </p>
                  </CardContent>
                </Card>
              )
            })}
          </div>

          {/* Chart */}
          <Card>
            <CardHeader>
              <CardTitle>Analytics Overview</CardTitle>
              <CardDescription>
                Your performance metrics over time
              </CardDescription>
            </CardHeader>
            <CardContent>
              <ResponsiveContainer width="100%" height={300}>
                <LineChart data={chartData}>
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="name" />
                  <YAxis />
                  <Tooltip />
                  <Legend />
                  <Line type="monotone" dataKey="value" stroke="hsl(var(--primary))" />
                </LineChart>
              </ResponsiveContainer>
            </CardContent>
          </Card>

          {/* Recent Activity */}
          <Card>
            <CardHeader>
              <CardTitle>Recent Activity</CardTitle>
              <CardDescription>
                Your recent activity and updates
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                <div className="flex items-center space-x-4">
                  <Activity className="h-4 w-4 text-muted-foreground" />
                  <div className="flex-1 space-y-1">
                    <p className="text-sm font-medium">Welcome to your dashboard</p>
                    <p className="text-sm text-muted-foreground">
                      Get started by exploring the features
                    </p>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
