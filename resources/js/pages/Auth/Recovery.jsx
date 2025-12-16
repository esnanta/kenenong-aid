import { Head, Link, useForm } from '@inertiajs/react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

export default function Recovery({ errors = {} }) {
  const { data, setData, post, processing } = useForm({
    'RecoveryForm[email]': '',
  })

  const handleSubmit = (e) => {
    e.preventDefault()
    post('/user/recovery/request')
  }

  return (
    <>
      <Head title="Password Recovery" />

      <div className="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <Card className="w-full max-w-md">
          <CardHeader>
            <CardTitle>Password Recovery</CardTitle>
            <CardDescription>Enter your email to receive password reset instructions</CardDescription>
          </CardHeader>

          <form onSubmit={handleSubmit}>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  value={data['RecoveryForm[email]']}
                  onChange={e => setData('RecoveryForm[email]', e.target.value)}
                  required
                />
                {errors.email && <p className="text-sm text-red-500">{errors.email[0]}</p>}
              </div>
            </CardContent>

            <CardFooter className="flex flex-col space-y-4">
              <Button type="submit" className="w-full" disabled={processing}>
                {processing ? 'Sending...' : 'Send Recovery Email'}
              </Button>

              <div className="text-sm text-center">
                <Link href="/user/security/login" className="text-primary hover:underline">
                  Back to Login
                </Link>
              </div>
            </CardFooter>
          </form>
        </Card>
      </div>
    </>
  )
}
