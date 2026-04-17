import { BaseRepository } from './BaseRepository'
import type { RegisterData, LoginResponse, RegisterResponse, AuthUserResponse, AuthenticatedSessionResponse } from '~/api/types/api.types'
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

  async verifyOTP(otp: string, type: string): Promise<ApiResponse<AuthenticatedSessionResponse>> {
    const otpToken = process.client
      ? localStorage.getItem('otp_token')
      : useCookie('otp_token').value

    return this.post<AuthenticatedSessionResponse>('/otp/verify', { 
      otp, 
      type
    }, otpToken ? {
      headers: {
        Authorization: `Bearer ${otpToken}`
      }
    } : undefined)
  }

  async resendOTP(payload: { type: string; delivery_method: 'email' | 'phone'; email?: string; phone?: string }): Promise<ApiResponse<{ expires_at: string }>> {
    return this.post<{ expires_at: string }>('/otp/resend', payload)
  }

  async register(data: RegisterData): Promise<ApiResponse<RegisterResponse>> {
    return this.post<RegisterResponse>('/auth/register', data)
  }

  async logout(): Promise<ApiResponse<void>> {
    return this.post<void>('/logout')
  }

  async getCurrentUser(): Promise<ApiResponse<AuthUserResponse>> {
    return this.get<AuthUserResponse>('/me')
  }

  async forgotPassword(email: string): Promise<ApiResponse<void>> {
    return this.post<void>('/auth/password/forgot', { email })
  }

  async resetPassword(data: any): Promise<ApiResponse<void>> {
    return this.post<void>('/auth/password/reset', data)
  }

  async changePassword(data: any): Promise<ApiResponse<void>> {
    return this.put<void>('/change-password', data)
  }
}
