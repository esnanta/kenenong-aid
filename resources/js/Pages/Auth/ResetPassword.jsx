import { Head, Link, router } from '@inertiajs/react'
import { useState } from 'react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

export default function ResetPassword({ errors, id, code }) {
  const [data, setData] = useState({
    password: '',
    password_confirm: '',
  })
  const [processing, setProcessing] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()
    setProcessing(true)

    router.post(`/reset-password/${id}/${code}`, data, {
      onFinish: () => setProcessing(false),
    })
  }

  const passwordsMatch = data.password === data.password_confirm || !data.password_confirm

  return (
    <>
      <Head title="Reset password" />
      <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle className="text-2xl">Reset password</CardTitle>
            <CardDescription>Enter your new password</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="password">New Password</Label>
                <Input
                  id="password"
                  name="password"
                  type="password"
                  autoComplete="new-password"
                  required
                  autoFocus
                  value={data.password}
                  onChange={e => setData({ ...data, password: e.target.value })}
                  className={errors?.password ? 'border-destructive' : ''}
                />
                {errors?.password && (
                  <p className="text-sm text-destructive">{errors.password[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="password_confirm">Confirm Password</Label>
                <Input
                  id="password_confirm"
                  name="password_confirm"
                  type="password"
                  autoComplete="new-password"
                  required
                  value={data.password_confirm}
                  onChange={e => setData({ ...data, password_confirm: e.target.value })}
                  className={!passwordsMatch ? 'border-destructive' : ''}
                />
                {!passwordsMatch && (
                  <p className="text-sm text-destructive">Passwords do not match</p>
                )}
              </div>

              <Button type="submit" className="w-full" disabled={processing || !passwordsMatch}>
                {processing ? 'Resetting...' : 'Reset password'}
              </Button>
            </form>

            <div className="mt-4 text-center text-sm">
              <Link href="/login" className="text-primary hover:underline">
                Back to sign in
              </Link>
            </div>
          </CardContent>
        </Card>
      </div>
    </>
  )
}
