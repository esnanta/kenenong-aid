import { createInertiaApp, router } from '@inertiajs/react'
import axios from 'axios'
import { lazy, Suspense } from 'react'
import { createRoot } from 'react-dom/client'
import { toast, Toaster } from 'sonner'
import ErrorBoundary from './components/ErrorBoundary'
import { ThemeProvider } from './components/ThemeProvider'
import { addCsrfToData, getCsrfToken } from './lib/csrf'
import Dashboard from './pages/Dashboard/Index'

import Home from './pages/Home'
import NotFound from './pages/NotFound'
import '../css/app.css'

// Lazy load pages for code splitting
const Login = lazy(() => import('./pages/Auth/Login'))
const Register = lazy(() => import('./pages/Auth/Register'))
const ForgotPassword = lazy(() => import('./pages/Auth/ForgotPassword'))
const ResetPassword = lazy(() => import('./pages/Auth/ResetPassword'))
const Resend = lazy(() => import('./pages/Auth/Resend'))
const Profile = lazy(() => import('./pages/Dashboard/Profile'))
const Settings = lazy(() => import('./pages/Dashboard/Settings'))
const Billing = lazy(() => import('./pages/Dashboard/Billing'))
const UserIndex = lazy(() => import('./pages/User/Index'))
const UserForm = lazy(() => import('./pages/User/Form'))
const UserView = lazy(() => import('./pages/User/View'))
const DisasterIndex = lazy(() => import('./pages/Disaster/Index'))
const DisasterForm = lazy(() => import('./pages/Disaster/Form'))
const DisasterView = lazy(() => import('./pages/Disaster/View'))
const DisasterStatusIndex = lazy(() => import('./pages/DisasterStatus/Index'))
const DisasterStatusForm = lazy(() => import('./pages/DisasterStatus/Form'))
const DisasterStatusView = lazy(() => import('./pages/DisasterStatus/View'))
const DisasterTypeIndex = lazy(() => import('./pages/DisasterType/Index'))
const DisasterTypeForm = lazy(() => import('./pages/DisasterType/Form'))
const DisasterTypeView = lazy(() => import('./pages/DisasterType/View'))
// RBAC pages
const RoleIndex = lazy(() => import('./pages/Role/Index'))
const RoleForm = lazy(() => import('./pages/Role/Form'))
const RoleView = lazy(() => import('./pages/Role/View'))
const PermissionIndex = lazy(() => import('./pages/Permission/Index'))
const PermissionForm = lazy(() => import('./pages/Permission/Form'))
const PermissionView = lazy(() => import('./pages/Permission/View'))
const RuleIndex = lazy(() => import('./pages/Rule/Index'))
const RuleForm = lazy(() => import('./pages/Rule/Form'))
const RuleView = lazy(() => import('./pages/Rule/View'))

// CSRF Header setup for Axios
const token = getCsrfToken()
if (token) {
  axios.defaults.headers.common['X-CSRF-Token'] = token
}
else {
  console.warn('CSRF token not found for Axios header')
}

createInertiaApp({
  progress: {
    delay: 250,
    color: '#29d',
    includeCSS: true,
    showSpinner: true,
  },
  resolve: (name) => {
    const pages = {
      'Home': Home,
      'Auth/Login': Login,
      'Auth/Register': Register,
      'Auth/ForgotPassword': ForgotPassword,
      'Auth/ResetPassword': ResetPassword,
      'Auth/Resend': Resend,
      'Dashboard/Index': Dashboard,
      'Dashboard/Profile': Profile,
      'Dashboard/Settings': Settings,
      'Dashboard/Billing': Billing,
      'Role/Index': RoleIndex,
      'Role/Form': RoleForm,
      'Role/View': RoleView,
      'Permission/Index': PermissionIndex,
      'Permission/Form': PermissionForm,
      'Permission/View': PermissionView,
      'Rule/Index': RuleIndex,
      'Rule/Form': RuleForm,
      'Rule/View': RuleView,
      'User/Index': UserIndex,
      'User/Form': UserForm,
      'User/View': UserView,
      'Disaster/Index': DisasterIndex,
      'Disaster/Form': DisasterForm,
      'Disaster/View': DisasterView,
      'DisasterStatus/Index': DisasterStatusIndex,
      'DisasterStatus/Form': DisasterStatusForm,
      'DisasterStatus/View': DisasterStatusView,
      'DisasterType/Index': DisasterTypeIndex,
      'DisasterType/Form': DisasterTypeForm,
      'DisasterType/View': DisasterTypeView,
      'NotFound': NotFound,
    }
    return pages[name] || NotFound
  },
  title: title => title ? `${title}` : 'Logistic Disaster Aid',
  setup({ el, App, props }) {
    // Store initial page props for CSRF token access globally
    if (typeof window !== 'undefined') {
      window.__INERTIA_PAGE__ = props.initialPage
    }

    // Update page reference when Inertia navigates to get fresh CSRF tokens
    router.on('navigate', (event) => {
      if (typeof window !== 'undefined' && event.detail?.page) {
        window.__INERTIA_PAGE__ = event.detail.page
      }
    })

    // Configure Inertia router to automatically include CSRF token in all POST/PUT/PATCH/DELETE requests
    router.on('start', (event) => {
      const visit = event.detail?.visit || event.detail
      const method = (visit?.method || 'GET').toUpperCase()

      // CRITICAL: Only add CSRF token for POST/PUT/PATCH/DELETE requests
      if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method) && visit?.data !== undefined) {
        visit.data = addCsrfToData(visit.data)
      }
    })

    // Global error handler for 400 Bad Request errors
    router.on('error', (event) => {
      const error = event.detail

      if (error?.status === 400 || error?.response?.status === 400) {
        const errors = error?.errors || error?.response?.data?.errors

        if (errors && typeof errors === 'object') {
          Object.values(errors).forEach((errorMsg) => {
            if (Array.isArray(errorMsg)) {
              errorMsg.forEach(err => toast.error(err))
            }
            else if (typeof errorMsg === 'string') {
              toast.error(errorMsg)
            }
            else if (errorMsg && typeof errorMsg === 'object' && errorMsg.message) {
              toast.error(errorMsg.message)
            }
          })
        }
        else if (error?.message) {
          toast.error(error.message)
        }
        else {
          toast.error('Bad Request - Please check your input and try again')
        }
      }
    })

    // Also handle errors from Inertia responses
    if (props.initialPage?.props?.errors) {
      const serverErrors = props.initialPage.props.errors
      if (typeof serverErrors === 'object') {
        Object.values(serverErrors).forEach((errorMsg) => {
          if (Array.isArray(errorMsg)) {
            errorMsg.forEach(err => toast.error(err))
          }
          else if (typeof errorMsg === 'string') {
            toast.error(errorMsg)
          }
        })
      }
    }

    const root = createRoot(el)
    root.render(
      <ErrorBoundary>
        <ThemeProvider attribute="class" defaultTheme="system" enableSystem>
          <Suspense fallback={<div className="min-h-screen flex items-center justify-center"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>}>
            <App {...props} />
          </Suspense>
          <Toaster position="top-right" />
        </ThemeProvider>
      </ErrorBoundary>,
    )

    // FIX: Remove the PHP loader once React is ready
    const loader = document.getElementById('app-loader')
    if (loader) {
      loader.style.opacity = '0'
      setTimeout(() => loader.remove(), 300)
    }
  },
})
