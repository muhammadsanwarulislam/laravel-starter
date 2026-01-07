import { BaseRepository } from './BaseRepository'
import type { Role, CreateRoleData, ApiResponse } from '~/api/types/api.types'

export class RoleRepository extends BaseRepository {
  constructor() {
    super()
  }

  async getAll(): Promise<ApiResponse<Role[]>> {
    return this.get<Role[]>('/roles')
  }

  async getById(id: number): Promise<ApiResponse<Role>> {
    return this.get<Role>(`/roles/${id}`)
  }

  async create(data: CreateRoleData): Promise<ApiResponse<Role>> {
    return this.post<Role>('/roles', data)
  }

  async update(id: number, data: CreateRoleData): Promise<ApiResponse<Role>> {
    return this.put<Role>(`/roles/${id}`, data)
  }

  async delete(id: number): Promise<ApiResponse<void>> {
    return this.delete<void>(`/roles/${id}`)
  }

  async assignPermissions(id: number, permissionIds: number[]): Promise<ApiResponse<Role>> {
    return this.post<Role>(`/roles/${id}/permissions`, { permissions: permissionIds })
  }
}