import { zodResolver } from '@hookform/resolvers/zod'
import { Head, Link, router, useForm, usePage } from '@inertiajs/react'
import { useForm as useHookForm } from 'react-hook-form'
import { toast } from 'sonner'
import { z } from 'zod'
import AuthLayout from '@/components/layouts/AuthLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const registerSchema = z.object({
  username: z.string().min(3, 'Username must be at least 3 characters'),
  email: z.string().email('Invalid email address'),
  password: z.string().min(6, 'Password must be at least 6 characters'),
  passwordConfirm: z.string(),
}).refine(data => data.password === data.passwordConfirm, {
  message: 'Passwords don\'t match',
  path: ['passwordConfirm'],
})

export default function Register({ model, errors: serverErrors }) {
  const { props } = usePage()
  const inertiaForm = useForm({
    username: model?.username || '',
    email: model?.email || '',
    password: '',
    passwordConfirm: '',
  })

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useHookForm({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      username: model?.username || '',
      email: model?.email || '',
      password: '',
      passwordConfirm: '',
    },
  })

  const onSubmit = (data) => {
    // Get CSRF token from Inertia shared props (more reliable than meta tags)
    const csrfToken = props.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const csrfParam = props.csrfParam || document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

    const formData = {
      username: data.username, // yii2-usuario expects 'username' field
      email: data.email,
      password: data.password,
      ...(csrfToken && csrfParam ? { [csrfParam]: csrfToken } : {}),
    }

    // Use router.post directly to ensure data is sent correctly
    router.post('/auth/register', formData, {
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

  const allErrors = { ...errors, ...serverErrors }

  return (
    <>
      <Head title="Register | Yii2 - Modern Starter Kit" />
      <AuthLayout>
        <Card className="border-0 shadow-none px-6">
          <CardHeader className="space-y-1 px-0">
            <CardTitle className="text-2xl font-semibold tracking-tight">Create an account</CardTitle>
            <CardDescription className="text-base">
              Enter your information to get started
            </CardDescription>
          </CardHeader>
          <CardContent className="px-0">
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
              <div className="space-y-2">
                <Label htmlFor="username" className="text-sm font-medium">Username</Label>
                <Input
                  id="username"
                  type="text"
                  {...register('username')}
                  className={allErrors.username ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  autoFocus
                  placeholder="Enter your username"
                />
                {allErrors.username && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.username === 'string'
                      ? allErrors.username
                      : allErrors.username?.message || 'Invalid username'}
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="email" className="text-sm font-medium">Email</Label>
                <Input
                  id="email"
                  type="email"
                  {...register('email')}
                  className={allErrors.email ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  placeholder="Enter your email"
                />
                {allErrors.email && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.email === 'string'
                      ? allErrors.email
                      : allErrors.email?.message || 'Invalid email'}
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="password" className="text-sm font-medium">Password</Label>
                <Input
                  id="password"
                  type="password"
                  {...register('password')}
                  className={allErrors.password ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  placeholder="Create a password"
                />
                {allErrors.password && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.password === 'string'
                      ? allErrors.password
                      : allErrors.password?.message || 'Invalid password'}
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="passwordConfirm" className="text-sm font-medium">Confirm Password</Label>
                <Input
                  id="passwordConfirm"
                  type="password"
                  {...register('passwordConfirm')}
                  className={allErrors.passwordConfirm ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  placeholder="Confirm your password"
                />
                {allErrors.passwordConfirm && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.passwordConfirm === 'string'
                      ? allErrors.passwordConfirm
                      : allErrors.passwordConfirm?.message || 'Passwords do not match'}
                  </p>
                )}
              </div>

              <Button
                type="submit"
                variant="default"
                className="w-full"
                disabled={isSubmitting || inertiaForm.processing}
              >
                {isSubmitting || inertiaForm.processing ? 'Creating account...' : 'Create account'}
              </Button>
            </form>

            <div className="mt-6 text-center text-sm">
              <span className="text-muted-foreground">Already have an account? </span>
              <Link href="/auth/login" className="text-primary hover:underline font-medium">
                Sign in
              </Link>
            </div>
          </CardContent>
        </Card>
      </AuthLayout>
    </>
  )
}
