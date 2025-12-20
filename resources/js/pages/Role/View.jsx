import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit, Shield } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Separator } from '@/components/ui/separator.tsx'

export default function RoleView({ role }) {
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
      <Head title={`Role: ${role.name}`} />

      <div className="space-y-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
            <CardTitle className="flex items-center gap-2">
              <Shield className="h-6 w-6 text-blue-500" />
              {role.name}
            </CardTitle>
            <div className="flex items-center gap-2">
              <Link href="/role">
                <Button variant="outline" size="sm">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </Link>
              <Link href={`/role/${role.name}/update`}>
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
                  <p className="text-base">{role.description || 'No description'}</p>
                </div>
                <Separator />
                <div>
                  <p className="text-sm font-medium text-muted-foreground">Rule</p>
                  {role.rule_name
                    ? (
                        <Badge variant="outline">{role.rule_name}</Badge>
                      )
                    : (
                        <p className="text-sm text-muted-foreground">No rule assigned</p>
                      )}
                </div>
                <Separator />
                <div>
                  <p className="text-sm font-medium text-muted-foreground">Created At</p>
                  <p className="text-base">{formatDateTime(role.created_at)}</p>
                </div>
                <Separator />
                <div>
                  <p className="text-sm font-medium text-muted-foreground">Updated At</p>
                  <p className="text-base">{formatDateTime(role.updated_at)}</p>
                </div>
              </div>

              <div>
                <p className="text-sm font-medium text-muted-foreground mb-2">Assigned Permissions</p>
                {role.children && role.children.length > 0
                  ? (
                      <div className="space-y-2">
                        {role.children.map(child => (
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
                            <Badge variant={child.type === 'role' ? 'default' : 'secondary'} className="text-[10px] px-1.5 h-4">
                              {child.type}
                            </Badge>
                          </div>
                        ))}
                      </div>
                    )
                  : (
                      <p className="text-sm text-muted-foreground">
                        No permissions or child roles assigned
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
