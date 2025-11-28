import { Head, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/components/layouts/DashboardLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm as useHookForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { addCsrfToData } from '@/lib/csrf';

const profileSchema = z.object({
  name: z.string().min(3, 'Name must be at least 3 characters'),
  email: z.string().email('Invalid email address'),
});

export default function Profile({ user }) {
  const { props } = usePage();
  const inertiaForm = useForm({
    name: user?.name || '',
    email: user?.email || '',
  });

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
  });

  const onSubmit = (data) => {
    // Get CSRF token from Inertia shared props (more reliable than meta tags)
    const csrfToken = props.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const csrfParam = props.csrfParam || document.querySelector('meta[name="csrf-param"]')?.getAttribute('content');
    
    const formData = {
      ...data,
      ...(csrfToken && csrfParam ? { [csrfParam]: csrfToken } : {}),
    };
    
    // Use router.put directly to ensure data is sent correctly
    router.put('/dashboard/profile', formData, {
      onSuccess: () => {
        toast.success('Profile updated successfully');
      },
      onError: (errors) => {
        Object.values(errors).forEach((error) => {
          if (Array.isArray(error)) {
            error.forEach((err) => toast.error(err));
          } else {
            toast.error(error);
          }
        });
      },
    });
  };

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
  );
}
