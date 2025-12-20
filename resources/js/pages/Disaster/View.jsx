import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

export default function DisasterView({ disaster }) {
  const { props } = usePage()

  const formatDate = (dateString) => {
    if (!dateString)
      return 'N/A'
    return new Date(dateString).toLocaleString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }

  const formatDateOnly = (dateString) => {
    if (!dateString)
      return 'N/A'
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  }

  const getStatusBadgeVariant = (status) => {
    const statusNum = typeof status === 'string' ? Number.parseInt(status) : status
    switch (statusNum) {
      case 1: // Active
        return 'destructive'
      case 2: // Resolved
        return 'outline'
      case 3: // Monitoring
        return 'secondary'
      default:
        return 'outline'
    }
  }

  const getTypeBadgeColor = (type) => {
    const typeNum = typeof type === 'string' ? Number.parseInt(type) : type
    const colors = {
      1: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
      2: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
      3: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
      4: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200',
      5: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
      6: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
      99: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    }
    return colors[typeNum] || 'bg-gray-100 text-gray-800'
  }

  return (
    <>
      <Head title={`Disaster: ${disaster.disaster_type_label}`} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
              <CardTitle>{disaster.disaster_type_label}</CardTitle>
              <div className="flex items-center gap-2">
                <Link href="/disasters">
                  <Button variant="outline" size="sm">
                    <ArrowLeft className="mr-2 h-4 w-4" />
                    Back
                  </Button>
                </Link>
                <Link href={`/disasters/${disaster.id}/edit`}>
                  <Button size="sm">
                    <Edit className="mr-2 h-4 w-4" />
                    Edit
                  </Button>
                </Link>
              </div>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-4 md:grid-cols-2">
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Disaster Type</label>
                  <div className="mt-1">
                    <Badge variant="outline" className={getTypeBadgeColor(disaster.disaster_type_id)}>
                      {disaster.disaster_type_label}
                    </Badge>
                  </div>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Status</label>
                  <div className="mt-1">
                    <Badge variant={getStatusBadgeVariant(disaster.disaster_status_id)}>
                      {disaster.disaster_status_label}
                    </Badge>
                  </div>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Start Date</label>
                  <p className="text-sm font-medium">{formatDateOnly(disaster.start_date)}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">End Date</label>
                  <p className="text-sm font-medium">{formatDateOnly(disaster.end_date)}</p>
                </div>
                <div className="md:col-span-2">
                  <label className="text-sm font-medium text-muted-foreground">Description</label>
                  <p className="text-sm font-medium mt-1 whitespace-pre-wrap">{disaster.description}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Created At</label>
                  <p className="text-sm font-medium">{formatDate(disaster.created_at)}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Updated At</label>
                  <p className="text-sm font-medium">{formatDate(disaster.updated_at)}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
