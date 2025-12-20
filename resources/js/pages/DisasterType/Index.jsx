import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ArrowUpDown, Columns2, Edit, Eye, Filter, Plus, Search, Trash2, X } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import DashboardLayout from '@/components/layouts/DashboardLayout'
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
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

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

export default function DisasterTypeIndex({ types, pagination, filters, sort }) {
  const { props } = usePage()
  const [search, setSearch] = useState(filters?.search || '')
  const [deleteId, setDeleteId] = useState(null)
  const [isFilterOpen, setIsFilterOpen] = useState(false)
  const [columnVisibility, setColumnVisibility] = useState({
    code: true,
    title: true,
    description: true,
    actions: true,
  })

  const currentSortBy = sort?.sort_by || 'code'
  const currentSortOrder = sort?.sort_order || 'asc'

  const handleSort = (column) => {
    const newSortOrder = currentSortBy === column && currentSortOrder === 'asc' ? 'desc' : 'asc'
    router.get('/disaster-types', {
      ...filters,
      search,
      sort_by: column,
      sort_order: newSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleFilter = () => {
    router.get('/disaster-types', {
      search: search || undefined,
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleClearFilters = () => {
    setSearch('')
    router.get('/disaster-types', {
      sort_by: currentSortBy,
      sort_order: currentSortOrder,
      page: 1,
    }, { preserveState: true })
  }

  const handleDelete = (id) => {
    router.post(`/disaster-types/${id}/delete`, {}, {
      onSuccess: () => {
        setDeleteId(null)
        toast.success('Disaster type deleted successfully')
      },
    })
  }

  const hasActiveFilters = !!search

  return (
    <>
      <Head title="Disaster Types | Kenenong Aid" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-between">
                <CardTitle>Disaster Types</CardTitle>
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
                        1
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
                        checked={columnVisibility.code}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, code: checked })}
                      >
                        Code
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.title}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, title: checked })}
                      >
                        Title
                      </DropdownMenuCheckboxItem>
                      <DropdownMenuCheckboxItem
                        checked={columnVisibility.description}
                        onCheckedChange={checked => setColumnVisibility({ ...columnVisibility, description: checked })}
                      >
                        Description
                      </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                  <Link href="/disaster-types/create">
                    <Button>
                      <Plus className="mr-2 h-4 w-4" />
                      Add Type
                    </Button>
                  </Link>
                </div>
              </div>
            </CardHeader>
            <CardContent>
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

                    <div className="grid gap-4 md:grid-cols-2">
                      <div className="space-y-2">
                        <Label htmlFor="search">Search</Label>
                        <div className="relative">
                          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                          <Input
                            id="search"
                            type="search"
                            placeholder="Code or title..."
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

              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      {columnVisibility.code && (
                        <SortableHeader column="code" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>Code</SortableHeader>
                      )}
                      {columnVisibility.title && (
                        <SortableHeader column="title" currentSortBy={currentSortBy} currentSortOrder={currentSortOrder} onSort={handleSort}>Title</SortableHeader>
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
                    {types && types.length > 0
                      ? (
                          types.map(type => (
                            <TableRow key={type.id}>
                              {columnVisibility.code && (
                                <TableCell>{type.code}</TableCell>
                              )}
                              {columnVisibility.title && (
                                <TableCell>{type.title}</TableCell>
                              )}
                              {columnVisibility.description && (
                                <TableCell>
                                  <div className="max-w-md truncate">
                                    {type.description}
                                  </div>
                                </TableCell>
                              )}
                              {columnVisibility.actions && (
                                <TableCell className="text-right">
                                  <div className="flex justify-end gap-2">
                                    <Link href={`/disaster-types/${type.id}`}>
                                      <Button variant="ghost" size="sm" title="View">
                                        <Eye className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Link href={`/disaster-types/${type.id}/edit`}>
                                      <Button variant="ghost" size="sm" title="Edit">
                                        <Edit className="h-4 w-4" />
                                      </Button>
                                    </Link>
                                    <Button
                                      variant="ghost"
                                      size="sm"
                                      onClick={() => setDeleteId(type.id)}
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
                              No disaster types found
                            </TableCell>
                          </TableRow>
                        )}
                  </TableBody>
                </Table>
              </div>

              {pagination && pagination.last_page > 1 && (
                <div className="flex items-center justify-between mt-4">
                  <div className="text-sm text-muted-foreground">
                    Showing {((pagination.current_page - 1) * pagination.per_page) + 1} to {Math.min(pagination.current_page * pagination.per_page, pagination.total)} of {pagination.total} types
                  </div>
                  <div className="flex gap-2">
                    {pagination.current_page > 1 && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/disaster-types', { ...filters, page: pagination.current_page - 1 }, { preserveState: true })}
                      >
                        Previous
                      </Button>
                    )}
                    {pagination.current_page < pagination.last_page && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => router.get('/disaster-types', { ...filters, page: pagination.current_page + 1 }, { preserveState: true })}
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

        <AlertDialog open={deleteId !== null} onOpenChange={() => setDeleteId(null)}>
          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>Are you sure?</AlertDialogTitle>
              <AlertDialogDescription>
                This action will delete the disaster type record. This action cannot be undone.
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
