import { zodResolver } from '@hookform/resolvers/zod'
import { Head, router, useForm, usePage } from '@inertiajs/react'
import { useForm as useHookForm } from 'react-hook-form'
import { toast } from 'sonner'
import { z } from 'zod'
import { DashboardLayout } from '@/components/layouts/DashboardLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

const profileSchema = z.object({
  name: z.string().min(3, 'Name must be at least 3 characters'),
  email: z.string().email('Invalid email address'),
})

export default function Profile({ user }) {
  const { props } = usePage()
  const inertiaForm = useForm({
    name: user?.name || '',
    email: user?.email || '',
  })

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useHookForm({
    resolver: zodResolver(profileSchema),
    defaultValues: {
      name: user?.name || '',
      email: user?.email || '',
    },
  })

  const onSubmit = (data) => {
    const formDataWithCsrf = addCsrfToData(data)

    // Use router.put directly to ensure data is sent correctly
    router.put('/dashboard/profile', formDataWithCsrf, {
      onSuccess: () => {
        toast.success('Profile updated successfully')
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          if (Array.isArray(error)) {
            error.forEach(err => toast.error(err))
          }
          else {
            toast.error(error)
          }
        })
      },
    })
  }

  return (
    <>
      <Head title="Profile | Yii2 - Modern Starter Kit" />
      <DashboardLayout user={props.user}>
        <div className="space-y-6">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Profile</h1>
            <p className="text-muted-foreground">
              Manage your account settings and preferences
            </p>
          </div>

          <Card>
            <CardHeader>
              <CardTitle>Profile Information</CardTitle>
              <CardDescription>
                Update your account's profile information
              </CardDescription>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="name">Name</Label>
                  <Input
                    id="name"
                    {...register('name')}
                    disabled={isSubmitting || inertiaForm.processing}
                  />
                  {errors.name && (
                    <p className="text-sm text-destructive">
                      {errors.name.message}
                    </p>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    {...register('email')}
                    disabled={isSubmitting || inertiaForm.processing}
                  />
                  {errors.email && (
                    <p className="text-sm text-destructive">
                      {errors.email.message}
                    </p>
                  )}
                </div>

                <Button
                  type="submit"
                  disabled={isSubmitting || inertiaForm.processing}
                >
                  {isSubmitting || inertiaForm.processing ? 'Saving...' : 'Save Changes'}
                </Button>
              </form>
            </CardContent>
          </Card>
        </div>
      </DashboardLayout>
    </>
  )
}
