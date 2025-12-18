import { Head, Link, router, usePage } from '@inertiajs/react'
import { Edit, Eye, Plus, Shield, Trash2 } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog.tsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table.tsx'

export default function RoleIndex({ roles, pagination }) {
  const { props } = usePage()
  const [deleteName, setDeleteName] = useState(null)

  const handleDelete = (name) => {
    router.post(`/role/${name}/delete`, {}, {
      onSuccess: () => {
        setDeleteName(null)
        toast.success('Role deleted successfully')
      },
      onError: () => {
        toast.error('Failed to delete role')
      },
    })
  }

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
      <Head title="Role Management" />

      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Role Management</h1>
            <p className="text-muted-foreground">
              Manage system roles and their permissions
            </p>
          </div>
          <Link href="/role/create">
            <Button>
              <Plus className="mr-2 h-4 w-4" />
              Create Role
            </Button>
          </Link>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Roles</CardTitle>
            <CardDescription>
              A list of all roles in the system
            </CardDescription>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Description</TableHead>
                  <TableHead>Rule</TableHead>
                  <TableHead>Created At</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {roles && roles.length > 0
                  ? (
                      roles.map(role => (
                        <TableRow key={role.name}>
                          <TableCell className="font-medium">
                            <div className="flex items-center gap-2">
                              <Shield className="h-4 w-4 text-blue-500" />
                              {role.name}
                            </div>
                          </TableCell>
                          <TableCell>{role.description || '-'}</TableCell>
                          <TableCell>
                            {role.rule_name
                              ? (
                                  <Badge variant="outline">{role.rule_name}</Badge>
                                )
                              : (
                                  '-'
                                )}
                          </TableCell>
                          <TableCell className="text-muted-foreground">
                            {formatDateTime(role.created_at)}
                          </TableCell>
                          <TableCell className="text-right">
                            <div className="flex items-center justify-end gap-2">
                              <Link href={`/role/${role.name}`}>
                                <Button size="sm" variant="ghost">
                                  <Eye className="h-4 w-4" />
                                </Button>
                              </Link>
                              <Link href={`/role/${role.name}/update`}>
                                <Button size="sm" variant="ghost">
                                  <Edit className="h-4 w-4" />
                                </Button>
                              </Link>
                              <Button
                                size="sm"
                                variant="ghost"
                                onClick={() => setDeleteName(role.name)}
                              >
                                <Trash2 className="h-4 w-4 text-destructive" />
                              </Button>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))
                    )
                  : (
                      <TableRow>
                        <TableCell colSpan={5} className="text-center text-muted-foreground">
                          No roles found
                        </TableCell>
                      </TableRow>
                    )}
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>

      {/* Delete Confirmation Dialog */}
      <AlertDialog open={!!deleteName} onOpenChange={() => setDeleteName(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Role</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete the role "
              {deleteName}
              "? This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction onClick={() => handleDelete(deleteName)}>
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </DashboardLayout>
  )
}

