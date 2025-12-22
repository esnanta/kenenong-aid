import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit, Key } from 'lucide-react'
import {DashboardLayout} from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card.tsx'
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
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
            <CardTitle className="flex items-center gap-2">
              <Key className="h-6 w-6 text-green-500" />
              {permission.name}
            </CardTitle>
            <div className="flex items-center gap-2">
              <Link href="/permission">
                <Button variant="outline" size="sm">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </Link>
              <Link href={`/permissions/${permission.name}/update`}>
                <Button size="sm">
                  <Edit className="mr-2 h-4 w-4" />
                  Edit
                </Button>
              </Link>
            </div>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="grid gap-6 md:grid-cols-2">
              <div className="space-y-4">
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
              </div>

              <div>
                <p className="text-sm font-medium text-muted-foreground mb-2">Child Permissions</p>
                {permission.children && permission.children.length > 0
                  ? (
                      <div className="space-y-2">
                        {permission.children.map(child => (
                          <div
                            key={child.name}
                            className="flex items-center justify-between p-2 rounded-md border"
                          >
                            <div>
                              <p className="font-medium text-sm">{child.name}</p>
                              {child.description && (
                                <p className="text-xs text-muted-foreground">{child.description}</p>
                              )}
                            </div>
                            <Badge variant="secondary" className="text-[10px] px-1.5 h-4">
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
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  )
}
