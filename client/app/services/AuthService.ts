import { AuthRepository } from '../repositories/AuthRepository'
import type { LoginCredentials, RegisterData } from '~/api/types/api.types'

export class AuthService {
  private repository: AuthRepository

  constructor() {
    this.repository = new AuthRepository()
  }

  async login(credentials: LoginCredentials): Promise<{ success: boolean; message?: string; data?: any }> {
    try {
      const response = await this.repository.login(credentials)
      
      if (response.success && response.data) {
        // Store token and user
        this.storeAuthData(response.data)
        
        // Set locale if provided
        if (credentials.locale) {
          this.setLocale(credentials.locale)
        }
        
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Login failed' }
    } catch (error) {
      return { success: false, message: 'An error occurred during login' }
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

  private setLocale(locale: string) {
    if (process.client) {
      localStorage.setItem('locale', locale)
      useCookie('locale').value = locale
    }
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
}