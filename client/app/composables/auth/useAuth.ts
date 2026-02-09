import { services } from '~/services'
import { useUserStore } from './useUserStore'
import { useAuthSession } from './useAuthSession'
import { useAuthValidation } from './useAuthValidation'
import { useAuthOperations } from './useAuthOperations'

export const useAuth = () => {
  // Import specialized composables
  const userStore       = useUserStore()
  const authSession     = useAuthSession()
  const authOps         = useAuthOperations()
  const authValidation  = useAuthValidation()

  // Reactive state
  const user            = ref(userStore.getUser())
  const isAuthenticated = computed(() => !!authSession.getAuthToken())
  const loading         = ref(false)
  const otpRequired     = ref(false)
  const otpData         = ref({
    token: authSession.getOTPToken()
  })

  // Main methods
  const login = async (credentials: any) => {
    loading.value = true
    const result = await authOps.login(credentials)
    
    if (result.success && result.otpRequired) {
      otpRequired.value = true
      otpData.value = {
        token: result.data?.token || null
      }
      authSession.setOTPToken(result.data?.token || '')
    }
    
    loading.value = false
    return result
  }

  const verifyOTP = async (otp: string) => {
    loading.value = true
    const result = await authOps.verifyOTP(otp)
    
    if (result.success) {
      user.value = userStore.getUser()
      otpRequired.value = false
      otpData.value = { token: null }
      authSession.clearOTPToken()
    }
    
    loading.value = false
    return result
  }

  const logout = async () => {
    const result = await authOps.logout()
    if (result.success) {
      user.value = null
    }
    return result
  }

  const register = async (data: any) => {
    loading.value = true
    try {
      const result = await services.auth.register(data)
      
      if (result.success && result.data) {
        userStore.setUser(result.data.user)
        authSession.setAuthToken(result.data.token)
        user.value = result.data.user
      }
      
      return result
    } finally {
      loading.value = false
    }
  }

  const fetchCurrentUser = async () => {
    loading.value = true
    try {
      const result = await services.auth.getCurrentUser()
      
      if (result.success && result.data) {
        userStore.setUser(result.data.user)
        user.value = result.data.user
      }
      
      return result
    } finally {
      loading.value = false
    }
  }

  const fetchCountryCodes = async () => {
    const result = await services.countryCode.getAllCountryCodes()
    return result
  }

  const initialize = () => {
    user.value = userStore.getUser()
    otpData.value.token = authSession.getOTPToken()
    fetchCountryCodes()
  }

  // Delegate to specialized composables
  const hasPermission         = userStore.hasPermission
  const hasRole               = userStore.hasRole
  const clearAuth             = authOps.clearAuthData
  const validatePhone         = authValidation.validatePhoneNumber
  const cleanPhone            = authValidation.cleanPhoneNumber
  const detectIdentifierType  = authValidation.detectIdentifierType

  return {
    // State
    user,
    isAuthenticated,
    loading,
    otpRequired,
    otpData,
    
    // Main operations
    login,
    verifyOTP,
    logout,
    register,
    fetchCurrentUser,
    fetchCountryCodes,
    
    // Validation helpers
    validatePhone,
    cleanPhone,
    detectIdentifierType,
    
    // Permission helpers
    hasPermission,
    hasRole,
    
    // Utility
    clearAuth,
    initialize
  }
}