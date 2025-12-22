import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ArrowUpDown, Columns2, Edit, Eye, Filter, Plus, Search, Trash2, X } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import {DashboardLayout} from '@/components/layouts/DashboardLayout'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import {
  Collapsible,
  CollapsibleContent,
} from '@/components/ui/collapsible'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

// SortableHeader component (moved outside to avoid nesting)
function SortableHeader({ column, currentSortBy, currentSortOrder, onSort, children }) {
  const isSorted = currentSortBy === column
  const sortIcon = isSorted
    ? (currentSortOrder === 'asc' ? <ArrowUp className="ml-2 h-4 w-4" /> : <ArrowDown className="ml-2 h-4 w-4" />)
    : <ArrowUpDown className="ml-2 h-4 w-4 opacity-50" />

  return (
    <TableHead className="cursor-pointer hover:bg-muted/50" onClick={() => onSort(column)}>
      <div className="flex items-center">
        {children}
        {sortIcon}
      </div>
    </TableHead>
  )
}

export default function DisasterIndex({ disasters, pagination, filters, sort, disasterTypes, disasterStatuses }) {
  const { props } = usePage()
  const [search, setSearch] = useState(filters?.search || '')
  const [disasterTypeId, setDisasterTypeId] = useState(filters?.disaster_type_id?.toString() || '')
  const [disasterStatusId, setDisasterStatusId] = useState(filters?.disaster_status_id?.toString() || '')
  const [dateFrom, setDateFrom] = useState(filters?.date_from || '')
  const [dateTo, setDateTo] = useState(filters?.date_to || '')
  const [deleteId, setDeleteId] = useState(null)
  const [isFilterOpen, setIsFilterOpen] = useState(false)
  const [columnVisibility, setColumnVisibility] = useState({
    type: true,
    status: true,
    startDate: true,
    endDate: true,
    description: true,
    actions: true,
  })

  const currentSortBy = sort?.sort_by || 'created_at'
  const currentSortOrder = sort?.sort_order || 'desc'

  const handleSort = (column) => {
    const newSortOrder = currentSortBy === column && currentSortOrder === 'asc' ? 'desc' : 'asc'
    router.get('/disasters', {
      ...filters,
      search,
      disaster_type_id: disasterTypeId || undefined,
      disaster_status_id: disasterStatusId || undefined,
      date_from: dateFrom || undefined,
      date_to: dateTo || undefined,
      sort_by: column,
      sort_order: newSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleFilter = () => {
    router.get('/disasters', {
      search: search || undefined,
      disaster_type_id: disasterTypeId || undefined,
      disaster_status_id: disasterStatusId || undefined,
      date_from: dateFrom || undefined,
      date_to: dateTo || undefined,
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleClearFilters = () => {
    setSearch('')
    setDisasterTypeId('')
    setDisasterStatusId('')
    setDateFrom('')
    setDateTo('')
    router.get('/disasters', {
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleDelete = (id) => {
    router.post(`/disasters/${id}/delete`, {}, {
      onSuccess: () => {
        setDeleteId(null)
        toast.success('Disaster deleted successfully')
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

  const getStatusBadgeVariant = (status) => {
    const statusNum = typeof status === 'string' ? Number.parseInt(status) : status
    switch (statusNum) {
      case 1: // Active
        return 'destructive'
      case 2: // Resolved
        return 'outline'
      case 3: // Monitoring
        return 'secondary'
      default:
        return 'outline'
    }
  }

  const getTypeBadgeColor = (type) => {
    const typeNum = typeof type === 'string' ? Number.parseInt(type) : type
    const colors = {
      1: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200', // Earthquake
      2: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200', // Flood
      3: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200', // Fire
      4: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200', // Tsunami
      5: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200', // Volcano
      6: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200', // Landslide
      99: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200', // Other
    }
    return colors[typeNum] || 'bg-gray-100 text-gray-800'
  }

  const hasActiveFilters = search || disasterTypeId || disasterStatusId || dateFrom || dateTo
  return (
    <>
      <Head title="Disasters | Yii2 - Modern Starter Kit" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <CardTitle>Disasters</CardTitle>
                <div className="flex gap-2">
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => setIsFilterOpen(!isFilterOpen)}
                  >
                    <Filter className="mr-2 h-4 w-4" />
                    Filters
                    {hasActiveFilters && (
                      <Badge variant="secondary" className="ml-2">
                        {[search, disasterTypeId, disasterStatusId, dateFrom, dateTo].filter(Boolean).length}
                      </Badge>
                    )}
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
                        checked={columnVisibility.type}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, type: checked })}
                      >
                        Type
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.status}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, status: checked })}
                      >
                        Status
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.startDate}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, startDate: checked })}
                      >
                        Start Date
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.endDate}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, endDate: checked })}
                      >
                        End Date
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.description}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, description: checked })}
                      >
                        Description
                      </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                  <Link href="/disasters/create">
                    <Button>
                      <Plus className="mr-2 h-4 w-4" />
                      Add Disaster
                    </Button>
                  </Link>
                </div>
              </div>
            </CardHeader>
            <CardContent>
              {/* Advanced Filters - Collapsible */}
              <Collapsible open={isFilterOpen} onOpenChange={setIsFilterOpen}>
                <CollapsibleContent>
                  <div className="space-y-4 mb-6 p-4 border rounded-lg bg-muted/50">
                    <div className="flex items-center justify-between mb-4">
                      <div className="flex items-center gap-2">
                        <Filter className="h-4 w-4" />
                        <h3 className="font-semibold">Filter Options</h3>
                      </div>
                      {hasActiveFilters && (
                        <Button variant="ghost" size="sm" onClick={handleClearFilters}>
                          <X className="mr-2 h-4 w-4" />
                          Clear All
                        </Button>
                      )}
                    </div>

                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                      {/* Search */}
                      <div className="space-y-2">
                        <Label htmlFor="search">Search</Label>
                        <div className="relative">
                          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                          <Input
                            id="search"
                            type="search"
                            placeholder="Description..."
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

                      {/* Disaster Type Filter */}
                      <div className="space-y-2">
                        <Label htmlFor="disaster-type">Disaster Type</Label>
                        <Select value={disasterTypeId || 'all'} onValueChange={value => setDisasterTypeId(value === 'all' ? '' : value)}>
                          <SelectTrigger id="disaster-type">
                            <SelectValue placeholder="All" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="all">All Types</SelectItem>
                            {Object.entries(disasterTypes || {}).map(([key, value]) => (
                              <SelectItem key={key} value={key}>{value}</SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>

                      {/* Disaster Status Filter */}
                      <div className="space-y-2">
                        <Label htmlFor="disaster-status">Status</Label>
                        <Select value={disasterStatusId || 'all'} onValueChange={value => setDisasterStatusId(value === 'all' ? '' : value)}>
                          <SelectTrigger id="disaster-status">
                            <SelectValue placeholder="All" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectItem value="all">All Statuses</SelectItem>
                            {Object.entries(disasterStatuses || {}).map(([key, value]) => (
                              <SelectItem key={key} value={key}>{value}</SelectItem>
                            ))}
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
                </CollapsibleContent>
              </Collapsible>

              {/* Table */}
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      {columnVisibility.type && (
                        <SortableHeader column="disaster_type" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>Type</SortableHeader>
                      )}
                      {columnVisibility.status && (
                        <SortableHeader column="disaster_status" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>Status</SortableHeader>
                      )}
                      {columnVisibility.startDate && (
                        <SortableHeader column="start_date" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>Start Date</SortableHeader>
                      )}
                      {columnVisibility.endDate && (
                        <SortableHeader column="end_date" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>End Date</SortableHeader>
                      )}
                      {columnVisibility.description && (
                        <TableHead>Description</TableHead>
                      )}
                      {columnVisibility.actions && (
                        <TableHead className="text-right">Actions</TableHead>
                      )}
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {disasters && disasters.length > 0
                      ? (
                          disasters.map(disaster => (
                            <TableRow key={disaster.id}>
                              {columnVisibility.type && (
                                <TableCell>
                                  <Badge variant="outline" className={getTypeBadgeColor(disaster.disaster_type_id)}>
                                    {disaster.disaster_type_label}
                                  </Badge>
                                </TableCell>
                              )}
                              {columnVisibility.status && (
                                <TableCell>
                                  <Badge variant={getStatusBadgeVariant(disaster.disaster_status_id)}>
                                    {disaster.disaster_status_label}
                                  </Badge>
                                </TableCell>
                              )}
                              {columnVisibility.startDate && (
                                <TableCell>{formatDate(disaster.start_date)}</TableCell>
                              )}
                              {columnVisibility.endDate && (
                                <TableCell>{formatDate(disaster.end_date)}</TableCell>
                              )}
                              {columnVisibility.description && (
                                <TableCell>
                                  <div className="max-w-md truncate">
                                    {disaster.description}
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.actions && (
                                <TableCell className="text-right">
                                  <div className="flex justify-end gap-2">
                                    <Link href={`/disasters/${disaster.id}`}>
                                      <Button variant="ghost" size="sm" title="View">
                                        <Eye className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Link href={`/disasters/${disaster.id}/update`}>
                                      <Button variant="ghost" size="sm" title="update">
                                        <Edit className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Button
                                      variant="ghost"
                                      size="sm"
                                      onClick={() => setDeleteId(disaster.id)}
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
                              No disasters found
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
                    disasters
                  </div>
                  <div className="flex gap-2">
                    {pagination.current_page > 1 && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/disasters', {
                          search: search || undefined,
                          disaster_type_id: disasterTypeId || undefined,
                          disaster_status_id: disasterStatusId || undefined,
                          date_from: dateFrom || undefined,
                          date_to: dateTo || undefined,
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
                        onClick={() => router.get('/disasters', {
                          search: search || undefined,
                          disaster_type_id: disasterTypeId || undefined,
                          disaster_status_id: disasterStatusId || undefined,
                          date_from: dateFrom || undefined,
                          date_to: dateTo || undefined,
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
                This action will delete the disaster record. This action cannot be undone.
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
