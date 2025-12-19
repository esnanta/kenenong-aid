import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'

export default function UserView({ user }) {
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

  return (
    <>
      <Head title={`User: ${user.name}`} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
              <Link href="/user">
                <Button variant="ghost" size="icon">
                  <ArrowLeft className="h-4 w-4" />
                </Button>
              </Link>
              <div>
                <h1 className="text-3xl font-bold tracking-tight">{user.name}</h1>
                <p className="text-muted-foreground">User Details</p>
              </div>
            </div>
            <Link href={`/user/${user.id}/edit`}>
              <Button>
                <Edit className="mr-2 h-4 w-4" />
                Edit User
              </Button>
            </Link>
          </div>

          <Card>
            <CardHeader>
              <CardTitle>User Information</CardTitle>
              <CardDescription>
                Detailed information about the user
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-4 md:grid-cols-2">
                <div>
                  <label className="text-sm font-medium text-muted-foreground">ID</label>
                  <p className="text-sm font-medium">{user.id}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Name</label>
                  <p className="text-sm font-medium">{user.name}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Email</label>
                  <p className="text-sm font-medium">{user.email}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Email Verified</label>
                  <p className="text-sm font-medium">
                    {user.email_verified_at
                      ? (
                          <span className="text-green-600">Verified</span>
                        )
                      : (
                          <span className="text-muted-foreground">Not verified</span>
                        )}
                  </p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Created At</label>
                  <p className="text-sm font-medium">{formatDate(user.created_at)}</p>
                </div>
                <div>
                  <label className="text-sm font-medium text-muted-foreground">Updated At</label>
                  <p className="text-sm font-medium">{formatDate(user.updated_at)}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
