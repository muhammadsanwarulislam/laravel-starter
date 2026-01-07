import { BaseRepository } from './BaseRepository'
import type { Permission, ApiResponse } from '~/api/types/api.types'

export class PermissionRepository extends BaseRepository {
  constructor() {
    super()
  }

  async getAll(): Promise<ApiResponse<Permission[]>> {
    return this.get<Permission[]>('/permissions')
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