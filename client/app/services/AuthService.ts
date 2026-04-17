import { AuthRepository } from '../repositories/AuthRepository'
import type { RegisterData, OtpSessionData, ChangePasswordData } from '~/api/types/api.types'

export class AuthService {
  private repository: AuthRepository

  constructor() {
    this.repository = new AuthRepository()
  }

  async login(credentials: any): Promise<{ success: boolean; message?: string; data?: any; otpRequired?: boolean }> {
    try {
      let identifier = ''
      let identifierType = 'email' 

      if (credentials.email) {
        identifier = credentials.email
        identifierType = 'email'
      } else if (credentials.phone) {
        identifier = credentials.phone
        identifierType = 'phone'
      } else {
        return { success: false, message: 'Please provide email or phone number' }
      }
      // Step 1: Request OTP
      const otpResponse = await this.repository.loginWithOTP(
        identifierType,
        identifier,
        credentials.password || undefined
      )

      if (otpResponse.success && otpResponse.data) {
        return {
          success: true,
          otpRequired: true,
          message: otpResponse.message,
          data: {
            identifier: otpResponse.data.identifier,
            identifier_type: otpResponse.data.identifier_type || identifierType,
            token: otpResponse.data.token,
            expires_at: otpResponse.data.expires_at
          }
        }
      }

      return { success: false, message: otpResponse.message || 'Failed to generate OTP' }
    } catch (error) {
      console.error('Login error:', error)
      return { success: false, message: 'An error occurred during login' }
    }
  }

  async verifyOTP(otp: string, type: string): Promise<{ success: boolean; message?: string; data?: any }> {
    try {
      const response = await this.repository.verifyOTP(otp, type)

      if (response.success && response.data) {
        // Store token and user
        this.storeAuthData(response.data)
        return { success: true, data: response.data, message: response.message }
      }

      return { success: false, message: response.message || 'OTP verification failed' }
    } catch (error) {
      console.error('OTP verification error:', error)
      return { success: false, message: 'An error occurred during OTP verification' }
    }
  }

  async register(data: RegisterData): Promise<{ success: boolean; message?: string; data?: any; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.register(data)

      if (response.success && response.data) {
        this.storeAuthData(response.data)
        return { success: true, data: response.data, message: response.message }
      }

      return { success: false, message: response.message || 'Registration failed', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred during registration' }
    }
  }

  async logout(): Promise<{ success: boolean; message?: string }> {
    try {
      await this.repository.logout()
      return { success: true }
    } catch (error) {
      console.error('Logout error:', error)
      return { success: false, message: 'Logout failed' }
    } finally {
      this.clearAuth()
    }
  }

  async getCurrentUser(): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const response = await this.repository.getCurrentUser()

      if (response.success && response.data) {
        // Update stored user data
        this.updateUserData(response.data.user)
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to fetch user' }
    } catch (error) {
      console.error('Failed to fetch user:', error)
      return { success: false, message: 'Failed to fetch user' }
    }
  }

  async forgetPassword(email: string): Promise<{ success: boolean; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.forgotPassword(email)
      if (response.success) {
        return { success: true, message: response.message }
      }
      return { success: false, message: response.message || 'Failed to forget password', errors: response.errors }
    } catch (error) {
      console.error('Failed to forget password:', error)
      return { success: false, message: 'Failed to forget password' }
    }
  }

  async resetPassword(data: any): Promise<{ success: boolean; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.resetPassword(data)
      if (response.success) {
        return { success: true, message: response.message }
      }
      return { success: false, message: response.message || 'Failed to reset password', errors: response.errors }
    } catch (error) {
      console.error('Failed to reset password:', error)
      return { success: false, message: 'Failed to reset password' }
    }
  }

  async resendOTP(data: { type: string; delivery_method: 'email' | 'phone'; email?: string; phone?: string }): Promise<{ success: boolean; message?: string; data?: any }> {
    try {
      const response = await this.repository.resendOTP(data)

      if (response.success) {
        return { success: true, message: response.message, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to resend OTP' }
    } catch (error) {
      console.error('Failed to resend OTP:', error)
      return { success: false, message: 'Failed to resend OTP' }
    }
  }

  async changePassword(data: ChangePasswordData): Promise<{ success: boolean; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.changePassword(data)

      if (response.success) {
        return { success: true, message: response.message }
      }

      return { success: false, message: response.message || 'Failed to change password', errors: response.errors }
    } catch (error) {
      console.error('Failed to change password:', error)
      return { success: false, message: 'Failed to change password' }
    }
  }

  private storeAuthData(data: any) {
    if (process.client) {
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('auth_user', JSON.stringify(data.user))
      localStorage.removeItem('otp_token')
      localStorage.removeItem('otp_identifier')
      localStorage.removeItem('otp_identifier_type')

      // Store user permissions if available
      if (data.user?.roles) {
        const permissions = this.extractPermissions(data.user.roles)
        localStorage.setItem('user_permissions', JSON.stringify(permissions))
      }
    }
  }

  private updateUserData(user: any) {
    if (process.client) {
      localStorage.setItem('auth_user', JSON.stringify(user))
    }
  }

  clearAuth() {
    if (process.client) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      localStorage.removeItem('user_permissions')
      localStorage.removeItem('otp_token')
      localStorage.removeItem('otp_identifier')
      localStorage.removeItem('otp_identifier_type')
    }
  }

  private extractPermissions(roles: any[]): string[] {
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

  getStoredUser(): any {
    if (process.client) {
      const userStr = localStorage.getItem('auth_user')
      return userStr ? JSON.parse(userStr) : null
    }
    return null
  }

  getStoredToken(): string | null {
    if (process.client) {
      return localStorage.getItem('auth_token')
    }
    return null
  }

  isAuthenticated(): boolean {
    return !!this.getStoredToken()
  }

  storeOTPData(token: string) {
    if (process.client) {
      localStorage.setItem('otp_token', token)
    }
  }

  getOTPData(): OtpSessionData {
    if (process.client) {
      return {
        token: localStorage.getItem('otp_token'),
        identifier: localStorage.getItem('otp_identifier'),
        identifier_type: (localStorage.getItem('otp_identifier_type') as 'email' | 'phone' | null) ?? null
      }
    }
    return { identifier: null, identifier_type: null, token: null }
  }
}
