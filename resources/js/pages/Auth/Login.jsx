import { Head, Link, router } from '@inertiajs/react'
import { useState } from 'react'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Checkbox } from '@/components/ui/checkbox.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

export default function Login({ form, errors }) {
  const [data, setData] = useState({
    login: form?.login || '',
    password: '',
    rememberMe: form?.rememberMe || false,
  })
  const [processing, setProcessing] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()
    setProcessing(true)

    const formDataWithCsrf = addCsrfToData(data)

    router.post('/login', formDataWithCsrf, {
      onFinish: () => setProcessing(false),
    })
  }

  return (
    <>
      <Head title="Sign in" />
      <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle className="text-2xl">Sign in</CardTitle>
            <CardDescription>Enter your credentials to access your account</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="login">Email or Username</Label>
                <Input
                  id="login"
                  name="login"
                  type="text"
                  autoComplete="username"
                  required
                  autoFocus
                  value={data.login}
                  onChange={e => setData({ ...data, login: e.target.value })}
                  className={errors?.login ? 'border-destructive' : ''}
                />
                {errors?.login && (
                  <p className="text-sm text-destructive">{errors.login[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <Label htmlFor="password">Password</Label>
                  <Link
                    href="/forgot-password"
                    className="text-sm text-muted-foreground hover:text-primary"
                  >
                    Forgot password?
                  </Link>
                </div>
                <Input
                  id="password"
                  name="password"
                  type="password"
                  autoComplete="current-password"
                  required
                  value={data.password}
                  onChange={e => setData({ ...data, password: e.target.value })}
                  className={errors?.password ? 'border-destructive' : ''}
                />
                {errors?.password && (
                  <p className="text-sm text-destructive">{errors.password[0]}</p>
                )}
              </div>

              <div className="flex items-center space-x-2">
                <Checkbox
                  id="rememberMe"
                  checked={data.rememberMe}
                  onCheckedChange={checked => setData({ ...data, rememberMe: checked })}
                />
                <Label htmlFor="rememberMe" className="text-sm font-normal cursor-pointer">
                  Remember me
                </Label>
              </div>

              <Button type="submit" className="w-full" disabled={processing}>
                {processing ? 'Signing in...' : 'Sign in'}
              </Button>
            </form>

            <div className="mt-4 text-center text-sm">
              <span className="text-muted-foreground">Don't have an account? </span>
              <Link href="/register" className="text-primary hover:underline">
                Sign up
              </Link>
            </div>

            <div className="mt-2 text-center text-sm">
              <Link href="/resend" className="text-muted-foreground hover:text-primary">
                Didn't receive confirmation message?
              </Link>
            </div>
          </CardContent>
        </Card>
      </div>
    </>
  )
}
