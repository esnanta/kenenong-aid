import { Link, usePage } from '@inertiajs/react'
import {
  AlertTriangle,
  Bell,
  CheckCircle,
  ChevronDown,
  ChevronRight,
  Database,
  Home,
  Key,
  LayoutDashboard,
  LogOut,
  Package,
  Search,
  Settings,
  Shield,
  Truck,
  User,
  UserCog,
  Users,
} from 'lucide-react'
import { useState } from 'react'
import { Logo } from '@/components/Logo'
import { ThemeToggle } from '@/components/ThemeToggle'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from '@/components/ui/collapsible'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarHeader,
  SidebarInset,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
  SidebarProvider,
  SidebarTrigger,
} from '@/components/ui/sidebar'
import { getCsrfParam, getCsrfToken } from '@/lib/csrf'

export function DashboardLayout({ children, user }) {
  const { url } = usePage()
  const [showLogoutDialog, setShowLogoutDialog] = useState(false)

  // State untuk mengontrol menu yang terbuka secara otomatis berdasarkan URL
  const [isUserMenuOpen, setIsUserMenuOpen] = useState(
    url.startsWith('/users') || url.startsWith('/roles') || url.startsWith('/permissions'),
  )
  const [isMasterMenuOpen, setIsMasterMenuOpen] = useState(
    url.startsWith('/disaster-statuses') || url.startsWith('/disaster-types') || url.startsWith('/item-categories') || url.startsWith('/units'),
  )
  const [isLogisticsOpen, setIsLogisticsOpen] = useState(
    url.startsWith('/aid-plans') || url.startsWith('/aid-distributions') || url.startsWith('/items'),
  )

  const handleLogout = () => {
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = '/logout'
    const csrfToken = getCsrfToken()
    const csrfParam = getCsrfParam()
    if (csrfToken && csrfParam) {
      const csrfInput = document.createElement('input')
      csrfInput.type = 'hidden'
      csrfInput.name = csrfParam
      csrfInput.value = csrfToken
      form.appendChild(csrfInput)
    }
    document.body.appendChild(form)
    form.submit()
  }

  const getUserInitials = () => {
    if (!user?.name)
      return 'U'
    const names = user.name.split(' ')
    return names.length >= 2
      ? (names[0][0] + names[names.length - 1][0]).toUpperCase()
      : user.name.charAt(0).toUpperCase()
  }

  return (
    <SidebarProvider>
      <Sidebar collapsible="icon">
        <SidebarHeader>
          <Link
            href="/"
            className="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity w-full group-data-[collapsible=icon]:justify-center"
          >
            <Logo className="h-6 w-6 flex-shrink-0" />
            <span className="text-xl font-bold group-data-[collapsible=icon]:hidden">Kenenong Aid</span>
          </Link>
        </SidebarHeader>

        <SidebarContent>
          <SidebarGroup>
            <SidebarGroupContent>
              <SidebarMenu>
                {/* 1. Dashboard Utama */}
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={url === '/dashboard'} tooltip="Dashboard">
                    <Link href="/dashboard">
                      <LayoutDashboard />
                      <span>Dashboard</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>

                {/* 2. Operasional Bencana */}
                <SidebarMenuItem>
                  <SidebarMenuButton
                    asChild
                    isActive={url.startsWith('/disasters')}
                    tooltip="Daftar Kejadian Bencana"
                  >
                    <Link href="/disasters">
                      <AlertTriangle />
                      <span>Data Bencana</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>

                {/* 3. Posko & Shelter */}
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={url.startsWith('/shelters')} tooltip="Titik Pengungsian">
                    <Link href="/shelters">
                      <Home />
                      <span>Tempat Evakuasi</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>

                {/* 4. Logistik & Bantuan */}
                <Collapsible open={isLogisticsOpen} onOpenChange={setIsLogisticsOpen} className="group/collapsible">
                  <SidebarMenuItem>
                    <CollapsibleTrigger asChild>
                      <SidebarMenuButton tooltip="Manajemen Logistik">
                        <Package />
                        <span>Logistik & Bantuan</span>
                        <ChevronRight
                          className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                        />
                      </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                      <SidebarMenuSub>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/aid-plans')}>
                            <Link href="/aid-plans">Rencana Distribusi</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/aid-distributions')}>
                            <Link href="/aid-distributions">Realisasi Bantuan</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                      </SidebarMenuSub>
                    </CollapsibleContent>
                  </SidebarMenuItem>
                </Collapsible>

                {/* 5. Aksesibilitas */}
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={url.startsWith('/access-routes')} tooltip="Rute Transportasi">
                    <Link href="/access-routes">
                      <Truck />
                      <span>Rute Akses</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>

                {/* 6. Verifikasi Lapangan */}
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={url.startsWith('/verifications')} tooltip="Validasi Data">
                    <Link href="/verifications">
                      <CheckCircle />
                      <span>Verifikasi</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>

                {/* 7. Master Data (Kategori/Referensi) */}
                <Collapsible open={isMasterMenuOpen} onOpenChange={setIsMasterMenuOpen} className="group/collapsible">
                  <SidebarMenuItem>
                    <CollapsibleTrigger asChild>
                      <SidebarMenuButton tooltip="Data Referensi">
                        <Database />
                        <span>Master Data</span>
                        <ChevronRight
                          className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                        />
                      </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                      <SidebarMenuSub>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/disaster-types')}>
                            <Link href="/disaster-types">Tipe Bencana</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/disaster-statuses')}>
                            <Link href="/disaster-statuses">Status Bencana</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/item-categories')}>
                            <Link href="/item-categories">Kategori Barang</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/items')}>
                            <Link href="/items">Barang</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/units')}>
                            <Link href="/units">Satuan Unit</Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                      </SidebarMenuSub>
                    </CollapsibleContent>
                  </SidebarMenuItem>
                </Collapsible>

                {/* Garis Pemisah untuk User Management */}
                <div className="my-2 border-t border-sidebar-border" />

                {/* 8. User Management (Paling Bawah) */}
                <Collapsible open={isUserMenuOpen} onOpenChange={setIsUserMenuOpen} className="group/collapsible">
                  <SidebarMenuItem>
                    <CollapsibleTrigger asChild>
                      <SidebarMenuButton tooltip="Administrasi User">
                        <UserCog />
                        <span>User Management</span>
                        <ChevronRight
                          className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                        />
                      </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                      <SidebarMenuSub>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/users')}>
                            <Link href="/users">
                              <Users className="w-4 h-4 mr-2" />
                              <span>Daftar Pengguna</span>
                            </Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/roles')}>
                            <Link href="/roles">
                              <Shield className="w-4 h-4 mr-2" />
                              <span>Peran (Roles)</span>
                            </Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                        <SidebarMenuSubItem>
                          <SidebarMenuSubButton asChild isActive={url.startsWith('/permissions')}>
                            <Link href="/permissions">
                              <Key className="w-4 h-4 mr-2" />
                              <span>Izin Akses</span>
                            </Link>
                          </SidebarMenuSubButton>
                        </SidebarMenuSubItem>
                      </SidebarMenuSub>
                    </CollapsibleContent>
                  </SidebarMenuItem>
                </Collapsible>
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        </SidebarContent>
      </Sidebar>

      <SidebarInset>
        <header
          className="sticky top-0 z-40 border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60"
        >
          <div className="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
            <SidebarTrigger />
            <div className="flex-1 max-w-md ml-auto">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input type="search" placeholder="Cari data bencana..." className="pl-9 h-9" />
              </div>
            </div>
            <div className="flex items-center gap-2">
              <ThemeToggle />
              <Button size="icon" variant="ghost" className="relative">
                <Bell className="h-4 w-4" />
                <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-destructive" />
              </Button>
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="ghost" className="relative h-8 w-8 rounded-full cursor-pointer">
                    <Avatar className="h-8 w-8">
                      <AvatarFallback className="text-xs">{getUserInitials()}</AvatarFallback>
                    </Avatar>
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent className="w-56" align="end" forceMount>
                  <DropdownMenuLabel className="font-normal">
                    <div className="flex flex-col space-y-1">
                      <p className="text-sm font-medium leading-none">{user?.name || 'User'}</p>
                      <p className="text-xs leading-none text-muted-foreground">
                        {user?.email || ''}
                      </p>
                    </div>
                  </DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem asChild>
                    <Link href="/dashboard/profile" className="cursor-pointer">
                      <User className="mr-2 h-4 w-4" />
                      Profil
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem asChild>
                    <Link href="/dashboard/settings" className="cursor-pointer">
                      <Settings className="mr-2 h-4 w-4" />
                      Pengaturan
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem
                    onClick={() => setShowLogoutDialog(true)}
                    className="cursor-pointer text-destructive"
                  >
                    <LogOut className="mr-2 h-4 w-4" />
                    Keluar
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </div>
        </header>

        <main className="p-4 sm:p-6 lg:p-8">{children}</main>
      </SidebarInset>

      <AlertDialog open={showLogoutDialog} onOpenChange={setShowLogoutDialog}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Konfirmasi Logout</AlertDialogTitle>
            <AlertDialogDescription>Apakah Anda yakin ingin keluar dari aplikasi?</AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Batal</AlertDialogCancel>
            <AlertDialogAction onClick={handleLogout} className="bg-destructive text-destructive-foreground">
              Ya,
              Logout
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </SidebarProvider>
  )
}
