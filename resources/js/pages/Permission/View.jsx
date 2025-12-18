import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Key } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Separator } from '@/components/ui/separator.tsx'

export default function PermissionView({ permission }) {
  const { props } = usePage()
  const formatDateTime = (dateString) => {
    if (!dateString)
      return 'N/A'
    return new Date(dateString).toLocaleString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }
  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={`Permission: ${permission.name}`} />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/permission">
            <Button variant="ghost" size="icon">
              <ArrowLeft className="h-4 w-4" />
            </Button>
          </Link>
          <div className="flex-1">
            <h1 className="text-3xl font-bold tracking-tight flex items-center gap-2">
              <Key className="h-8 w-8 text-green-500" />
              {permission.name}
            </h1>
            <p className="text-muted-foreground">Permission details</p>
          </div>
          <Link href={`/permission/${permission.name}/update`}>
            <Button>Edit Permission</Button>
          </Link>
        </div>
        <div className="grid gap-6 md:grid-cols-2">
          <Card>
            <CardHeader>
              <CardTitle>Permission Information</CardTitle>
              <CardDescription>Basic details about this permission</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Name</p>
                <p className="text-base font-semibold">{permission.name}</p>
              </div>
              <Separator />
              <div>
                <p className="text-sm font-medium text-muted-foreground">Description</p>
                <p className="text-base">{permission.description || 'No description'}</p>
              </div>
              <Separator />
              <div>
                <p className="text-sm font-medium text-muted-foreground">Rule</p>
                {permission.rule_name
                  ? (
                      <Badge variant="outline">{permission.rule_name}</Badge>
                    )
                  : (
                      <p className="text-sm text-muted-foreground">No rule assigned</p>
                    )}
              </div>
              <Separator />
              <div>
                <p className="text-sm font-medium text-muted-foreground">Created At</p>
                <p className="text-base">{formatDateTime(permission.created_at)}</p>
              </div>
              <Separator />
              <div>
                <p className="text-sm font-medium text-muted-foreground">Updated At</p>
                <p className="text-base">{formatDateTime(permission.updated_at)}</p>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardHeader>
              <CardTitle>Child Permissions</CardTitle>
              <CardDescription>
                Child permissions assigned to this permission
              </CardDescription>
            </CardHeader>
            <CardContent>
              {permission.children && permission.children.length > 0
                ? (
                    <div className="space-y-2">
                      {permission.children.map(child => (
                        <div
                          key={child.name}
                          className="flex items-center justify-between p-2 rounded-md border"
                        >
                          <div>
                            <p className="font-medium">{child.name}</p>
                            {child.description && (
                              <p className="text-sm text-muted-foreground">{child.description}</p>
                            )}
                          </div>
                          <Badge variant="secondary">
                            {child.type}
                          </Badge>
                        </div>
                      ))}
                    </div>
                  )
                : (
                    <p className="text-sm text-muted-foreground">
                      No child permissions assigned
                    </p>
                  )}
            </CardContent>
          </Card>
        </div>
      </div>
    </DashboardLayout>
  )
}
