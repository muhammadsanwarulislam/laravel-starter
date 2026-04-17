import { BaseRepository } from './BaseRepository'
import type { Permission, CreatePermissionData, ApiResponse } from '~/api/types/api.types'

export class PermissionRepository extends BaseRepository {
  constructor() {
    super()
  }

  async getAll(params?: Record<string, any>): Promise<ApiResponse<any>> {
    return this.get<any>('/permissions', params)
  }

  async getById(id: number): Promise<ApiResponse<Permission>> {
    return this.get<Permission>(`/permissions/${id}`)
  }

  async create(data: CreatePermissionData): Promise<ApiResponse<Permission>> {
    return this.post<Permission>('/permissions', data)
  }

  async update(id: number, data: CreatePermissionData): Promise<ApiResponse<Permission>> {
    return this.put<Permission>(`/permissions/${id}`, data)
  }

  async deletePermission(id: number): Promise<ApiResponse<void>> {
    return this.delete<void>(`/permissions/${id}`)
  }

  async getModules(): Promise<ApiResponse<string[]>> {
    return this.get<string[]>('/permissions/modules')
  }

  async getByModule(module: string): Promise<ApiResponse<Permission[]>> {
    return this.get<Permission[]>(`/permissions/module/${module}`)
  }

  async sync(modules: Record<string, string[]>): Promise<ApiResponse<void>> {
    return this.post<void>('/permissions/sync', { modules })
  }
}
