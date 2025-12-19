import { createInertiaApp, router } from '@inertiajs/react'
import axios from 'axios'
import { lazy, Suspense } from 'react'
import { createRoot } from 'react-dom/client'
import { toast, Toaster } from 'sonner'
import ErrorBoundary from './components/ErrorBoundary'
import { LoadingIndicator } from './components/LoadingIndicator'
import { ThemeProvider } from './components/ThemeProvider'
import '../css/app.css'

// Get CSRF token from meta tag
function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]')
  return meta ? meta.getAttribute('content') : null
}

function getCsrfParam() {
  const meta = document.querySelector('meta[name="csrf-param"]')
  return meta ? meta.getAttribute('content') : null
}

// Lazy load pages for code splitting
const Home = lazy(() => import('./pages/Home'))
const Login = lazy(() => import('./pages/Auth/Login'))
const Register = lazy(() => import('./pages/Auth/Register'))
const ForgotPassword = lazy(() => import('./pages/Auth/ForgotPassword'))
const ResetPassword = lazy(() => import('./pages/Auth/ResetPassword'))
const Resend = lazy(() => import('./pages/Auth/Resend'))
const Dashboard = lazy(() => import('./pages/Dashboard/Index'))
const Profile = lazy(() => import('./pages/Dashboard/Profile'))
const Settings = lazy(() => import('./pages/Dashboard/Settings'))
const Billing = lazy(() => import('./pages/Dashboard/Billing'))
const UserIndex = lazy(() => import('./pages/User/Index'))
const UserForm = lazy(() => import('./pages/User/Form'))
const UserView = lazy(() => import('./pages/User/View'))
const DisasterIndex = lazy(() => import('./pages/Disaster/Index'))
const DisasterForm = lazy(() => import('./pages/Disaster/Form'))
const DisasterView = lazy(() => import('./pages/Disaster/View'))
const NotFound = lazy(() => import('./pages/NotFound'))
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

// Ambil token dari head meta atau dari global props yang dibagikan oleh InertiaBootstrap
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (token) {
  axios.defaults.headers.common['X-CSRF-Token'] = token
}

createInertiaApp({
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
      'NotFound': NotFound,
    }
    return pages[name] || NotFound
  },
  title: title => title ? `${title}` : 'Yii2 - Modern Starter Kit',
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
    // IMPORTANT: Only add CSRF token to request body, NEVER to query params or GET requests
    router.on('start', (event) => {
      const visit = event.detail?.visit || event.detail
      const method = visit?.method || 'GET'
      const url = visit?.url || ''

      // SECURITY: Ensure CSRF token is NEVER in query parameters
      // Check if url is a string before calling includes
      if (url && typeof url === 'string' && url.includes('_csrf')) {
        console.error('SECURITY WARNING: CSRF token found in URL query params!', url)
        // Remove CSRF from URL if somehow it got there
        try {
          const urlObj = new URL(url, window.location.origin)
          if (urlObj.searchParams.has('_csrf')) {
            urlObj.searchParams.delete('_csrf')
            visit.url = urlObj.pathname + urlObj.search
            console.warn('Removed CSRF token from URL query params')
          }
        }
        catch {
          // URL parsing failed, ignore
        }
      }

      // CRITICAL: Only add CSRF token for POST/PUT/PATCH/DELETE requests in the request body
      // NEVER add CSRF token to GET requests or query parameters
      if ((method === 'POST' || method === 'PUT' || method === 'PATCH' || method === 'DELETE') && visit?.data !== undefined) {
        // Get CSRF token from Inertia shared props or meta tag
        const currentPage = window.__INERTIA_PAGE__ || props.initialPage
        const csrfToken = currentPage?.props?.csrfToken || getCsrfToken()
        const csrfParam = currentPage?.props?.csrfParam || getCsrfParam()

        if (csrfToken && csrfParam) {
          // Add CSRF token to the request data (body), NOT to URL
          if (visit.data instanceof FormData) {
            // Check if already added
            if (!visit.data.has(csrfParam)) {
              visit.data.append(csrfParam, csrfToken)
            }
          }
          else if (typeof visit.data === 'object' && visit.data !== null) {
            // Check if already added
            if (!visit.data[csrfParam]) {
              visit.data[csrfParam] = csrfToken
            }
          }
        }
        else {
          console.warn('CSRF token or param missing in router.on(start)')
        }
      }
    })

    // Global error handler for 400 Bad Request errors
    // This catches 400 errors that occur outside of form submissions
    router.on('error', (event) => {
      const error = event.detail

      // Handle 400 Bad Request errors with toast notifications
      if (error?.status === 400 || error?.response?.status === 400) {
        const errors = error?.errors || error?.response?.data?.errors

        if (errors && typeof errors === 'object') {
          // Handle validation errors
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
          // Generic 400 error message
          toast.error('Bad Request - Please check your input and try again')
        }
      }
    })

    // Also handle errors from Inertia responses
    // This catches errors that come from server responses
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
          <LoadingIndicator />
          <Suspense fallback={<div className="min-h-screen flex items-center justify-center"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>}>
            <App {...props} />
          </Suspense>
          <Toaster position="top-right" />
        </ThemeProvider>
      </ErrorBoundary>,
    )
  },
})
