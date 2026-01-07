import { services } from '~/services'

export const useAuth = () => {
  const auth = services.auth
  
  const user = ref(auth.getStoredUser())
  const isAuthenticated = ref(auth.isAuthenticated())
  const loading = ref(false)

  const login = async (credentials: any) => {
    loading.value = true
    try {
      const result = await auth.login(credentials)
      if (result.success) {
        user.value = auth.getStoredUser()
        isAuthenticated.value = true
      }
      return result
    } finally {
      loading.value = false
    }
  }

  const register = async (data: any) => {
    loading.value = true
    try {
      const result = await auth.register(data)
      if (result.success) {
        user.value = auth.getStoredUser()
        isAuthenticated.value = true
      }
      return result
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    const result = await auth.logout()
    if (result.success) {
      user.value = null
      isAuthenticated.value = false

      if(process.client) {
        await nextTick()
        navigateTo('/')
      }
    }
    return result
  }

  const resetPassword = async (data: any) => {
    const result = await auth.resetPassword(data)
    return result
  }

  const forgetPassword = async (data: any) => {
    const result = await auth.forgetPassword(data)
    return result
  }

  const fetchCurrentUser = async () => {
    const result = await auth.getCurrentUser()
    if (result.success && result.data) {
      user.value = result.data.user
    }
    return result
  }

  const clearAuth = () => {
    auth.clearAuth()
    user.value = null
    isAuthenticated.value = false
  }

  const hasPermission = (permission: string): boolean => {
    if (!user.value) return false
    
    // Super admin check
    if (user.value.roles?.some((role: any) => role.slug === 'super-admin')) {
      return true
    }
    
    // Check stored permissions
    if (process.client) {
      const permissionsStr = localStorage.getItem('user_permissions')
      if (permissionsStr) {
        const permissions = JSON.parse(permissionsStr)
        return permissions.includes(permission)
      }
    }
    
    return false
  }

  const hasRole = (role: string): boolean => {
    return user.value?.roles?.some((r: any) => r.slug === role) || false
  }

  const initialize = () => {
    if (process.client) {
      user.value = auth.getStoredUser()
      isAuthenticated.value = auth.isAuthenticated()
    }
  }

  return {
    user,
    isAuthenticated,
    loading,
    login,
    register,
    resetPassword,
    forgetPassword,
    logout,
    fetchCurrentUser,
    clearAuth,
    hasPermission,
    hasRole,
    initialize
  }
}