import { BaseRepository } from './BaseRepository'
import type { LoginCredentials, RegisterData, LoginResponse, RegisterResponse, AuthUserResponse } from '~/api/types/api.types'
import type { ApiResponse } from '~/api/types/api.types'

export class AuthRepository extends BaseRepository {
  constructor() {
    super()
  }

  async login(credentials: LoginCredentials): Promise<ApiResponse<LoginResponse>> {
    return this.post<LoginResponse>('/login', credentials)
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