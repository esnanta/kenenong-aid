import { Head, Link, router } from '@inertiajs/react'
import { useState } from 'react'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

export default function Register({ form, errors, enableEmailConfirmation }) {
  const [data, setData] = useState({
    email: form?.email || '',
    username: form?.username || '',
    name: form?.name || '',
    password: '',
  })
  const [processing, setProcessing] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()
    setProcessing(true)

    const formDataWithCsrf = addCsrfToData(data)

    router.post('/register', formDataWithCsrf, {
      onFinish: () => setProcessing(false),
    })
  }

  return (
    <>
      <Head title="Sign up" />
      <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle className="text-2xl">Sign up</CardTitle>
            <CardDescription>
              {enableEmailConfirmation
                ? 'Create your account. You\'ll need to confirm your email address.'
                : 'Create your account to get started'}
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  name="email"
                  type="email"
                  autoComplete="email"
                  required
                  autoFocus
                  value={data.email}
                  onChange={e => setData({ ...data, email: e.target.value })}
                  className={errors?.email ? 'border-destructive' : ''}
                />
                {errors?.email && (
                  <p className="text-sm text-destructive">{errors.email[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="username">Username</Label>
                <Input
                  id="username"
                  name="username"
                  type="text"
                  autoComplete="username"
                  required
                  value={data.username}
                  onChange={e => setData({ ...data, username: e.target.value })}
                  className={errors?.username ? 'border-destructive' : ''}
                />
                {errors?.username && (
                  <p className="text-sm text-destructive">{errors.username[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="name">Full Name</Label>
                <Input
                  id="name"
                  name="name"
                  type="text"
                  required
                  value={data.name}
                  onChange={e => setData({ ...data, name: e.target.value })}
                  className={errors?.name ? 'border-destructive' : ''}
                />
                {errors?.name && (
                  <p className="text-sm text-destructive">{errors.name[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="password">Password</Label>
                <Input
                  id="password"
                  name="password"
                  type="password"
                  autoComplete="new-password"
                  required
                  value={data.password}
                  onChange={e => setData({ ...data, password: e.target.value })}
                  className={errors?.password ? 'border-destructive' : ''}
                />
                {errors?.password && (
                  <p className="text-sm text-destructive">{errors.password[0]}</p>
                )}
              </div>

              <Button type="submit" className="w-full" disabled={processing}>
                {processing ? 'Creating account...' : 'Sign up'}
              </Button>
            </form>

            <div className="mt-4 text-center text-sm">
              <span className="text-muted-foreground">Already registered? </span>
              <Link href="/login" className="text-primary hover:underline">
                Sign in
              </Link>
            </div>
          </CardContent>
        </Card>
      </div>
    </>
  )
}
