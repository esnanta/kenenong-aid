import { zodResolver } from '@hookform/resolvers/zod'
import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowLeft } from 'lucide-react'
import { useForm as useHookForm } from 'react-hook-form'
import { toast } from 'sonner'
import { z } from 'zod'
import {DashboardLayout} from '@/components/layouts/DashboardLayout.jsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

// Validation schema - password validation is handled in onSubmit
const userSchema = z.object({
  name: z.string().min(1, 'Name is required').min(2, 'Name must be at least 2 characters'),
  email: z.string().min(1, 'Email is required').email('Please enter a valid email address'),
  password: z.string().optional(),
})

export default function UserForm({ user, errors: serverErrors }) {
  const { props } = usePage()
  const isEdit = !!user?.id

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useHookForm({
    resolver: zodResolver(userSchema),
    defaultValues: {
      name: user?.name || '',
      email: user?.email || '',
      password: '',
    },
  })

  const onSubmit = (data) => {
    // Validate password for create
    if (!isEdit && !data.password) {
      toast.error('Password is required')
      return
    }

    // Create form data with CSRF token
    let formData = { ...data };

    // Remove password if it's empty (for edit)
    if (isEdit && !data.password) {
      delete formData.password
    }

    formData = addCsrfToData(formData);

    const url = isEdit ? `/user/${user.id}/edit` : '/user/create'
    const method = isEdit ? 'put' : 'post'

    router[method](url, formData, {
      preserveScroll: true,
      onSuccess: (page) => {
        // Check if we navigated to the users list page (success)
        // Inertia::location() causes a full page reload, so we check the component
        const isOnUsersList = page?.component === 'Users/Index'

        // Only show success toast if we navigated to users list (success)
        if (isOnUsersList) {
          toast.success(isEdit ? 'User updated successfully' : 'User created successfully')
        }

        // If we're still on the form, don't show success toast
        // Errors are already displayed inline
      },
      onError: (errors) => {
        console.error('Form submission error:', errors)
        // Show error toast for HTTP errors
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

  // Merge all error sources: react-hook-form errors, server errors prop, and page props errors
  const allErrors = {}

  // Get errors from page props (Inertia standard location)
  const pageErrors = props?.errors || {}

  // Process react-hook-form errors
  if (errors) {
    Object.keys(errors).forEach((key) => {
      const errorValue = errors[key]
      if (errorValue?.message) {
        allErrors[key] = errorValue.message
      }
    })
  }

  // Process server errors prop (from backend)
  if (serverErrors) {
    Object.keys(serverErrors).forEach((key) => {
      const errorValue = serverErrors[key]
      allErrors[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
    })
  }

  // Process page props errors (Inertia standard)
  if (pageErrors) {
    Object.keys(pageErrors).forEach((key) => {
      const errorValue = pageErrors[key]
      if (!allErrors[key]) { // Don't override if already set
        allErrors[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
      }
    })
  }

  return (
    <>
      <Head title={isEdit ? 'Edit User' : 'Create User'} />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
              <CardTitle>{isEdit ? 'Edit User' : 'Create User'}</CardTitle>
              <Link href="/user">
                <Button variant="outline" size="sm">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </Link>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="name">Name *</Label>
                  <Input
                    id="name"
                    type="text"
                    {...register('name')}
                    className={allErrors.name ? 'border-destructive' : ''}
                  />
                  {allErrors.name && (
                    <p className="text-sm text-destructive">
                      {allErrors.name}
                    </p>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="email">Email *</Label>
                  <Input
                    id="email"
                    type="email"
                    {...register('email')}
                    className={allErrors.email ? 'border-destructive' : ''}
                  />
                  {allErrors.email && (
                    <p className="text-sm text-destructive">
                      {allErrors.email}
                    </p>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="password">
                    Password
                    {' '}
                    {isEdit ? '(leave blank to keep current password)' : '*'}
                  </Label>
                  <Input
                    id="password"
                    type="password"
                    {...register('password')}
                    className={allErrors.password ? 'border-destructive' : ''}
                  />
                  {allErrors.password && (
                    <p className="text-sm text-destructive">
                      {allErrors.password}
                    </p>
                  )}
                </div>

                <div className="flex gap-2">
                  <Button type="submit" disabled={isSubmitting}>
                    {isSubmitting ? 'Saving...' : isEdit ? 'Update User' : 'Create User'}
                  </Button>
                  <Link href="/user">
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
