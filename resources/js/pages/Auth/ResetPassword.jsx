import { Head, Link, router, useForm, usePage } from '@inertiajs/react'
import { toast } from 'sonner'
import AuthLayout from '@/components/layouts/AuthLayout'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

export default function ResetPassword({ token }) {
  const { props } = usePage()
  const { data, setData, post, processing } = useForm({
    token: token || '',
    email: '',
    password: '',
    passwordConfirm: '',
  })

  const submit = (e) => {
    e.preventDefault()

    if (data.password !== data.passwordConfirm) {
      toast.error('Passwords do not match')
      return
    }

    // Get CSRF token from Inertia shared props (more reliable than meta tags)
    const csrfToken = props.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const csrfParam = props.csrfParam || document.querySelector('meta[name="csrf-param"]')?.getAttribute('content')

    const formData = {
      ...data,
      ...(csrfToken && csrfParam ? { [csrfParam]: csrfToken } : {}),
    }

    router.post('/auth/reset-password', formData, {
      onSuccess: () => {
        toast.success('Password reset successfully')
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          toast.error(error)
        })
      },
    })
  }

  return (
    <>
      <Head title="Reset Password | Yii2 - Modern Starter Kit" />
      <AuthLayout>
        <Card className="border-0 shadow-none">
          <CardHeader className="space-y-1 px-0">
            <CardTitle className="text-2xl font-semibold tracking-tight">Reset password</CardTitle>
            <CardDescription className="text-base">
              Enter your new password
            </CardDescription>
          </CardHeader>
          <CardContent className="px-0">
            <form onSubmit={submit} className="space-y-6">
              <div className="space-y-2">
                <Label htmlFor="email" className="text-sm font-medium">Email</Label>
                <Input
                  id="email"
                  type="email"
                  value={data.email}
                  onChange={e => setData('email', e.target.value)}
                  disabled={processing}
                  required
                  autoFocus
                  placeholder="Enter your email"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="password" className="text-sm font-medium">New Password</Label>
                <Input
                  id="password"
                  type="password"
                  value={data.password}
                  onChange={e => setData('password', e.target.value)}
                  disabled={processing}
                  required
                  placeholder="Enter your new password"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="passwordConfirm" className="text-sm font-medium">Confirm New Password</Label>
                <Input
                  id="passwordConfirm"
                  type="password"
                  value={data.passwordConfirm}
                  onChange={e => setData('passwordConfirm', e.target.value)}
                  disabled={processing}
                  required
                  placeholder="Confirm your new password"
                />
              </div>

              <Button type="submit" variant="default" className="w-full" disabled={processing}>
                {processing ? 'Resetting...' : 'Reset password'}
              </Button>
            </form>

            <div className="mt-6 text-center text-sm">
              <Link href="/auth/login" className="text-primary hover:underline font-medium">
                Back to login
              </Link>
            </div>
          </CardContent>
        </Card>
      </AuthLayout>
    </>
  )
}
