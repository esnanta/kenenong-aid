import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ArrowUpDown, ChevronDown, ChevronUp, Columns2, Edit, Eye, Filter, Plus, Search, Trash2, X } from 'lucide-react'
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
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select.tsx'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table.tsx'

// Moved SortableHeader component definition to the top level
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

export default function PermissionIndex({ permissions, pagination, filters, sort, rulesList }) {
  const { props } = usePage()
  const [search, setSearch] = useState(filters?.search || '')
  const [ruleName, setRuleName] = useState(filters?.rule_name || '')
  const [createdAtFrom, setCreatedAtFrom] = useState(filters?.created_at_from || '')
  const [createdAtTo, setCreatedAtTo] = useState(filters?.created_at_to || '')
  const [deleteId, setDeleteId] = useState(null)
  const [showFilters, setShowFilters] = useState(false)
  const [columnVisibility, setColumnVisibility] = useState({
    name: true,
    description: true,
    ruleName: true,
    createdAt: true,
    updatedAt: true,
    actions: true,
  })

  const currentSortBy = sort?.sort_by || 'name'
  const currentSortOrder = sort?.sort_order || 'asc'

  const handleSort = (column) => {
    router.get('/permissions', {
      ...filters,
      search,
      rule_name: ruleName,
      created_at_from: createdAtFrom,
      created_at_to: createdAtTo,
      sort_by: column,
      sort_order: currentSortOrder === 'asc' ? 'desc' : 'asc', // Toggle sort order
      page: 1,
    }, { preserveState: true })
  }

  const handleFilter = () => {
    router.get('/permissions', {
      search,
      rule_name: ruleName,
      created_at_from: createdAtFrom,
      created_at_to: createdAtTo,
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleClearFilters = () => {
    setSearch('')
    setRuleName('')
    setCreatedAtFrom('')
    setCreatedAtTo('')
    router.get('/permissions', {
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleDelete = (name) => {
    // Get CSRF token from meta tag or props
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const metaParam = document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

    const csrfToken = metaToken || props.csrfToken
    const csrfParam = metaParam || props.csrfParam

    if (!csrfToken || !csrfParam) {
      toast.error('CSRF token missing. Please refresh the page.')
      return
    }

    const formData = {
      [csrfParam]: csrfToken,
      _method: 'delete', // Inertia uses _method for DELETE requests
    }

    router.post(`/permissions/${name}/delete`, formData, {
      onSuccess: () => {
        setDeleteId(null)
        toast.success('Permission deleted successfully.')
      },
      onError: (errors) => {
        toast.error('Failed to delete permission.', {
          description: errors.message || 'An unknown error occurred.',
        })
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

  const hasActiveFilters = search || ruleName || createdAtFrom || createdAtTo

  return (
    <>
      <Head title="Permissions | Kenenong Aid" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <div>
                  <CardTitle>Permissions List</CardTitle>
                  <CardDescription>
                    A list of all permissions in your system
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
                  <Link href="/permissions/create">
                    <Button size="sm">
                      <Plus className="mr-2 h-4 w-4" />
                      Add Permission
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
                    {hasActiveFilters && (
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
                          value={search}
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

                    {/* Rule Name Filter */}
                    <div className="space-y-2">
                      <Label htmlFor="rule-name">Rule Name</Label>
                      <Select value={ruleName || 'all'} onValueChange={value => setRuleName(value === 'all' ? '' : value)}>
                        <SelectTrigger id="rule-name">
                          <SelectValue placeholder="All Rules" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="all">All Rules</SelectItem>
                          {rulesList.map(rule => (
                            <SelectItem key={rule.value} value={rule.value}>{rule.label}</SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>

                    {/* Created At From */}
                    <div className="space-y-2">
                      <Label htmlFor="created-at-from">Created From</Label>
                      <Input
                        id="created-at-from"
                        type="date"
                        value={createdAtFrom}
                        onChange={e => setCreatedAtFrom(e.target.value)}
                      />
                    </div>

                    {/* Created At To */}
                    <div className="space-y-2">
                      <Label htmlFor="created-at-to">Created To</Label>
                      <Input
                        id="created-at-to"
                        type="date"
                        value={createdAtTo}
                        onChange={e => setCreatedAtTo(e.target.value)}
                      />
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
                        <SortableHeader
                          column="name"
                          currentSortBy={currentSortBy}
                          currentSortOrder={currentSortOrder}
                          handleSort={handleSort}
                        >
                          Name
                        </SortableHeader>
                      )}
                      {columnVisibility.description && (
                        <SortableHeader
                          column="description"
                          currentSortBy={currentSortBy}
                          currentSortOrder={currentSortOrder}
                          handleSort={handleSort}
                        >
                          Description
                        </SortableHeader>
                      )}
                      {columnVisibility.ruleName && (
                        <SortableHeader
                          column="ruleName"
                          currentSortBy={currentSortBy}
                          currentSortOrder={currentSortOrder}
                          handleSort={handleSort}
                        >
                          Rule Name
                        </SortableHeader>
                      )}
                      {columnVisibility.createdAt && (
                        <SortableHeader
                          column="createdAt"
                          currentSortBy={currentSortBy}
                          currentSortOrder={currentSortOrder}
                          handleSort={handleSort}
                        >
                          Created At
                        </SortableHeader>
                      )}
                      {columnVisibility.updatedAt && (
                        <SortableHeader
                          column="updatedAt"
                          currentSortBy={currentSortBy}
                          currentSortOrder={currentSortOrder}
                          handleSort={handleSort}
                        >
                          Updated At
                        </SortableHeader>
                      )}
                      {columnVisibility.actions && (
                        <TableHead className="text-right">Actions</TableHead>
                      )}
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {permissions && permissions.length > 0
                      ? (
                          permissions.map(permission => (
                            <TableRow key={permission.name}>
                              {columnVisibility.name && (
                                <TableCell className="font-medium">{permission.name}</TableCell>
                              )}
                              {columnVisibility.description && (
                                <TableCell>{permission.description || 'N/A'}</TableCell>
                              )}
                              {columnVisibility.ruleName && (
                                <TableCell>
                                  {permission.rule_name
                                    ? (
                                        <Badge variant="outline" className="bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950 dark:text-blue-400 dark:border-blue-800">
                                          {permission.rule_name}
                                        </Badge>
                                      )
                                    : (
                                        <Badge variant="outline" className="bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-950 dark:text-gray-400 dark:border-gray-800">
                                          No Rule
                                        </Badge>
                                      )}
                                </TableCell>
                              )}
                              {columnVisibility.createdAt && (
                                <TableCell>
                                  <div className="flex flex-col">
                                    <span>{formatDate(permission.created_at)}</span>
                                    <span className="text-xs text-muted-foreground">
                                      {formatDateTime(permission.created_at).split(',')[1]?.trim()}
                                    </span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.updatedAt && (
                                <TableCell>
                                  <div className="flex flex-col">
                                    <span>{formatDate(permission.updated_at)}</span>
                                    <span className="text-xs text-muted-foreground">
                                      {formatDateTime(permission.updated_at).split(',')[1]?.trim()}
                                    </span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.actions && (
                                <TableCell className="text-right">
                                  <div className="flex justify-end gap-2">
                                    <Link href={`/permissions/${encodeURIComponent(permission.name)}`}>
                                      <Button variant="ghost" size="sm" title="View">
                                        <Eye className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Link href={`/permissions/${encodeURIComponent(permission.name)}/update`}>
                                      <Button variant="ghost" size="sm" title="Update">
                                        <Edit className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Button
                                      variant="ghost"
                                      size="sm"
                                      onClick={() => setDeleteId(permission.name)}
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
                          <TableRow>
                            <TableCell colSpan={Object.values(columnVisibility).filter(Boolean).length} className="text-center text-muted-foreground py-8">
                              No permissions found
                            </TableCell>
                          </TableRow>
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
                    permissions
                  </div>
                  <div className="flex gap-2">
                    {pagination.current_page > 1 && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/permissions', {
                          ...filters,
                          search,
                          rule_name: ruleName,
                          created_at_from: createdAtFrom,
                          created_at_to: createdAtTo,
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
                        onClick={() => router.get('/permissions', {
                          ...filters,
                          search,
                          rule_name: ruleName,
                          created_at_from: createdAtFrom,
                          created_at_to: createdAtTo,
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
        <AlertDialog open={deleteId !== null} onOpenChange={() => setDeleteId(null)}>
          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>Are you sure?</AlertDialogTitle>
              <AlertDialogDescription>
                This action will delete the permission &quot;
                {deleteId}
                &quot;. This action cannot be undone.
              </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
              <AlertDialogCancel>Cancel</AlertDialogCancel>
              <AlertDialogAction
                onClick={() => handleDelete(deleteId)}
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
