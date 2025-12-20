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
import { Textarea } from '@/components/ui/textarea'

const disasterStatusSchema = z.object({
  code: z.string().min(1, 'Code is required').max(50, 'Code must be at most 50 characters'),
  title: z.string().min(1, 'Title is required').max(255, 'Title must be at most 255 characters'),
  description: z.string().optional().nullable(),
})

export default function DisasterStatusForm({ status, errors: serverErrors }) {
  const { props } = usePage()
  const isEdit = !!status?.id

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useHookForm({
    resolver: zodResolver(disasterStatusSchema),
    defaultValues: {
      code: status?.code || '',
      title: status?.title || '',
      description: status?.description || '',
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

    const formData = {
      ...data,
      [csrfParam]: csrfToken,
    }

    const url = isEdit ? `/disaster-statuses/${status.id}/edit` : '/disaster-statuses/create'
    const method = isEdit ? 'put' : 'post'

    router[method](url, formData, {
      preserveScroll: true,
      onSuccess: (page) => {
        const isOnList = page?.component === 'DisasterStatus/Index'

        if (isOnList) {
          toast.success(isEdit ? 'Disaster status updated successfully' : 'Disaster status created successfully')
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
      <Head title={isEdit ? 'Edit Disaster Status' : 'Create Disaster Status'} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
              <CardTitle>{isEdit ? 'Edit Disaster Status' : 'Create Disaster Status'}</CardTitle>
              <Link href="/disaster-statuses">
                <Button variant="outline" size="sm">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </Link>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div className="grid gap-4 md:grid-cols-2">
                  <div className="space-y-2">
                    <Label htmlFor="code">Code *</Label>
                    <Input
                      id="code"
                      {...register('code')}
                      className={allErrors.code ? 'border-destructive' : ''}
                    />
                    {allErrors.code && (
                      <p className="text-sm text-destructive">
                        {allErrors.code}
                      </p>
                    )}
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="title">Title *</Label>
                    <Input
                      id="title"
                      {...register('title')}
                      className={allErrors.title ? 'border-destructive' : ''}
                    />
                    {allErrors.title && (
                      <p className="text-sm text-destructive">
                        {allErrors.title}
                      </p>
                    )}
                  </div>
                </div>

                <div className="space-y-2">
                  <Label htmlFor="description">Description</Label>
                  <Textarea
                    id="description"
                    rows={5}
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
                    {isSubmitting ? 'Saving...' : (isEdit ? 'Update Status' : 'Create Status')}
                  </Button>
                  <Link href="/disaster-statuses">
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
