import { Head, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/components/layouts/DashboardLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { ThemeToggle } from '@/components/ThemeToggle';
import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { addCsrfToData } from '@/lib/csrf';

export default function Settings({ user }) {
  const { props } = usePage();
  const { data, setData, put, processing } = useForm({
    notifications: {
      email: true,
      push: false,
      sms: false,
    },
    theme: 'light',
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
    
    router.put('/dashboard/settings', formData, {
      onSuccess: () => {
        toast.success('Settings updated successfully');
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
      <Head title="Settings | Yii2 - Modern Starter Kit" />
      <DashboardLayout user={props.user}>
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Settings</h1>
          <p className="text-muted-foreground">
            Manage your account settings and preferences
          </p>
        </div>

        <Tabs defaultValue="general" className="space-y-4">
          <TabsList>
            <TabsTrigger value="general">General</TabsTrigger>
            <TabsTrigger value="notifications">Notifications</TabsTrigger>
            <TabsTrigger value="security">Security</TabsTrigger>
            <TabsTrigger value="appearance">Appearance</TabsTrigger>
          </TabsList>

          <TabsContent value="general" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>General Settings</CardTitle>
                <CardDescription>
                  Manage your general account settings
                </CardDescription>
              </CardHeader>
              <CardContent>
                <form onSubmit={submit} className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="language">Language</Label>
                    <Input id="language" defaultValue="English" disabled={processing} />
                  </div>
                  <Button type="submit" disabled={processing}>
                    Save Changes
                  </Button>
                </form>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="notifications" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>Notifications</CardTitle>
                <CardDescription>
                  Configure how you receive notifications
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="flex items-center justify-between">
                  <div className="space-y-0.5">
                    <Label htmlFor="email-notifications">Email Notifications</Label>
                    <p className="text-sm text-muted-foreground">
                      Receive notifications via email
                    </p>
                  </div>
                  <Switch
                    id="email-notifications"
                    checked={data.notifications.email}
                    onCheckedChange={(checked) =>
                      setData('notifications', { ...data.notifications, email: checked })
                    }
                  />
                </div>
                <div className="flex items-center justify-between">
                  <div className="space-y-0.5">
                    <Label htmlFor="push-notifications">Push Notifications</Label>
                    <p className="text-sm text-muted-foreground">
                      Receive push notifications
                    </p>
                  </div>
                  <Switch
                    id="push-notifications"
                    checked={data.notifications.push}
                    onCheckedChange={(checked) =>
                      setData('notifications', { ...data.notifications, push: checked })
                    }
                  />
                </div>
                <Button onClick={submit} disabled={processing}>
                  Save Changes
                </Button>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="security" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>Security</CardTitle>
                <CardDescription>
                  Manage your security settings
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="current-password">Current Password</Label>
                  <Input id="current-password" type="password" disabled={processing} />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="new-password">New Password</Label>
                  <Input id="new-password" type="password" disabled={processing} />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="confirm-password">Confirm New Password</Label>
                  <Input id="confirm-password" type="password" disabled={processing} />
                </div>
                <Button disabled={processing}>Update Password</Button>
              </CardContent>
            </Card>
          </TabsContent>

          <TabsContent value="appearance" className="space-y-4">
            <Card>
              <CardHeader>
                <CardTitle>Appearance</CardTitle>
                <CardDescription>
                  Customize the appearance of the application
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="flex items-center justify-between">
                  <div className="space-y-0.5">
                    <Label>Theme</Label>
                    <p className="text-sm text-muted-foreground">
                      Choose your preferred theme
                    </p>
                  </div>
                  <ThemeToggle />
                </div>
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
      </DashboardLayout>
    </>
  );
}

