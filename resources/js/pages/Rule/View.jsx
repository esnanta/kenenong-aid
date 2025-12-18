import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, FileText } from 'lucide-react'
import DashboardLayout from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Separator } from '@/components/ui/separator.tsx'

export default function RuleView({ rule }) {
  const { props } = usePage()

  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={`Rule: ${rule.name}`} />

      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/rule">
            <Button variant="ghost" size="icon">
              <ArrowLeft className="h-4 w-4" />
            </Button>
          </Link>
          <div className="flex-1">
            <h1 className="text-3xl font-bold tracking-tight flex items-center gap-2">
              <FileText className="h-8 w-8 text-purple-500" />
              {rule.name}
            </h1>
            <p className="text-muted-foreground">Authorization rule details</p>
          </div>
          <Link href={`/rule/${rule.name}/update`}>
            <Button>Edit Rule</Button>
          </Link>
        </div>

        <Card>
          <CardHeader>
            <CardTitle>Rule Information</CardTitle>
            <CardDescription>Details about this authorization rule</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div>
              <p className="text-sm font-medium text-muted-foreground">Name</p>
              <p className="text-base font-semibold">{rule.name}</p>
            </div>
            <Separator />
            <div>
              <p className="text-sm font-medium text-muted-foreground">Class Name</p>
              <Badge variant="secondary" className="font-mono text-xs">
                {rule.class_name}
              </Badge>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>About Rules</CardTitle>
            <CardDescription>How authorization rules work</CardDescription>
          </CardHeader>
          <CardContent className="prose prose-sm">
            <p className="text-muted-foreground">
              Rules are PHP classes that implement custom logic to determine whether a user
              has permission to perform an action. They can be assigned to roles and permissions
              to add dynamic authorization checks beyond simple access control.
            </p>
          </CardContent>
        </Card>
      </div>
    </DashboardLayout>
  )
}
