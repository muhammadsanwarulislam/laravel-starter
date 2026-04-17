import { PermissionRepository } from '../repositories/PermissionRepository'
import type { Permission, CreatePermissionData, PaginatedResponse } from '~/api/types/api.types'

type PermissionListResponse = {
  success: boolean
  data?: Permission[]
  pagination?: {
    currentPage: number
    lastPage: number
    total: number
    from: number
    to: number
    perPage: number
    firstPageUrl?: string
    lastPageUrl?: string
    nextPageUrl?: string | null
    prevPageUrl?: string | null
    links?: Array<{ url: string | null; label: string; active: boolean }>
  }
  message?: string
}

export class PermissionService {
  private repository: PermissionRepository

  constructor() {
    this.repository = new PermissionRepository()
  }

  async getPermissions(params?: Record<string, any>): Promise<PermissionListResponse> {
    try {
      const response = await this.repository.getAll(params)

      if (response.success && response.data) {
        if (Array.isArray(response.data)) {
          return { success: true, data: response.data }
        }

        const paginated = response.data as PaginatedResponse<Permission>
        const { data, ...pagination } = paginated

        return {
          success: true,
          data,
          pagination: {
            currentPage: pagination.current_page,
            lastPage: pagination.last_page,
            total: pagination.total,
            from: pagination.from,
            to: pagination.to,
            perPage: pagination.per_page,
            firstPageUrl: pagination.first_page_url,
            lastPageUrl: pagination.last_page_url,
            nextPageUrl: pagination.next_page_url,
            prevPageUrl: pagination.prev_page_url,
            links: pagination.links
          }
        }
      }

      return { success: false, message: response.message || 'Failed to fetch permissions' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permissions' }
    }
  }

  async getPermissionById(id: number): Promise<{ success: boolean; data?: Permission; message?: string }> {
    try {
      const response = await this.repository.getById(id)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to fetch permission' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permission' }
    }
  }

  async createPermission(data: CreatePermissionData): Promise<{ success: boolean; data?: Permission; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.create(data)

      if (response.success && response.data) {
        return { success: true, data: response.data, message: response.message }
      }

      return { success: false, message: response.message || 'Failed to create permission', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred while creating permission' }
    }
  }

  async updatePermission(id: number, data: CreatePermissionData): Promise<{ success: boolean; data?: Permission; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.update(id, data)

      if (response.success && response.data) {
        return { success: true, data: response.data, message: response.message }
      }

      return { success: false, message: response.message || 'Failed to update permission', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating permission' }
    }
  }

  async deletePermission(id: number): Promise<{ success: boolean; message?: string }> {
    try {
      const response = await this.repository.deletePermission(id)

      if (response.success) {
        return { success: true, message: response.message }
      }

      return { success: false, message: response.message || 'Failed to delete permission' }
    } catch (error) {
      return { success: false, message: 'An error occurred while deleting permission' }
    }
  }
}
