import type { OtpSessionData } from '~/api/types/api.types'

export const useAuthSession = () => {
    const authToken = useCookie('auth_token')
    const otpToken = useCookie('otp_token')
    const otpIdentifier = useCookie('otp_identifier')
    const otpIdentifierType = useCookie('otp_identifier_type')
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

    const getOTPSession = (): OtpSessionData => {
        if (process.client) {
            return {
                token: localStorage.getItem('otp_token'),
                identifier: localStorage.getItem('otp_identifier'),
                identifier_type: (localStorage.getItem('otp_identifier_type') as 'email' | 'phone' | null) ?? null,
            }
        }

        return {
            token: otpToken.value ?? null,
            identifier: otpIdentifier.value ?? null,
            identifier_type: (otpIdentifierType.value as 'email' | 'phone' | null) ?? null,
        }
    }

    const setOTPSession = (session: OtpSessionData) => {
        if (process.client) {
            if (session.token) {
                localStorage.setItem('otp_token', session.token)
            } else {
                localStorage.removeItem('otp_token')
            }

            if (session.identifier) {
                localStorage.setItem('otp_identifier', session.identifier)
            } else {
                localStorage.removeItem('otp_identifier')
            }

            if (session.identifier_type) {
                localStorage.setItem('otp_identifier_type', session.identifier_type)
            } else {
                localStorage.removeItem('otp_identifier_type')
            }
        }

        otpToken.value = session.token
        otpIdentifier.value = session.identifier
        otpIdentifierType.value = session.identifier_type
    }

    const clearOTPSession = () => {
        if (process.client) {
            localStorage.removeItem('otp_token')
            localStorage.removeItem('otp_identifier')
            localStorage.removeItem('otp_identifier_type')
        }

        otpToken.value = null
        otpIdentifier.value = null
        otpIdentifierType.value = null
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
        getOTPSession,
        setOTPSession,
        clearOTPSession,
        getLocale,
        setLocale
    }
}
