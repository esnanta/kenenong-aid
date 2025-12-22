import { Head, Link, router, usePage } from '@inertiajs/react'
import { ArrowLeft, Lock, Shield } from 'lucide-react'
import { useState } from 'react'
import { toast } from 'sonner'
import { DashboardLayout } from '@/components/layouts/DashboardLayout.jsx'
import { Badge } from '@/components/ui/badge.tsx'
import { Button } from '@/components/ui/button.tsx'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card.tsx'
import { Checkbox } from '@/components/ui/checkbox.tsx'
import { Input } from '@/components/ui/input.tsx'
import { Label } from '@/components/ui/label.tsx'
import { ScrollArea } from '@/components/ui/scroll-area.tsx'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select.tsx'
import { Textarea } from '@/components/ui/textarea.tsx'
import { addCsrfToData } from '@/lib/csrf' // Import addCsrfToData

const EMPTY_ERRORS = {}
/** @type {RuleOption[]} */
const EMPTY_RULES = []
/** @type {UnassignedItem[]} */
const EMPTY_ITEMS = []

/**
 * @typedef {object} Role
 * @property {string} name - The name of the role.
 * @property {string} description - The description of the role.
 * @property {string} rule_name - The name of the rule associated with the role.
 * @property {string | null} old_name - The original name of the role, if it was edited.
 * @property {string[]} children - An array of child permissions or roles.
 */

/**
 * @typedef {object} RuleOption
 * @property {string} value - The value of the rule option.
 * @property {string} label - The label of the rule option.
 */

/**
 * @typedef {object} UnassignedItem
 * @property {string} name - The name of the unassigned item.
 * @property {'role' | 'permission'} type - The type of the unassigned item (role or permission).
 * @property {string} description - The description of the unassigned item.
 * @property {string} label - The label of the unassigned item.
 */

/**
 * @param {object} props
 * @param {Role} [props.role] - The role object.
 * @param {{[key: string]: string[]}} [props.errors] - An object containing form errors.
 * @param {RuleOption[]} [props.rules] - An array of available rule options.
 * @param {UnassignedItem[]} [props.unassignedItems] - An array of unassigned items (permissions or roles).
 */
export default function RoleForm({ role, errors = EMPTY_ERRORS, rules = EMPTY_RULES, unassignedItems = EMPTY_ITEMS }) {
  const { props } = usePage()
  const isEdit = !!role?.old_name
  const [formData, setFormData] = useState({
    name: role?.name || '',
    description: role?.description || '',
    ruleName: role?.rule_name || 'none',
    children: role?.children || [],
  })

  const handleChildToggle = (itemName) => {
    setFormData((prev) => {
      const isSelected = prev.children.includes(itemName)
      return {
        ...prev,
        children: isSelected
          ? prev.children.filter(c => c !== itemName)
          : [...prev.children, itemName],
      }
    })
  }

  const handleSubmit = (e) => {
    e.preventDefault()

    const formDataWithCsrf = addCsrfToData(formData)

    const url = isEdit ? `/roles/${role.old_name}/update` : '/roles/create'
    const method = isEdit ? 'put' : 'post'

    router[method](
      url,
      formDataWithCsrf,
      {
        onSuccess: () => {
          toast.success(isEdit ? 'Role updated successfully' : 'Role created successfully')
        },
        onError: () => {
          toast.error('Please check the form for errors')
        },
      },
    )
  }

  return (
    <DashboardLayout user={props.auth?.user}>
      <Head title={isEdit ? 'Edit Role' : 'Create Role'} />

      <div className="space-y-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-4">
            <CardTitle>Role Information</CardTitle>
            <Link href="/role">
              <Button variant="outline" size="sm">
                <ArrowLeft className="mr-2 h-4 w-4" />
                Back
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
                  placeholder="e.g., admin, moderator"
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
                  placeholder="Brief description of this role"
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

              <div className="space-y-2">
                <Label>Permissions & Roles</Label>
                <p className="text-sm text-muted-foreground mb-2">
                  Select permissions and roles to assign to this role
                </p>
                <Card>
                  <ScrollArea className="h-[300px] p-4">
                    <div className="space-y-3">
                      {unassignedItems.length > 0
                        ? (
                            unassignedItems.map(item => (
                              <div
                                key={item.name}
                                className="flex items-start space-x-3 p-3 rounded-lg border bg-card hover:bg-accent/50 transition-colors"
                              >
                                <Checkbox
                                  id={`child-${item.name}`}
                                  checked={formData.children.includes(item.name)}
                                  onCheckedChange={() => handleChildToggle(item.name)}
                                />
                                <div className="flex-1 space-y-1">
                                  <label
                                    htmlFor={`child-${item.name}`}
                                    className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer flex items-center gap-2"
                                  >
                                    {item.type === 'role'
                                      ? (
                                          <Shield className="h-4 w-4 text-blue-500" />
                                        )
                                      : (
                                          <Lock className="h-4 w-4 text-green-500" />
                                        )}
                                    {item.name}
                                    <Badge variant="outline" className="ml-auto">
                                      {item.type}
                                    </Badge>
                                  </label>
                                  {item.description && (
                                    <p className="text-sm text-muted-foreground">
                                      {item.description}
                                    </p>
                                  )}
                                </div>
                              </div>
                            ))
                          )
                        : (
                            <p className="text-sm text-muted-foreground text-center py-4">
                              No available permissions or roles to assign
                            </p>
                          )}
                    </div>
                  </ScrollArea>
                </Card>
                {formData.children.length > 0 && (
                  <p className="text-sm text-muted-foreground">
                    {formData.children.length}
                    {' '}
                    item(s) selected
                  </p>
                )}
              </div>

              <div className="flex gap-2 pt-4">
                <Button type="submit">
                  {isEdit ? 'Update Role' : 'Create Role'}
                </Button>
                <Link href="/role">
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
