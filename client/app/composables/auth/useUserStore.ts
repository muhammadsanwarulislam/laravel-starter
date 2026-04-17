export const useUserStore = () => {
    const user = useCookie('auth_user')
    const permissions = useCookie('user_permissions')

    const getUser = () => {
        if (process.client) {
            const stored = localStorage.getItem('auth_user')
            return stored ? JSON.parse(stored) : null
        }
        return user.value ? JSON.parse(user.value) : null
    }

    const setUser = (userData: any) => {
        const userJson = JSON.stringify(userData)
        if (process.client) {
            localStorage.setItem('auth_user', userJson)
        }
        user.value = userJson
    }

    const clearUser = () => {
        if (process.client) {
            localStorage.removeItem('auth_user')
        }
        user.value = null
    }

    const getPermissions = () => {
        if (process.client) {
            const stored = localStorage.getItem('user_permissions')
            return stored ? JSON.parse(stored) : []
        }
        return permissions.value ? JSON.parse(permissions.value) : []
    }

    const setPermissions = (permissionsList: string[]) => {
        const permissionsJson = JSON.stringify(permissionsList)
        if (process.client) {
            localStorage.setItem('user_permissions', permissionsJson)
        }
        permissions.value = permissionsJson
    }

    const clearPermissions = () => {
        if (process.client) {
            localStorage.removeItem('user_permissions')
        }
        permissions.value = null
    }

    const extractPermissions = (roles: any[]): string[] => {
        const permissions: string[] = []
        roles.forEach(role => {
            if (role.permissions) {
                role.permissions.forEach((perm: any) => {
                    permissions.push(perm.slug)
                })
            }
        })
        return [...new Set(permissions)]
    }

    const hasPermission = (permission: string): boolean => {
        const userData = getUser()
        if (!userData) return false

        // Super admin check
        if (userData.roles?.some((role: any) => role.slug === 'super_admin')) {
            return true
        }

        const userPermissions = getPermissions()
        return userPermissions.includes(permission)
    }

    const hasRole = (role: string): boolean => {
        const userData = getUser()
        return userData?.roles?.some((r: any) => r.slug === role) || false
    }

    return {
        getUser,
        setUser,
        clearUser,
        getPermissions,
        setPermissions,
        clearPermissions,
        extractPermissions,
        hasPermission,
        hasRole
    }
}
