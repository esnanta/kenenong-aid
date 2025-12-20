import { Head, Link, router } from '@inertiajs/react'
import { useState } from 'react'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

export default function Resend({ form, errors }) {
  const [data, setData] = useState({
    email: form?.email || '',
  })
  const [processing, setProcessing] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()
    setProcessing(true)

    const formDataWithCsrf = addCsrfToData(data)

    router.post('/resend', formDataWithCsrf, {
      onFinish: () => setProcessing(false),
    })
  }

  return (
    <>
      <Head title="Resend confirmation" />
      <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle className="text-2xl">Resend confirmation</CardTitle>
            <CardDescription>
              Enter your email address to receive a new confirmation link
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
                  placeholder="your@email.com"
                  value={data.email}
                  onChange={e => setData({ ...data, email: e.target.value })}
                  className={errors?.email ? 'border-destructive' : ''}
                />
                {errors?.email && (
                  <p className="text-sm text-destructive">{errors.email[0]}</p>
                )}
              </div>

              <Button type="submit" className="w-full" disabled={processing}>
                {processing ? 'Sending...' : 'Resend confirmation'}
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
