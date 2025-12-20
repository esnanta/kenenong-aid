import { Head, Link, usePage } from '@inertiajs/react'
import { ArrowLeft, Edit, FileText } from 'lucide-react'
import {DashboardLayout} from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Separator } from '@/components/ui/separator.tsx'

export default function RuleView({ rule }) {
  const { props } = usePage()

  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={`Rule: ${rule.name}`} />

      <div className="space-y-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
            <CardTitle className="flex items-center gap-2">
              <FileText className="h-6 w-6 text-purple-500" />
              {rule.name}
            </CardTitle>
            <div className="flex items-center gap-2">
              <Link href="/rule">
                <Button variant="outline" size="sm">
                  <ArrowLeft className="mr-2 h-4 w-4" />
                  Back
                </Button>
              </Link>
              <Link href={`/rule/${rule.name}/update`}>
                <Button size="sm">
                  <Edit className="mr-2 h-4 w-4" />
                  Edit
                </Button>
              </Link>
            </div>
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
