import { zodResolver } from '@hookform/resolvers/zod'
import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowLeft } from 'lucide-react'
import { useForm as useHookForm } from 'react-hook-form'
import { toast } from 'sonner'
import { z } from 'zod'
import DashboardLayout from '@/components/layouts/DashboardLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Textarea } from '@/components/ui/textarea'

const disasterSchema = z.object({
  disaster_type_id: z.string().min(1, 'Disaster type is required'),
  disaster_status_id: z.string().min(1, 'Disaster status is required'),
  start_date: z.string().min(1, 'Start date is required'),
  end_date: z.string().optional().nullable(),
  description: z.string().min(1, 'Description is required').min(10, 'Description must be at least 10 characters'),
})

export default function DisasterForm({ disaster, errors: serverErrors, disasterTypes, disasterStatuses }) {
  const { props } = usePage()
  const isEdit = !!disaster?.id

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    watch,
    setValue,
  } = useHookForm({
    resolver: zodResolver(disasterSchema),
    defaultValues: {
      disaster_type_id: disaster?.disaster_type_id?.toString() || '',
      disaster_status_id: disaster?.disaster_status_id?.toString() || '',
      start_date: disaster?.start_date || '',
      end_date: disaster?.end_date || '',
      description: disaster?.description || '',
    },
  })

  const onSubmit = (data) => {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const metaParam = document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

    const csrfToken = metaToken || props.csrfToken
    const csrfParam = metaParam || props.csrfParam

    if (!csrfToken || !csrfParam) {
      toast.error('CSRF token missing. Please refresh the page.')
      return
    }

    // Convert string values to numbers for disaster_type_id and disaster_status_id
    const formData = {
      disaster_type_id: Number.parseInt(data.disaster_type_id),
      disaster_status_id: Number.parseInt(data.disaster_status_id),
      start_date: data.start_date,
      end_date: data.end_date || null,
      description: data.description,
      [csrfParam]: csrfToken,
    }

    const url = isEdit ? `/disasters/${disaster.id}/edit` : '/disasters/create'
    const method = isEdit ? 'put' : 'post'

    router[method](url, formData, {
      preserveScroll: true,
      onSuccess: (page) => {
        const isOnDisastersList = page?.component === 'Disaster/Index'

        if (isOnDisastersList) {
          toast.success(isEdit ? 'Disaster updated successfully' : 'Disaster created successfully')
        }
      },
      onError: (errors) => {
        console.error('Form submission error:', errors)
        if (errors && typeof errors === 'object') {
          Object.values(errors).forEach((error) => {
            if (Array.isArray(error)) {
              error.forEach(err => toast.error(err))
            }
            else if (typeof error === 'string') {
              toast.error(error)
            }
          })
        }
      },
    })
  }

  // Merge all error sources
  const allErrors = {}

  const pageErrors = props?.errors || {}

  if (errors) {
    Object.keys(errors).forEach((key) => {
      const errorValue = errors[key]
      if (errorValue?.message) {
        allErrors[key] = errorValue.message
      }
    })
  }

  if (serverErrors) {
    Object.keys(serverErrors).forEach((key) => {
      const errorValue = serverErrors[key]
      allErrors[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
    })
  }

  if (pageErrors) {
    Object.keys(pageErrors).forEach((key) => {
      const errorValue = pageErrors[key]
      if (!allErrors[key]) {
        allErrors[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
      }
    })
  }

  return (
    <>
      <Head title={isEdit ? 'Edit Disaster' : 'Create Disaster'} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
              <div className="flex items-center gap-3">
                <Link href="/disasters">
                  <Button variant="ghost" size="icon">
                    <ArrowLeft className="h-4 w-4" />
                  </Button>
                </Link>
                <CardTitle>{isEdit ? 'Edit Disaster' : 'Create Disaster'}</CardTitle>
              </div>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div className="grid gap-4 md:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="disaster_type_id">Disaster Type *</Label>
                    <Select
                      value={watch('disaster_type_id')}
                      onValueChange={value => setValue('disaster_type_id', value)}
                    >
                      <SelectTrigger id="disaster_type_id" className={allErrors.disaster_type_id ? 'border-destructive' : ''}>
                        <SelectValue placeholder="Select disaster type" />
                      </SelectTrigger>
                      <SelectContent>
                        {Object.entries(disasterTypes || {}).map(([key, value]) => (
                          <SelectItem key={key} value={key}>{value}</SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                    {allErrors.disaster_type_id && (
                      <p className="text-sm text-destructive">
                        {allErrors.disaster_type_id}
                      </p>
                    )}
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="disaster_status_id">Status *</Label>
                    <Select
                      value={watch('disaster_status_id')}
                      onValueChange={value => setValue('disaster_status_id', value)}
                    >
                      <SelectTrigger id="disaster_status_id" className={allErrors.disaster_status_id ? 'border-destructive' : ''}>
                        <SelectValue placeholder="Select status" />
                      </SelectTrigger>
                      <SelectContent>
                        {Object.entries(disasterStatuses || {}).map(([key, value]) => (
                          <SelectItem key={key} value={key}>{value}</SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                    {allErrors.disaster_status_id && (
                      <p className="text-sm text-destructive">
                        {allErrors.disaster_status_id}
                      </p>
                    )}
                  </div>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="start_date">Start Date *</Label>
                    <Input
                      id="start_date"
                      type="date"
                      {...register('start_date')}
                      className={allErrors.start_date ? 'border-destructive' : ''}
                    />
                    {allErrors.start_date && (
                      <p className="text-sm text-destructive">
                        {allErrors.start_date}
                      </p>
                    )}
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="end_date">End Date</Label>
                    <Input
                      id="end_date"
                      type="date"
                      {...register('end_date')}
                      className={allErrors.end_date ? 'border-destructive' : ''}
                    />
                    {allErrors.end_date && (
                      <p className="text-sm text-destructive">
                        {allErrors.end_date}
                      </p>
                    )}
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="description">Description *</Label>
                  <Textarea
                    id="description"
                    rows={5}
                    placeholder="Enter disaster description..."
                    {...register('description')}
                    className={allErrors.description ? 'border-destructive' : ''}
                  />
                  {allErrors.description && (
                    <p className="text-sm text-destructive">
                      {allErrors.description}
                    </p>
                  )}
                </div>

                <div className="flex gap-2">
                  <Button type="submit" disabled={isSubmitting}>
                    {isSubmitting ? 'Saving...' : (isEdit ? 'Update Disaster' : 'Create Disaster')}
                  </Button>
                  <Link href="/disasters">
                    <Button type="button" variant="outline">
                      Cancel
                    </Button>
                  </Link>
                </div>
              </form>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
