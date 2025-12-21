import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ArrowUpDown, ChevronDown, ChevronUp, Columns2, Edit, Eye, Filter, Plus, Search, Shield, Trash2, X } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import { DashboardLayout } from '@/components/layouts/DashboardLayout.jsx'
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
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table.tsx'

function SortableHeader({ column, children, currentSortBy, currentSortOrder, handleSort }) {
  const isSorted = currentSortBy === column
  const sortIcon = isSorted
    ? (currentSortOrder === 'asc' ? <ArrowUp className="ml-2 h-4 w-4" /> : <ArrowDown className="ml-2 h-4 w-4" />)
    : <ArrowUpDown className="ml-2 h-4 w-4 opacity-50" />

  return (
    <TableHead className="cursor-pointer hover:bg-muted/50" onClick={() => handleSort(column)}>
      <div className="flex items-center">
        {children}
        {sortIcon}
      </div>
    </TableHead>
  )
}

function ColumnVisibilityDropdown({ columnVisibility, setColumnVisibility }) {
  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="sm">
          <Columns2 className="mr-2 h-4 w-4" />
          Columns
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" className="w-48">
        <DropdownMenuLabel>Toggle Columns</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuCheckboxItem
          checked={columnVisibility.name}
          onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, name: checked })}
        >
          Name
        </DropdownMenuCheckboxItem>
        <DropdownMenuCheckboxItem
          checked={columnVisibility.description}
          onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, description: checked })}
        >
          Description
        </DropdownMenuCheckboxItem>
        <DropdownMenuCheckboxItem
          checked={columnVisibility.ruleName}
          onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, ruleName: checked })}
        >
          Rule Name
        </DropdownMenuCheckboxItem>
        <DropdownMenuCheckboxItem
          checked={columnVisibility.createdAt}
          onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, createdAt: checked })}
        >
          Created At
        </DropdownMenuCheckboxItem>
        <DropdownMenuCheckboxItem
          checked={columnVisibility.updatedAt}
          onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, updatedAt: checked })}
        >
          Updated At
        </DropdownMenuCheckboxItem>
      </DropdownMenuContent>
    </DropdownMenu>
  )
}

/**
 * @typedef {object} Role
 * @property {string} name - The name of the role.
 * @property {string} [description] - The description of the role.
 * @property {string} [rule_name] - The rule name associated with the role.
 * @property {string} created_at - The creation timestamp of the role.
 * @property {string} updated_at - The last update timestamp of the role.
 */

/**
 * @typedef {object} Pagination
 * @property {number} current_page - The current page number.
 * @property {number} per_page - The number of items per page.
 * @property {number} total - The total number of items.
 * @property {number} last_page - The last page number.
 */

/**
 * @typedef {object} Filters
 * @property {string} [search] - The search string for filtering roles.
 */

/**
 * @typedef {object} Sort
 * @property {string} [sort_by] - The column to sort by.
 * @property {string} [sort_order] - The sort order (asc or desc).
 */

/**
 * Helper function to get CSRF token and parameter.
 * @param {object} props - The props object from usePage().
 * @returns {{csrfToken: string, csrfParam: string} | null} - An object containing csrfToken and csrfParam, or null if not found.
 */
function getCsrfTokenAndParam(props) {
  const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
  const metaParam = document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

  const csrfToken = metaToken || props.csrfToken
  const csrfParam = metaParam || props.csrfParam

  if (!csrfToken || !csrfParam) {
    toast.error('CSRF token missing. Please refresh the page.')
    return null
  }
  return { csrfToken, csrfParam }
}

/**
 * @param {object} props
 * @param {Role[]} props.roles
 * @param {Pagination} props.pagination
 * @param {Filters} props.filters
 * @param {Sort} props.sort
 */
export default function RoleIndex({ roles, pagination, filters, sort }) {
  const { props } = usePage()
  const [search, setSearch] = useState(filters?.search || '')
  const [deleteName, setDeleteName] = useState(null)
  const [showFilters, setShowFilters] = useState(false)
  const [columnVisibility, setColumnVisibility] = useState({
    name: true,
    description: true,
    ruleName: true,
    createdAt: true,
    updatedAt: true,
    actions: true,
  })

  const currentSortBy = sort?.sort_by || 'created_at'
  const currentSortOrder = sort?.sort_order || 'desc'

  const handleSort = (column) => {
    const newSortOrder = currentSortBy === column && currentSortOrder === 'asc' ? 'desc' : 'asc'
    router.get('/role', {
      ...filters,
      search: String(search),
      sort_by: column,
      sort_order: newSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleFilter = () => {
    router.get('/role', {
      search: String(search),
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleClearFilters = () => {
    setSearch('')
    router.get('/role', {
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleDelete = (name) => {
    const csrf = getCsrfTokenAndParam(props)
    if (!csrf)
      return

    const formData = {
      [csrf.csrfParam]: csrf.csrfToken,
    }

    router.post(`/role/${name}/delete`, formData, {
      onSuccess: () => {
        setDeleteName(null)
        toast.success('Role deleted successfully')
      },
      onError: () => {
        toast.error('Failed to delete role')
      },
    })
  }

  const formatDate = (dateString) => {
    if (!dateString)
      return 'N/A'
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
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
    <>
      <Head title="Roles | Yii2 - Modern Starter Kit" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <div>
                  <CardTitle>Roles List</CardTitle>
                  <CardDescription>
                    A list of all roles in the system
                  </CardDescription>
                </div>
                <div className="flex gap-2">
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => setShowFilters(!showFilters)}
                  >
                    <Filter className="mr-2 h-4 w-4" />
                    Filters
                    {showFilters ? <ChevronUp className="ml-2 h-4 w-4" /> : <ChevronDown className="ml-2 h-4 w-4" />}
                  </Button>
                  <ColumnVisibilityDropdown columnVisibility={columnVisibility} setColumnVisibility={setColumnVisibility} />
                  <Link href="/role/create">
                    <Button size="sm">
                      <Plus className="mr-2 h-4 w-4" />
                      Create Role
                    </Button>
                  </Link>
                </div>
              </div>
            </CardHeader>
            <CardContent>
              {/* Advanced Filters - Collapsible */}
              {showFilters && (
                <div className="space-y-4 mb-6 p-4 border rounded-lg bg-muted/50">
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-2">
                      <Filter className="h-4 w-4" />
                      <h3 className="font-semibold">Filters</h3>
                    </div>
                    {search && (
                      <Button variant="ghost" size="sm" onClick={handleClearFilters}>
                        <X className="mr-2 h-4 w-4" />
                        Clear All
                      </Button>
                    )}
                  </div>

                  <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {/* Search */}
                    <div className="space-y-2">
                      <Label htmlFor="search">Search</Label>
                      <div className="relative">
                        <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                        <Input
                          id="search"
                          type="search"
                          placeholder="Name or description..."
                          value={String(search)}
                          onChange={e => setSearch(e.target.value)}
                          className="pl-9"
                          onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                              e.preventDefault()
                              handleFilter()
                            }
                          }}
                        />
                      </div>
                    </div>
                  </div>

                  <div className="flex justify-end">
                    <Button onClick={handleFilter}>
                      <Filter className="mr-2 h-4 w-4" />
                      Apply Filters
                    </Button>
                  </div>
                </div>
              )}

              {/* Table */}
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      {columnVisibility.name && (
                        <SortableHeader column="name" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Name</SortableHeader>
                      )}
                      {columnVisibility.description && (
                        <SortableHeader column="description" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Description</SortableHeader>
                      )}
                      {columnVisibility.ruleName && (
                        <SortableHeader column="rule_name" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Rule</SortableHeader>
                      )}
                      {columnVisibility.createdAt && (
                        <SortableHeader column="created_at" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Created At</SortableHeader>
                      )}
                      {columnVisibility.updatedAt && (
                        <SortableHeader column="updated_at" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Updated At</SortableHeader>
                      )}
                      {columnVisibility.actions && (
                        <TableHead className="text-right">Actions</TableHead>
                      )}
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {roles && roles.length > 0
                      ? (
                          roles.map(role => (
                            <TableRow key={role.name}>
                              {columnVisibility.name && (
                                <TableCell className="font-medium">
                                  <div className="flex items-center gap-2">
                                    <Shield className="h-4 w-4 text-blue-500" />
                                    {role.name}
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.description && (
                                <TableCell>{role.description || '-'}</TableCell>
                              )}
                              {columnVisibility.ruleName && (
                                <TableCell>
                                  {role.rule_name
                                    ? (
                                        <Badge variant="outline">{role.rule_name}</Badge>
                                      )
                                    : (
                                        '-'
                                      )}
                                </TableCell>
                              )}
                              {columnVisibility.createdAt && (
                                <TableCell>
                                  <div className="flex flex-col">
                                    <span>{formatDate(role.created_at)}</span>
                                    <span className="text-xs text-muted-foreground">
                                      {formatDateTime(role.created_at).split(',')[1]?.trim()}
                                    </span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.updatedAt && (
                                <TableCell>
                                  <div className="flex flex-col">
                                    <span>{formatDate(role.updated_at)}</span>
                                    <span className="text-xs text-muted-foreground">
                                      {formatDateTime(role.updated_at).split(',')[1]?.trim()}
                                    </span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.actions && (
                                <TableCell className="text-right">
                                  <div className="flex items-center justify-end gap-2">
                                    <Link href={`/role/${role.name}`}>
                                      <Button size="sm" variant="ghost" title="View">
                                        <Eye className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Link href={`/role/${role.name}/update`}>
                                      <Button size="sm" variant="ghost" title="Edit">
                                        <Edit className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Button
                                      size="sm"
                                      variant="ghost"
                                      onClick={() => setDeleteName(role.name)}
                                      title="Delete"
                                    >
                                      <Trash2 className="h-4 w-4 text-destructive" />
                                    </Button>
                                  </div>
                                </TableCell>
                              )}
                            </TableRow>
                          ))
                        )
                      : (
                          [ // Wrap the single TableRow in an array
                            <TableRow key="no-roles">
                              <TableCell colSpan={Object.values(columnVisibility).filter(Boolean).length} className="text-center text-muted-foreground py-8">
                                No roles found
                              </TableCell>
                            </TableRow>,
                          ]
                        )}
                  </TableBody>
                </Table>
              </div>

              {/* Pagination */}
              {pagination && pagination.last_page > 1 && (
                <div className="flex items-center justify-between mt-4">
                  <div className="text-sm text-muted-foreground">
                    Showing
                    {' '}
                    {((pagination.current_page - 1) * pagination.per_page) + 1}
                    {' '}
                    to
                    {' '}
                    {Math.min(pagination.current_page * pagination.per_page, pagination.total)}
                    {' '}
                    of
                    {' '}
                    {pagination.total}
                    {' '}
                    roles
                  </div>
                  <div className="flex gap-2">
                    {pagination.current_page > 1 && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/role', {
                          ...filters,
                          search: String(search),
                          sort_by: currentSortBy,
                          sort_order: currentSortOrder,
                          page: pagination.current_page - 1,
                        }, { preserveState: true })}
                      >
                        Previous
                      </Button>
                    )}
                    {pagination.current_page < pagination.last_page && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/role', {
                          ...filters,
                          search: String(search),
                          sort_by: currentSortBy,
                          sort_order: currentSortOrder,
                          page: pagination.current_page + 1,
                        }, { preserveState: true })}
                      >
                        Next
                      </Button>
                    )}
                  </div>
                </div>
              )}
            </CardContent>
          </Card>
        </div>

        {/* Delete Confirmation Dialog */}
        <AlertDialog open={!!deleteName} onOpenChange={() => setDeleteName(null)}>
          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>Delete Role</AlertDialogTitle>
            </AlertDialogHeader>
            <AlertDialogDescription>
              Are you sure you want to delete the role "
              {String(deleteName)}
              "? This action cannot be undone.
            </AlertDialogDescription>
            <AlertDialogFooter>
              <AlertDialogCancel>Cancel</AlertDialogCancel>
              <AlertDialogAction
                onClick={() => handleDelete(deleteName)}
                className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
              >
                Delete
              </AlertDialogAction>
            </AlertDialogFooter>
          </AlertDialogContent>
        </AlertDialog>
      </DashboardLayout>
    </>
  )
}
