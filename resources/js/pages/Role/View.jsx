import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Shield } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
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
        <div className="flex items-center gap-4">
          <Link href="/role">
            <Button variant="ghost" size="icon">
              <ArrowLeft className="h-4 w-4" />
            </Button>
          </Link>
          <div className="flex-1">
            <h1 className="text-3xl font-bold tracking-tight flex items-center gap-2">
              <Shield className="h-8 w-8 text-blue-500" />
              {role.name}
            </h1>
            <p className="text-muted-foreground">Role details and permissions</p>
          </div>
          <Link href={`/role/${role.name}/update`}>
            <Button>Edit Role</Button>
          </Link>
        </div>

        <div className="grid gap-6 md:grid-cols-2">
          <Card>
            <CardHeader>
              <CardTitle>Role Information</CardTitle>
              <CardDescription>Basic details about this role</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Name</p>
                <p className="text-base font-semibold">{role.name}</p>
              </div>
              <Separator />
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
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Assigned Permissions</CardTitle>
              <CardDescription>
                Permissions and child roles assigned to this role
              </CardDescription>
            </CardHeader>
            <CardContent>
              {role.children && role.children.length > 0
                ? (
                    <div className="space-y-2">
                      {role.children.map(child => (
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
                          <Badge variant={child.type === 'role' ? 'default' : 'secondary'}>
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
            </CardContent>
          </Card>
        </div>
      </div>
    </DashboardLayout>
  )
}
