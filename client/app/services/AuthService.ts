import { AuthRepository } from '../repositories/AuthRepository'
import type { RegisterData } from '~/api/types/api.types'

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
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'OTP verification failed' }
    } catch (error) {
      console.error('OTP verification error:', error)
      return { success: false, message: 'An error occurred during OTP verification' }
    }
  }

  async register(data: RegisterData): Promise<{ success: boolean; message?: string; data?: any }> {
    try {
      const response = await this.repository.register(data)

      if (response.success && response.data) {
        this.storeAuthData(response.data)
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Registration failed' }
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

  async forgetPassword(email: string): Promise<{ success: boolean; message?: string }> {
    try {
      await this.repository.forgotPassword(email)
      return { success: true }
    } catch (error) {
      console.error('Failed to forget password:', error)
      return { success: false, message: 'Failed to forget password' }
    }
  }

  async resetPassword(data: any): Promise<{ success: boolean; message?: string }> {
    try {
      await this.repository.resetPassword(data)
      return { success: true }
    } catch (error) {
      console.error('Failed to reset password:', error)
      return { success: false, message: 'Failed to reset password' }
    }
  }

  private storeAuthData(data: any) {
    if (process.client) {
      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('auth_user', JSON.stringify(data.user))

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

  getOTPData(): {token: string | null } {
    if (process.client) {
      return {
        token: localStorage.getItem('otp_token')
      }
    }
    return { identifier: null, token: null }
  }
}