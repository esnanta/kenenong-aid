import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit } from 'lucide-react'
import {DashboardLayout} from '@/components/layouts/DashboardLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

export default function DisasterStatusView({ status }) {
  const { props } = usePage()

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    return new Date(dateString).toLocaleString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }

  return (
    <>
      <Head title={`Disaster Status: ${status.title}`} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
              <CardTitle>{status.title}</CardTitle>
              <div className="flex items-center gap-2">
                <Link href="/disaster-statuses">
                  <Button variant="outline" size="sm">
                    <ArrowLeft className="mr-2 h-4 w-4" />
                    Back
                  </Button>
                </Link>
                <Link href={`/disaster-statuses/${status.id}/edit`}>
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
                  <label className="text-sm font-medium text-muted-foreground">Code</label>
                  <p className="text-sm font-medium">{status.code}</p>
                </div>
                <div className="md:col-span-2">
                  <label className="text-sm font-medium text-muted-foreground">Title</label>
                  <p className="text-sm font-medium">{status.title}</p>
                </div>
                <div className="md:col-span-2">
                  <label className="text-sm font-medium text-muted-foreground">Description</label>
                  <p className="text-sm font-medium mt-1 whitespace-pre-wrap">{status.description || 'No description'}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Created At</label>
                  <p className="text-sm font-medium">{formatDate(status.created_at)}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Updated At</label>
                  <p className="text-sm font-medium">{formatDate(status.updated_at)}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
