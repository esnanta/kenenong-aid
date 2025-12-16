import { zodResolver } from '@hookform/resolvers/zod'
import { Head, Link, router, useForm, usePage } from '@inertiajs/react'
import { useForm as useHookForm } from 'react-hook-form'
import { toast } from 'sonner'
import { z } from 'zod'
import AuthLayout from '@/components/layouts/AuthLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Checkbox } from '@/components/ui/checkbox'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const loginSchema = z.object({
  login: z.string().min(1, 'Email or username is required'),
  password: z.string().min(1, 'Password is required'),
  rememberMe: z.boolean().default(false),
})

export default function Login({ model, errors: serverErrors }) {
  const { props } = usePage()
  const inertiaForm = useForm({
    login: model?.login || '',
    password: '',
    rememberMe: false,
  })

  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors, isSubmitting },
  } = useHookForm({
    resolver: zodResolver(loginSchema),
    defaultValues: {
      login: model?.login || '',
      password: '',
      rememberMe: false,
    },
  })

  const onSubmit = (data) => {
    // CRITICAL: Always get CSRF token from meta tag - it's updated on each page load
    // The Inertia props token might be stale from the initial GET request
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const metaParam = document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

    // Fallback to props if meta tag is missing (shouldn't happen)
    const csrfToken = metaToken || props.csrfToken
    const csrfParam = metaParam || props.csrfParam

    if (!csrfToken || !csrfParam) {
      toast.error('CSRF token missing. Please refresh the page.')
      return
    }

    // Create form data with CSRF token
    const formData = {
      ...data,
      [csrfParam]: csrfToken,
    }

    // Use router.post directly to ensure data is sent correctly
    router.post('/auth/login', formData, {
      preserveScroll: true,
      onSuccess: (page) => {
        // Check if we were redirected to dashboard (login successful)
        if (page?.component === 'Dashboard/Index' || page?.url?.includes('/dashboard')) {
          toast.success('Login successfully')
          return
        }

        // If we're still on the login page, check for errors in props
        // This happens when login fails - server returns 200 with errors
        if (page?.props?.errors && Object.keys(page.props.errors).length > 0) {
          const errors = page.props.errors

          // Check for login error (yii2-usuario uses 'login' field)
          if (errors.login) {
            if (Array.isArray(errors.login)) {
              errors.login.forEach(err => toast.error(err))
            }
            else if (typeof errors.login === 'string') {
              toast.error(errors.login)
            }
          }

          // Then check for password error
          if (errors.password) {
            if (Array.isArray(errors.password)) {
              errors.password.forEach(err => toast.error(err))
            }
            else if (typeof errors.password === 'string') {
              toast.error(errors.password)
            }
          }

          // Handle other errors
          Object.entries(errors).forEach(([field, errorMessages]) => {
            if (field !== 'login' && field !== 'password') {
              if (Array.isArray(errorMessages)) {
                errorMessages.forEach(err => toast.error(err))
              }
              else if (typeof errorMessages === 'string') {
                toast.error(errorMessages)
              }
            }
          })
        }
      },
      onError: (errors) => {
        console.error('Login Error:', errors)
        // Handle validation errors (this fires for 4xx/5xx status codes)
        if (errors && typeof errors === 'object') {
          // Check for login error (yii2-usuario uses 'login' field)
          if (errors.login) {
            if (Array.isArray(errors.login)) {
              errors.login.forEach(err => toast.error(err))
            }
            else if (typeof errors.login === 'string') {
              toast.error(errors.login)
            }
          }
          // Then check for password error
          if (errors.password) {
            if (Array.isArray(errors.password)) {
              errors.password.forEach(err => toast.error(err))
            }
            else if (typeof errors.password === 'string') {
              toast.error(errors.password)
            }
          }
          // Handle other errors
          Object.entries(errors).forEach(([field, errorMessages]) => {
            if (field !== 'login' && field !== 'password') {
              if (Array.isArray(errorMessages)) {
                errorMessages.forEach(err => toast.error(err))
              }
              else if (typeof errorMessages === 'string') {
                toast.error(errorMessages)
              }
            }
          })
        }
        else {
          toast.error('Login failed. Please check your credentials.')
        }
      },
    })
  }

  const allErrors = { ...errors, ...serverErrors }

  return (
    <>
      <Head title="Login | Yii2 - Modern Starter Kit" />
      <AuthLayout>
        <Card className="border-0 shadow-none px-6">
          <CardHeader className="space-y-1 px-0">
            <CardTitle className="text-2xl font-semibold tracking-tight">Sign in</CardTitle>
            <CardDescription className="text-base">
              Enter your credentials to access your account
            </CardDescription>
          </CardHeader>
          <CardContent className="px-0">
            <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
              <div className="space-y-2">
                <Label htmlFor="login" className="text-sm font-medium">Email or Username</Label>
                <Input
                  id="login"
                  type="text"
                  {...register('login')}
                  className={allErrors.login ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  autoFocus
                  placeholder="Enter your email or username"
                />
                {allErrors.login && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.login === 'string'
                      ? allErrors.login
                      : allErrors.login?.message || 'Invalid login'}
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <Label htmlFor="password" className="text-sm font-medium">Password</Label>
                  <Link
                    href="/auth/forgot-password"
                    className="text-sm text-primary hover:underline font-medium"
                  >
                    Forgot password?
                  </Link>
                </div>
                <Input
                  id="password"
                  type="password"
                  {...register('password')}
                  className={allErrors.password ? 'border-destructive' : ''}
                  disabled={isSubmitting || inertiaForm.processing}
                  placeholder="Enter your password"
                />
                {allErrors.password && (
                  <p className="text-sm text-destructive font-medium">
                    {typeof allErrors.password === 'string'
                      ? allErrors.password
                      : allErrors.password?.message || 'Invalid password'}
                  </p>
                )}
              </div>

              <div className="flex items-center space-x-2">
                <Checkbox
                  id="rememberMe"
                  checked={watch('rememberMe')}
                  onCheckedChange={(checked) => {
                    setValue('rememberMe', checked)
                    inertiaForm.setData('rememberMe', checked)
                  }}
                />
                <Label htmlFor="rememberMe" className="text-sm font-normal cursor-pointer leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                  Remember me
                </Label>
              </div>

              <Button
                type="submit"
                variant="default"
                className="w-full"
                disabled={isSubmitting || inertiaForm.processing}
              >
                {isSubmitting || inertiaForm.processing ? 'Signing in...' : 'Sign in'}
              </Button>
            </form>

            <div className="mt-6 text-center text-sm">
              <span className="text-muted-foreground">Don't have an account? </span>
              <Link href="/auth/register" className="text-primary hover:underline font-medium">
                Sign up
              </Link>
            </div>
          </CardContent>
        </Card>
      </AuthLayout>
    </>
  )
}
