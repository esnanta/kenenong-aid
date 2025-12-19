import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowLeft } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select.tsx'
import { Textarea } from '@/components/ui/textarea.tsx'

export default function PermissionForm({ permission, errors = {}, rules = [] }) {
  const { props } = usePage()
  const isEdit = !!permission?.old_name
  const [formData, setFormData] = useState({
    name: permission?.name || '',
    description: permission?.description || '',
    ruleName: permission?.rule_name || '',
  })

  const handleSubmit = (e) => {
    e.preventDefault()

    const url = isEdit ? `/permission/${permission.old_name}/update` : '/permission/create'
    const method = isEdit ? 'put' : 'post'

    router[method](
      url,
      formData,
      {
        onSuccess: () => {
          toast.success(isEdit ? 'Permission updated successfully' : 'Permission created successfully')
        },
        onError: (errors) => {
          toast.error('Please check the form for errors')
        },
      },
    )
  }

  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={isEdit ? 'Edit Permission' : 'Create Permission'} />

      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/permission">
            <Button variant="ghost" size="icon">
              <ArrowLeft className="h-4 w-4" />
            </Button>
          </Link>
          <div>
            <h1 className="text-3xl font-bold tracking-tight">
              {isEdit ? 'Edit Permission' : 'Create Permission'}
            </h1>
            <p className="text-muted-foreground">
              {isEdit ? 'Update permission information' : 'Add a new permission to the system'}
            </p>
          </div>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Permission Information</CardTitle>
            <CardDescription>
              Enter the details for the permission
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">
                  Name
                  {' '}
                  <span className="text-destructive">*</span>
                </Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={e => setFormData({ ...formData, name: e.target.value })}
                  placeholder="e.g., create-post, delete-user"
                  disabled={isEdit}
                />
                {errors.name && (
                  <p className="text-sm text-destructive">{errors.name[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea
                  id="description"
                  value={formData.description}
                  onChange={e => setFormData({ ...formData, description: e.target.value })}
                  placeholder="Brief description of this permission"
                  rows={3}
                />
                {errors.description && (
                  <p className="text-sm text-destructive">{errors.description[0]}</p>
                )}
              </div>

              <div className="space-y-2">
                <Label htmlFor="ruleName">Rule</Label>
                <Select
                  value={formData.ruleName}
                  onValueChange={value => setFormData({ ...formData, ruleName: value })}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Select a rule (optional)" />
                  </SelectTrigger>
                  <SelectContent>
                    {rules.map(rule => (
                      <SelectItem key={rule.value} value={rule.value}>
                        {rule.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                {errors.ruleName && (
                  <p className="text-sm text-destructive">{errors.ruleName[0]}</p>
                )}
              </div>

              <div className="flex gap-2 pt-4">
                <Button type="submit">
                  {isEdit ? 'Update Permission' : 'Create Permission'}
                </Button>
                <Link href="/permission">
                  <Button type="button" variant="outline">
                    Cancel
                  </Button>
                </Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  )
}
