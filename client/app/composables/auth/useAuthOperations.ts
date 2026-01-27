import { services } from "~/services"
import { useAuthSession } from "./useAuthSession"
import { useUserStore } from "./useUserStore"

export const useAuthOperations = () => {
    const userStore = useUserStore()
    const authSession = useAuthSession()

    const login = async (credentials: any) => {
        try {
            const auth = services.auth
            let identifier = ''
            let identifierType: 'email' | 'phone' = 'email'

            if (credentials.email) {
                identifier = credentials.email
                identifierType = 'email'
            } else if (credentials.phone) {
                identifier = credentials.phone
                identifierType = 'phone'
            } else {
                return { success: false, message: 'Please provide email or phone number' }
            }

            const result = await auth.login(credentials)
            return result
        } catch (error) {
            console.error('Login error:', error)
            return { success: false, message: 'An error occurred during login' }
        }
    }

    const verifyOTP = async (otp: string) => {
        try {
            const otpToken = authSession.getOTPToken()
            if (!otpToken) {
                return { success: false, message: 'OTP session expired. Please login again.' }
            }

            const auth = services.auth
            const result = await auth.verifyOTP(otp, 'login')

            if (result.success && result.data) {
                // Store user data
                userStore.setUser(result.data.user)
                authSession.setAuthToken(result.data.token)

                // Store permissions if available
                if (result.data.user?.roles) {
                    const permissions = userStore.extractPermissions(result.data.user.roles)
                    userStore.setPermissions(permissions)
                }
            }

            return result
        } catch (error) {
            console.error('OTP verification error:', error)
            return { success: false, message: 'An error occurred during OTP verification' }
        }
    }

    const logout = async () => {
        try {
            await services.auth.logout()
            clearAuthData()
            return { success: true }
        } catch (error) {
            console.error('Logout error:', error)
            return { success: false, message: 'Logout failed' }
        }
    }

    const clearAuthData = () => {
        userStore.clearUser()
        userStore.clearPermissions()
        authSession.clearAuthToken()
        authSession.clearOTPToken()
    }

    return {
        login,
        verifyOTP,
        logout,
        clearAuthData
    }
}