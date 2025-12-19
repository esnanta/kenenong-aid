import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowLeft } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'

export default function RuleForm({ rule, errors = {} }) {
  const { props } = usePage()
  const isEdit = !!rule?.old_name
  const [formData, setFormData] = useState({
    name: rule?.name || '',
    className: rule?.class_name || '',
  })

  const handleSubmit = (e) => {
    e.preventDefault()

    const url = isEdit ? `/rule/${rule.old_name}/update` : '/rule/create'
    const method = isEdit ? 'put' : 'post'

    router[method](
      url,
      formData,
      {
        onSuccess: () => {
          toast.success(isEdit ? 'Rule updated successfully' : 'Rule created successfully')
        },
        onError: () => {
          toast.error('Please check the form for errors')
        },
      },
    )
  }

  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={isEdit ? 'Edit Rule' : 'Create Rule'} />

      <div className="space-y-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0">
            <div className="space-y-1.5">
              <CardTitle>
                {isEdit ? 'Edit Rule' : 'Create Rule'}
              </CardTitle>
              <CardDescription>
                {isEdit ? 'Update rule information' : 'Add a new authorization rule'}
              </CardDescription>
            </div>
            <Link href="/rule">
              <Button variant="ghost" size="icon">
                <ArrowLeft className="h-4 w-4" />
              </Button>
            </Link>
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
                  placeholder="e.g., isAuthor, isOwner"
                  disabled={isEdit}
                />
                {errors.name && (
                  <p className="text-sm text-destructive">{errors.name[0]}</p>
                )}
                <p className="text-sm text-muted-foreground">
                  A unique identifier for this rule
                </p>
              </div>

              <div className="space-y-2">
                <Label htmlFor="className">
                  Class Name
                  {' '}
                  <span className="text-destructive">*</span>
                </Label>
                <Input
                  id="className"
                  value={formData.className}
                  onChange={e => setFormData({ ...formData, className: e.target.value })}
                  placeholder="e.g., app\\rbac\\AuthorRule"
                  className="font-mono"
                />
                {errors.className && (
                  <p className="text-sm text-destructive">{errors.className[0]}</p>
                )}
                <p className="text-sm text-muted-foreground">
                  Full namespaced class name that implements yii\rbac\Rule
                </p>
              </div>

              <div className="flex gap-2 pt-4">
                <Button type="submit">
                  {isEdit ? 'Update Rule' : 'Create Rule'}
                </Button>
                <Link href="/rule">
                  <Button type="button" variant="outline">
                    Cancel
                  </Button>
                </Link>
              </div>
            </form>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Example Rule Class</CardTitle>
            <CardDescription>Sample PHP class for creating a custom rule</CardDescription>
          </CardHeader>
          <CardContent>
            <pre className="bg-muted p-4 rounded-md overflow-x-auto text-sm">
              <code>
                {`<?php
namespace app\\rbac;

use yii\\rbac\\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        return isset($params['post'])
            ? $params['post']->created_by == $user
            : false;
    }
}`}
              </code>
            </pre>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  )
}
