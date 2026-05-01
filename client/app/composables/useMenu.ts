import type { MenuItem, MenuSection } from '~/api/types/api.types'
import { useAuth } from './auth/useAuth'

export const useMenu = () => {
  const auth = useAuth()
  const route = useRoute()

  const hasAnyPermission = (permissions: string[]): boolean => {
    return permissions.some(permission => auth.hasPermission(permission))
  }

  const hasAnyRole = (roles: string[]): boolean => {
    return roles.some(role => auth.hasRole(role))
  }

  const getMenuItems = (): MenuSection[] => {
    const items: MenuSection[] = [
      {
        title: '',
        items: [
          {
            id: 'dashboard',
            title: 'common.dashboard',
            icon: 'Dashboard',
            to: '/dashboard',
            permissions: [], 
            roles: [], 
            isActive: route.path === '/dashboard',
            badge: null
          }
        ]
      },
      {
        title: 'Management',
        items: [
          {
            id: 'users',
            title: 'common.users',
            icon: 'Users',
            to: '/users',
            permissions: [
              'view-users', 'create-users', 'edit-users', 
              'delete-users', 'export-users'
            ],
            roles: [], 
            isActive: route.path.startsWith('/users'),
            // badge: 42 
          },
          {
            id: 'roles',
            title: 'common.roles',
            icon: 'RolePermissions',
            to: '/roles',
            permissions: [
              'view-roles', 'create-roles', 'edit-roles',
              'delete-roles', 'assign-roles'
            ],
            roles: [],
            isActive: route.path.startsWith('/roles')
          },
          {
            id: 'permissions',
            title: 'common.permissions',
            icon: 'RolePermissions',
            to: '/permissions',
            permissions: [
              'view-permissions', 'manage-permissions'
            ],
            roles: [],
            isActive: route.path.startsWith('/permissions')
          },
          {
            id: 'localization',
            title: 'common.localizations',
            icon: 'Localization',
            to: '/localization',
            permissions: [
              'view-languages', 'create-languages', 'edit-languages',
              'delete-languages', 'view-translations', 'create-translations',
              'edit-translations', 'delete-translations', 'import-translations'
            ],
            roles: [],
            isActive: route.path.startsWith('/localization')
          }
        ]
      },
      {
        title: 'Account',
        items: [
          {
            id: 'profile',
            title: 'common.profile',
            icon: 'Profile',
            to: '/auth/profile',
            permissions: ['view-profile', 'edit-profile'],
            roles: [],
            isActive: route.path.startsWith('/auth/profile')
          }
        ]
      }
    ]

    return items.map(section => ({
      ...section,
      items: section.items.filter(item => {
        if (item.roles.length > 0 && hasAnyRole(item.roles)) {
          return true
        }
        if (item.permissions.length > 0 && hasAnyPermission(item.permissions)) {
          return true
        }
        return item.permissions.length === 0 && item.roles.length === 0
      })
    })).filter(section => section.items.length > 0)
  }

  const menuItems = computed(() => getMenuItems())

  const getAllRequiredPermissions = (): string[] => {
    const allItems = menuItems.value.flatMap(section => section.items)
    return [...new Set(allItems.flatMap(item => item.permissions))]
  }

  const canAccessRoute = (routePath: string): boolean => {
    const allItems = menuItems.value.flatMap(section => section.items)
    const item = allItems.find(item => item.to === routePath || routePath.startsWith(item.to))
    
    if (!item) return false
    
    if (item.roles.length > 0 && hasAnyRole(item.roles)) {
      return true
    }
    
    if (item.permissions.length > 0 && hasAnyPermission(item.permissions)) {
      return true
    }
    
    return item.permissions.length === 0 && item.roles.length === 0
  }


  const getMenuItem = (id: string): MenuItem | undefined => {
    return menuItems.value
      .flatMap(section => section.items)
      .find(item => item.id === id)
  }

  return {
    menuItems,
    canAccessRoute,
    getMenuItem,
    getAllRequiredPermissions,
    hasAnyPermission,
    hasAnyRole
  }
}