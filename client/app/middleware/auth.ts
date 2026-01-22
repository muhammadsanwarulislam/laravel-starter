import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuth()
  
  // Initialize auth if not already
  if (!auth.isAuthenticated.value) {
    auth.initialize()
  }
  
  // If user is not authenticated, redirect to login
  if (!auth.isAuthenticated.value) {
    notification.error('Please login to access this page')
    return navigateTo('/auth/login')
  }
  
  // Check permissions if route requires specific permission
  if (to.meta.requiresPermission) {
    const permission = to.meta.requiresPermission as string
    if (permission && !auth.hasPermission(permission)) {
      notification.error('You do not have permission to access this page')
      return navigateTo('/dashboard')
    }
  }
})