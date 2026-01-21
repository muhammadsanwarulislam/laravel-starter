import { BaseRepository } from './BaseRepository'
import type { LoginCredentials, RegisterData, LoginResponse, RegisterResponse, AuthUserResponse } from '~/api/types/api.types'
import type { ApiResponse } from '~/api/types/api.types'

export class AuthRepository extends BaseRepository {
  constructor() {
    super()
  }

  async loginWithOTP(identifierType: 'email' | 'phone', identifier: string, password?: string): Promise<ApiResponse<LoginResponse>> {
    const payload: any = {}
    
    if (identifierType === 'email') {
      payload.email = identifier
    } else {
      payload.phone = identifier
    }
    
    if (password) {
      payload.password = password
    }
    
    return this.post<LoginResponse>('/auth/login/otp', payload)
  }

  async verifyOTP(otp: string, type: string, token: string): Promise<ApiResponse<LoginResponse>> {
    return this.post<LoginResponse>('/otp/verify', { 
      otp, 
      type
    })
  }

  async register(data: RegisterData): Promise<ApiResponse<RegisterResponse>> {
    return this.post<RegisterResponse>('/register', data)
  }

  async logout(): Promise<ApiResponse<void>> {
    return this.post<void>('/logout')
  }

  async getCurrentUser(): Promise<ApiResponse<AuthUserResponse>> {
    return this.get<AuthUserResponse>('/me')
  }

  async forgotPassword(email: string): Promise<ApiResponse<void>> {
    return this.post<void>('/password/forgot', { email })
  }

  async resetPassword(data: any): Promise<ApiResponse<void>> {
    return this.post<void>('/password/reset', data)
  }

  async changePassword(data: any): Promise<ApiResponse<void>> {
    return this.put<void>('/change-password', data)
  }
}