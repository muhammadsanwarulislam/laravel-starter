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
    token: authSession.getOTPSession().token,
    identifier: authSession.getOTPSession().identifier,
    identifier_type: authSession.getOTPSession().identifier_type,
  })

  // Main methods
  const login = async (credentials: any) => {
    loading.value = true
    const result = await authOps.login(credentials)
    
    if (result.success && result.otpRequired) {
      otpRequired.value = true
      otpData.value = {
        token: result.data?.token || null,
        identifier: result.data?.identifier || null,
        identifier_type: result.data?.identifier_type || null,
      }
      authSession.setOTPSession({
        token: result.data?.token || null,
        identifier: result.data?.identifier || null,
        identifier_type: result.data?.identifier_type || null,
      })
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
      otpData.value = { token: null, identifier: null, identifier_type: null }
      authSession.clearOTPSession()
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
        if (result.data.user?.roles) {
          userStore.setPermissions(userStore.extractPermissions(result.data.user.roles))
        }
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

  const forgetPassword = async (email: string) => {
    return services.auth.forgetPassword(email)
  }

  const resetPassword = async (payload: any) => {
    return services.auth.resetPassword(payload)
  }

  const changePassword = async (payload: any) => {
    return services.auth.changePassword(payload)
  }

  const resendOTP = async () => {
    const session = authSession.getOTPSession()

    if (!session.identifier || !session.identifier_type) {
      return { success: false, message: 'OTP session expired. Please login again.' }
    }

    return services.auth.resendOTP({
      type: 'login',
      delivery_method: session.identifier_type,
      email: session.identifier_type === 'email' ? session.identifier : undefined,
      phone: session.identifier_type === 'phone' ? session.identifier : undefined,
    })
  }

  const initialize = () => {
    user.value = userStore.getUser()
    otpData.value = authSession.getOTPSession()
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
    forgetPassword,
    resetPassword,
    changePassword,
    resendOTP,
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
