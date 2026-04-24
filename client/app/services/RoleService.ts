import { RoleRepository } from '../repositories/RoleRepository'
import type { Role, CreateRoleData, PaginatedResponse } from '~/api/types/api.types'

type RoleListResponse = {
  success: boolean
  data?: Role[]
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

export class RoleService {
  private repository: RoleRepository

  constructor() {
    this.repository = new RoleRepository()
  }

  
  async getRoles(params?: Record<string, any>): Promise<RoleListResponse> {
    try {
      const response = await this.repository.getAll(params)

      if (response.success && response.data) {
        const paginated = response.data as PaginatedResponse<Role>
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

      return { success: false, message: response.message || 'Failed to fetch roles' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching roles' }
    }
  }

  async getRoleById(id: number): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      const response = await this.repository.getById(id)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to fetch role' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching role' }
    }
  }

  async createRole(data: CreateRoleData): Promise<{ success: boolean; data?: Role; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.create(data)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to create role', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred while creating role' }
    }
  }

  async updateRole(id: number, data: CreateRoleData): Promise<{ success: boolean; data?: Role; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.update(id, data)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to update role', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating role' }
    }
  }

  async deleteRole(id: number): Promise<{ success: boolean; message?: string }> {
    try {
      const roleResult = await this.getRoleById(id)
      if (roleResult.success && roleResult.data) {
        const isSystemRole = this.isSystemRole(roleResult.data.slug)

        if (isSystemRole) {
          return { success: false, message: 'Cannot delete system roles' }
        }
      }

      const response = await this.repository.deleteRole(id)

      if (response.success) {
        return { success: true }
      }

      return { success: false, message: response.message || 'Failed to delete role' }
    } catch (error) {
      return { success: false, message: 'An error occurred while deleting role' }
    }
  }

  async assignPermissions(id: number, permissionIds: number[]): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      const response = await this.repository.assignPermissions(id, permissionIds)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to assign permissions' }
    } catch (error) {
      return { success: false, message: 'An error occurred while assigning permissions' }
    }
  }

  private isSystemRole(slug: string): boolean {
    return ['super-admin', 'admin', 'user'].includes(slug)
  }
}
