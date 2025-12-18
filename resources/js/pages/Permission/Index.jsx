import { Head, Link, router, usePage } from '@inertiajs/react'
import { Edit, Eye, Key, Plus, Trash2 } from 'lucide-react'
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

export default function PermissionIndex({ permissions, pagination }) {
  const { props } = usePage()
  const [deleteName, setDeleteName] = useState(null)

  const handleDelete = (name) => {
    router.post(`/permission/${name}/delete`, {}, {
      onSuccess: () => {
        setDeleteName(null)
        toast.success('Permission deleted successfully')
      },
      onError: () => {
        toast.error('Failed to delete permission')
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
      <Head title="Permission Management" />

      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Permission Management</h1>
            <p className="text-muted-foreground">
              Manage system permissions
            </p>
          </div>
          <Link href="/permission/create">
            <Button>
              <Plus className="mr-2 h-4 w-4" />
              Create Permission
            </Button>
          </Link>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Permissions</CardTitle>
            <CardDescription>
              A list of all permissions in the system
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
                {permissions && permissions.length > 0
                  ? (
                      permissions.map(permission => (
                        <TableRow key={permission.name}>
                          <TableCell className="font-medium">
                            <div className="flex items-center gap-2">
                              <Key className="h-4 w-4 text-green-500" />
                              {permission.name}
                            </div>
                          </TableCell>
                          <TableCell>{permission.description || '-'}</TableCell>
                          <TableCell>
                            {permission.rule_name
                              ? (
                                  <Badge variant="outline">{permission.rule_name}</Badge>
                                )
                              : (
                                  '-'
                                )}
                          </TableCell>
                          <TableCell className="text-muted-foreground">
                            {formatDateTime(permission.created_at)}
                          </TableCell>
                          <TableCell className="text-right">
                            <div className="flex items-center justify-end gap-2">
                              <Link href={`/permission/${permission.name}`}>
                                <Button size="sm" variant="ghost">
                                  <Eye className="h-4 w-4" />
                                </Button>
                              </Link>
                              <Link href={`/permission/${permission.name}/update`}>
                                <Button size="sm" variant="ghost">
                                  <Edit className="h-4 w-4" />
                                </Button>
                              </Link>
                              <Button
                                size="sm"
                                variant="ghost"
                                onClick={() => setDeleteName(permission.name)}
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
                          No permissions found
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
            <AlertDialogTitle>Delete Permission</AlertDialogTitle>
            <AlertDialogDescription>
              Are you sure you want to delete the permission "
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
