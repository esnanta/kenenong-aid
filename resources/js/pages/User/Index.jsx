import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ArrowUpDown, CheckCircle2, ChevronDown, ChevronUp, Columns2, Edit, Eye, Filter, Plus, Search, Trash2, X, XCircle } from 'lucide-react'
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
import { getCsrfParam, getCsrfToken } from '@/lib/csrf.js'

// SortableHeader component moved outside UserIndex
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

/**
 * @typedef {object} UserFilter
 * @property {string} [search] - Search query for users.
 * @property {string} [email_verified] - Filter by email verification status.
 * @property {string} [date_from] - Filter users created from this date.
 * @property {string} [date_to] - Filter users created up to this date.
 */

/**
 * @typedef {object} UserSort
 * @property {string} [sort_by] - Column to sort by.
 * @property {string} [sort_order] - Sort order (asc or desc).
 */

/**
 * @typedef {object} User
 * @property {number} id - The user's ID.
 * @property {string} name - The user's name.
 * @property {string} email - The user's email address.
 * @property {string|null} email_verified_at - Timestamp when the email was verified, or null if not verified.
 * @property {string} created_at - Timestamp when the user was created.
 */

/**
 * @param {object} props
 * @param {Array<User>} props.users
 * @param {object} props.pagination
 * @param {UserFilter} props.filters
 * @param {UserSort} props.sort
 */
export default function UserIndex({ users, pagination, filters, sort }) {
  const { props } = usePage()
  /** @type {string} */
  const [search, setSearch] = useState(filters?.search || '')
  /** @type {string} */
  const [emailVerified, setEmailVerified] = useState(filters?.email_verified || '')
  /** @type {string} */
  const [dateFrom, setDateFrom] = useState(filters?.date_from || '')
  /** @type {string} */
  const [dateTo, setDateTo] = useState(filters?.date_to || '')
  const [deleteId, setDeleteId] = useState(null)
  const [showFilters, setShowFilters] = useState(false)
  const [columnVisibility, setColumnVisibility] = useState({
    id: true,
    name: true,
    email: true,
    emailVerified: true,
    createdAt: true,
    actions: true,
  })

  const currentSortBy = sort?.sort_by || 'created_at'
  const currentSortOrder = sort?.sort_order || 'desc'

  const handleSort = (column) => {
    const newSortOrder = currentSortBy === column && currentSortOrder === 'asc' ? 'desc' : 'asc'
    router.get('/user', {
      ...filters,
      search,
      email_verified: emailVerified,
      date_from: dateFrom,
      date_to: dateTo,
      sort_by: column,
      sort_order: newSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleFilter = () => {
    router.get('/user', {
      search,
      email_verified: emailVerified,
      date_from: dateFrom,
      date_to: dateTo,
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleClearFilters = () => {
    setSearch('')
    setEmailVerified('')
    setDateFrom('')
    setDateTo('')
    router.get('/user', {
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleDelete = (id) => {
    const csrfToken = getCsrfToken()
    const csrfParam = getCsrfParam()

    if (!csrfToken || !csrfParam) {
      toast.error('CSRF token missing. Please refresh the page.')
      return
    }

    const formData = {
      [csrfParam]: csrfToken,
    }

    router.post(`/users/${id}/delete`, formData, {
      onSuccess: () => {
        setDeleteId(null)
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

  const hasActiveFilters = search || emailVerified || dateFrom || dateTo

  return (
    <>
      <Head title="Users | Yii2 - Modern Starter Kit" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <div>
                  <CardTitle>Users List</CardTitle>
                  <CardDescription>
                    A list of all users in your system
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
                        checked={columnVisibility.id}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, id: checked })}
                      >
                        ID
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.name}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, name: checked })}
                      >
                        Name
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.email}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, email: checked })}
                      >
                        Email
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.emailVerified}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, emailVerified: checked })}
                      >
                        Email Verified
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.createdAt}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, createdAt: checked })}
                      >
                        Created At
                      </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                  <Link href="/users/create">
                    <Button size="sm">
                      <Plus className="mr-2 h-4 w-4" />
                      Add User
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
                      <Search className="h-4 w-4" />
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
                          placeholder="Name or email..."
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

                    {/* Email Verified Filter */}
                    <div className="space-y-2">
                      <Label htmlFor="email-verified">Email Status</Label>
                      <Select value={emailVerified || 'all'} onValueChange={value => setEmailVerified(value === 'all' ? '' : value)}>
                        <SelectTrigger id="email-verified">
                          <SelectValue placeholder="All" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="all">All</SelectItem>
                          <SelectItem value="verified">Verified</SelectItem>
                          <SelectItem value="unverified">Unverified</SelectItem>
                        </SelectContent>
                      </Select>
                    </div>

                    {/* Date From */}
                    <div className="space-y-2">
                      <Label htmlFor="date-from">Date From</Label>
                      <Input
                        id="date-from"
                        type="date"
                        value={dateFrom}
                        onChange={e => setDateFrom(e.target.value)}
                      />
                    </div>

                    {/* Date To */}
                    <div className="space-y-2">
                      <Label htmlFor="date-to">Date To</Label>
                      <Input
                        id="date-to"
                        type="date"
                        value={dateTo}
                        onChange={e => setDateTo(e.target.value)}
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
                      {columnVisibility.id && (
                        <SortableHeader column="id" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>ID</SortableHeader>
                      )}
                      {columnVisibility.name && (
                        <SortableHeader column="name" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Name</SortableHeader>
                      )}
                      {columnVisibility.email && (
                        <SortableHeader column="email" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Email</SortableHeader>
                      )}
                      {columnVisibility.emailVerified && (
                        <SortableHeader column="email_verified_at" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Email Verified</SortableHeader>
                      )}
                      {columnVisibility.createdAt && (
                        <SortableHeader column="created_at" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} handleSort={handleSort}>Created At</SortableHeader>
                      )}
                      {columnVisibility.actions && (
                        <TableHead className="text-right">Actions</TableHead>
                      )}
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {users && users.length > 0
                      ? (
                          users.map(user => (
                            <TableRow key={user.id}>
                              {columnVisibility.id && (
                                <TableCell className="font-medium">{user.id}</TableCell>
                              )}
                              {columnVisibility.name && (
                                <TableCell className="font-medium">{user.name}</TableCell>
                              )}
                              {columnVisibility.email && (
                                <TableCell>
                                  <div className="flex items-center gap-2">
                                    <span>{user.email}</span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.emailVerified && (
                                <TableCell>
                                  {user.email_verified_at
                                    ? (
                                        <Badge variant="outline" className="bg-green-50 text-green-700 border-green-200 dark:bg-green-950 dark:text-green-400 dark:border-green-800">
                                          <CheckCircle2 className="mr-1 h-3 w-3" />
                                          Verified
                                        </Badge>
                                      )
                                    : (
                                        <Badge variant="outline" className="bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-950 dark:text-gray-400 dark:border-gray-800">
                                          <XCircle className="mr-1 h-3 w-3" />
                                          Not Verified
                                        </Badge>
                                      )}
                                </TableCell>
                              )}
                              {columnVisibility.createdAt && (
                                <TableCell>
                                  <div className="flex flex-col">
                                    <span>{formatDate(user.created_at)}</span>
                                    <span className="text-xs text-muted-foreground">
                                      {formatDateTime(user.created_at).split(',')[1]?.trim()}
                                    </span>
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.actions && (
                                <TableCell className="text-right">
                                  <div className="flex justify-end gap-2">
                                    <Link href={`/users/${user.id}`}>
                                      <Button variant="ghost" size="sm" title="View">
                                        <Eye className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Link href={`/users/${user.id}/update`}>
                                      <Button variant="ghost" size="sm" title="Edit">
                                        <Edit className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Button
                                      variant="ghost"
                                      size="sm"
                                      onClick={() => setDeleteId(user.id)}
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
                          [ // Wrap in an array
                            <TableRow key="no-users-found">
                              {' '}
                              {/* Add a unique key */}
                              <TableCell colSpan={Object.values(columnVisibility).filter(Boolean).length} className="text-center text-muted-foreground py-8">
                                No users found
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
                    users
                  </div>
                  <div className="flex gap-2">
                    {pagination.current_page > 1 && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/user', {
                          ...filters,
                          search,
                          email_verified: emailVerified,
                          date_from: dateFrom,
                          date_to: dateTo,
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
                        onClick={() => router.get('/user', {
                          ...filters,
                          search,
                          email_verified: emailVerified,
                          date_from: dateFrom,
                          date_to: dateTo,
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
                This action will delete the user. This action cannot be undone.
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
