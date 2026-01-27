export const useAuthSession = () => {
    const authToken = useCookie('auth_token')
    const otpToken = useCookie('otp_token')
    const locale = useCookie('locale')

    const getAuthToken = () => {
        if (process.client) {
            return localStorage.getItem('auth_token')
        }
        return authToken.value
    }

    const setAuthToken = (token: string) => {
        if (process.client) {
            localStorage.setItem('auth_token', token)
        }
        authToken.value = token
    }

    const clearAuthToken = () => {
        if (process.client) {
            localStorage.removeItem('auth_token')
        }
        authToken.value = null
    }

    const getOTPToken = () => {
        if (process.client) {
            return localStorage.getItem('otp_token')
        }
        return otpToken.value
    }

    const setOTPToken = (token: string) => {
        if (process.client) {
            localStorage.setItem('otp_token', token)
        }
        otpToken.value = token
    }

    const clearOTPToken = () => {
        if (process.client) {
            localStorage.removeItem('otp_token')
        }
        otpToken.value = null
    }

    const getLocale = () => locale.value
    const setLocale = (value: string) => {
        locale.value = value
    }

    return {
        getAuthToken,
        setAuthToken,
        clearAuthToken,
        getOTPToken,
        setOTPToken,
        clearOTPToken,
        getLocale,
        setLocale
    }
}