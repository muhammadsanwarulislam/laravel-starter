import { BaseRepository } from './BaseRepository'
import type { User, PaginatedResponse, CreateUserData, UpdateUserData } from '~/api/types/api.types'
import type { ApiResponse } from '~/api/types/api.types'

export class UserRepository extends BaseRepository {
  constructor() {
    super()
  }

  async getAll(params?: any): Promise<ApiResponse<PaginatedResponse<User>>> {
    return this.get<PaginatedResponse<User>>('/users', params)
  }

  async getById(id: number): Promise<ApiResponse<User>> {
    return this.get<User>(`/users/${id}`)
  }

  async create(data: CreateUserData): Promise<ApiResponse<User>> {
    return this.post<User>('/users', data)
  }

  async update(id: number, data: UpdateUserData): Promise<ApiResponse<User>> {
    return this.put<User>(`/users/${id}`, data)
  }

  async deleteById(id: number): Promise<ApiResponse<void>> {
    return this.delete<void>(`/users/${id}`)
  }

  async updateStatus(id: number, status: boolean): Promise<ApiResponse<User>> {
    return this.put<User>(`/users/${id}/status`, { status })
  }

  async assignRoles(id: number, roleIds: number[]): Promise<ApiResponse<User>> {
    return this.post<User>(`/users/${id}/roles`, { roles: roleIds })
  }

  async getProfile(): Promise<ApiResponse<User>> {
    return this.get<User>('/profile')
  }

  async updateProfile(data: UpdateUserData): Promise<ApiResponse<User>> {
    return this.put<User>('/profile', data)
  }
}