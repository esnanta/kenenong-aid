import { Link, useForm, Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AuthLayout from '@/components/layouts/AuthLayout';
import { ArrowLeft } from 'lucide-react';
import { toast } from 'sonner';
import { addCsrfToData } from '@/lib/csrf';

export default function ForgotPassword() {
  const { props } = usePage();
  const { data, setData, post, processing } = useForm({
    email: '',
  });

  const submit = (e) => {
    e.preventDefault();
    
    // Get CSRF token from Inertia shared props (more reliable than meta tags)
    const csrfToken = props.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const csrfParam = props.csrfParam || document.querySelector('meta[name="csrf-param"]')?.getAttribute('content');
    
    const formData = {
      ...data,
      ...(csrfToken && csrfParam ? { [csrfParam]: csrfToken } : {}),
    };
    
    router.post('/auth/forgot-password', formData, {
      onSuccess: () => {
        toast.success('Password reset link sent to your email');
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          toast.error(error);
        });
      },
    });
  };

  return (
    <>
      <Head title="Forgot Password | Yii2 - Modern Starter Kit" />
      <AuthLayout>
      <Card className="border-0 shadow-none px-6">
        <CardHeader className="space-y-1 px-0">
          <CardTitle className="text-2xl font-semibold tracking-tight">Forgot password</CardTitle>
          <CardDescription className="text-base">
            Enter your email address and we'll send you a link to reset your password
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
                onChange={(e) => setData('email', e.target.value)}
                disabled={processing}
                autoFocus
                required
                placeholder="Enter your email"
              />
            </div>

            <Button type="submit" variant="default" className="w-full" disabled={processing}>
              {processing ? 'Sending...' : 'Send reset link'}
            </Button>
          </form>

          <div className="mt-6 text-center">
            <Link
              href="/auth/login"
              className="text-sm text-primary hover:underline font-medium flex items-center justify-center"
            >
              <ArrowLeft className="mr-2 h-4 w-4" />
              Back to login
            </Link>
          </div>
        </CardContent>
      </Card>
    </AuthLayout>
    </>
  );
}

