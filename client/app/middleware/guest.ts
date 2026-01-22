import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuth()
  
  // Initialize auth if not already
  if (!auth.isAuthenticated.value) {
    auth.initialize()
  }
  
  // If user is authenticated, redirect to dashboard
  if (auth.isAuthenticated.value) {
    notification.info('You are already logged in')
    return navigateTo('/dashboard')
  }
})