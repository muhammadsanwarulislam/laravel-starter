import { RoleRepository } from '../repositories/RoleRepository'
import type { Role, CreateRoleData } from '~/api/types/api.types'

export class RoleService {
  private repository: RoleRepository

  constructor() {
    this.repository = new RoleRepository()
  }

  
  async getRoles(): Promise<{ success: boolean; data?: Role[]; pagination?: any; message?: string }> {
    try {
      const response = await this.repository.getAll()

      if (response.success && response.data) {
        return { success: true, data: response.data }
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

  async createRole(data: CreateRoleData): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      // Validate role name
      const validation = await this.validateRoleName(data.name)
      if (!validation.success || !validation.valid) {
        return { success: false, message: validation.message || 'Role name validation failed' }
      }

      const response = await this.repository.create(data)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to create role' }
    } catch (error) {
      return { success: false, message: 'An error occurred while creating role' }
    }
  }

  async updateRole(id: number, data: CreateRoleData): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      // Validate role name excluding current role
      const validation = await this.validateRoleName(data.name, id)
      if (!validation.success || !validation.valid) {
        return { success: false, message: validation.message || 'Role name validation failed' }
      }

      const response = await this.repository.update(id, data)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to update role' }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating role' }
    }
  }

  async deleteRole(id: number): Promise<{ success: boolean; message?: string }> {
    try {
      // Check if role is system role
      const roleResult = await this.getRoleById(id)
      if (roleResult.success && roleResult.data) {
        const isSystemRole = this.isSystemRole(roleResult.data.slug)
        if (isSystemRole) {
          return { success: false, message: 'Cannot delete system roles' }
        }
      }

      const response = await this.repository.delete(id)

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

  async removePermissions(id: number, permissionIds: number[]): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      // For remove permissions, we need to get current permissions and filter
      const roleResult = await this.getRoleById(id)
      if (!roleResult.success || !roleResult.data) {
        return roleResult
      }

      const currentPermissions = roleResult.data.permissions || []
      const updatedPermissionIds = currentPermissions
        .filter(perm => !permissionIds.includes(perm.id))
        .map(perm => perm.id)

      return await this.assignPermissions(id, updatedPermissionIds)
    } catch (error) {
      return { success: false, message: 'An error occurred while removing permissions' }
    }
  }

  async togglePermission(id: number, permissionId: number): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      const roleResult = await this.getRoleById(id)
      if (!roleResult.success || !roleResult.data) {
        return roleResult
      }

      const currentPermissions = roleResult.data.permissions || []
      const hasPermission = currentPermissions.some(perm => perm.id === permissionId)

      if (hasPermission) {
        return await this.removePermissions(id, [permissionId])
      } else {
        return await this.assignPermissions(id, [...currentPermissions.map(p => p.id), permissionId])
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while toggling permission' }
    }
  }

  // Business logic methods
  async getRoleWithPermissions(id: number): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const roleResult = await this.getRoleById(id)

      if (!roleResult.success || !roleResult.data) {
        return roleResult
      }

      const role = roleResult.data
      return {
        success: true,
        data: {
          ...role,
          permissionCount: role.permissions?.length || 0,
          isSystemRole: this.isSystemRole(role.slug),
          isEditable: !this.isSystemRole(role.slug),
          createdAtFormatted: this.formatDate(role.created_at),
          updatedAtFormatted: this.formatDate(role.updated_at)
        }
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching role details' }
    }
  }

  async getRolesWithPermissionCount(): Promise<{ success: boolean; data?: any[]; message?: string }> {
    try {
      const rolesResult = await this.getRoles()

      if (!rolesResult.success || !rolesResult.data) {
        return rolesResult
      }

      const rolesWithCounts = rolesResult.data.map(role => ({
        ...role,
        permissionCount: role.permissions?.length || 0,
        userCount: 0, // You would fetch this from user service
        isSystemRole: this.isSystemRole(role.slug),
        isEditable: !this.isSystemRole(role.slug),
        createdAtFormatted: this.formatDate(role.created_at)
      }))

      return { success: true, data: rolesWithCounts }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching roles with counts' }
    }
  }

  async validateRoleName(name: string, excludeId?: number): Promise<{ success: boolean; valid: boolean; message?: string }> {
    try {
      const rolesResult = await this.getRoles()

      if (!rolesResult.success || !rolesResult.data) {
        return { success: false, valid: false, message: rolesResult.message }
      }

      const slug = name.toLowerCase().replace(/\s+/g, '-')
      const exists = rolesResult.data.some(role =>
        role.slug === slug && (!excludeId || role.id !== excludeId)
      )

      // Check against system role slugs
      const systemRoles = ['super-admin', 'admin', 'user', 'moderator', 'editor']
      const isSystemRole = systemRoles.includes(slug)

      return {
        success: true,
        valid: !exists && !isSystemRole,
        message: exists ? 'Role name already exists' :
          isSystemRole ? 'Cannot use system role names' :
            'Role name is available'
      }
    } catch (error) {
      return { success: false, valid: false, message: 'An error occurred while validating role name' }
    }
  }

  async searchRoles(query: string): Promise<{ success: boolean; data?: Role[]; message?: string }> {
    try {
      const rolesResult = await this.getRoles()

      if (!rolesResult.success || !rolesResult.data) {
        return rolesResult
      }

      const filteredRoles = rolesResult.data.filter(role =>
        role.name.toLowerCase().includes(query.toLowerCase()) ||
        role.slug.toLowerCase().includes(query.toLowerCase()) ||
        role.description?.toLowerCase().includes(query.toLowerCase())
      )

      return { success: true, data: filteredRoles }
    } catch (error) {
      return { success: false, message: 'An error occurred while searching roles' }
    }
  }

  async getRolesByPermission(permissionSlug: string): Promise<{ success: boolean; data?: Role[]; message?: string }> {
    try {
      const rolesResult = await this.getRoles()

      if (!rolesResult.success || !rolesResult.data) {
        return rolesResult
      }

      const filteredRoles = rolesResult.data.filter(role =>
        role.permissions?.some(permission => permission.slug === permissionSlug)
      )

      return { success: true, data: filteredRoles }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching roles by permission' }
    }
  }

  async duplicateRole(id: number, newName: string): Promise<{ success: boolean; data?: Role; message?: string }> {
    try {
      const roleResult = await this.getRoleById(id)
      if (!roleResult.success || !roleResult.data) {
        return roleResult
      }

      const role = roleResult.data

      // Validate new name
      const validation = await this.validateRoleName(newName)
      if (!validation.success || !validation.valid) {
        return { success: false, message: validation.message }
      }

      const newRoleData: CreateRoleData = {
        name: newName,
        slug: newName.toLowerCase().replace(/\s+/g, '-'),
        description: role.description
      }

      const createResult = await this.createRole(newRoleData)
      if (!createResult.success || !createResult.data) {
        return createResult
      }

      // Copy permissions
      if (role.permissions && role.permissions.length > 0) {
        const permissionIds = role.permissions.map(p => p.id)
        return await this.assignPermissions(createResult.data.id, permissionIds)
      }

      return createResult
    } catch (error) {
      return { success: false, message: 'An error occurred while duplicating role' }
    }
  }

  async exportRoles(format: 'json' | 'csv' = 'json'): Promise<{ success: boolean; data?: string; message?: string }> {
    try {
      const rolesResult = await this.getRolesWithPermissionCount()

      if (!rolesResult.success || !rolesResult.data) {
        return rolesResult
      }

      let exportedData: string

      if (format === 'csv') {
        const headers = ['ID', 'Name', 'Slug', 'Description', 'Permissions', 'Created At']
        const rows = rolesResult.data.map(role => [
          role.id,
          role.name,
          role.slug,
          role.description || '',
          role.permissionCount,
          role.createdAtFormatted
        ])

        const csvContent = [
          headers.join(','),
          ...rows.map(row => row.join(','))
        ].join('\n')

        exportedData = csvContent
      } else {
        exportedData = JSON.stringify(rolesResult.data, null, 2)
      }

      return { success: true, data: exportedData }
    } catch (error) {
      return { success: false, message: 'An error occurred while exporting roles' }
    }
  }

  // Helper methods
  private isSystemRole(slug: string): boolean {
    const systemRoles = ['super-admin', 'admin', 'user']
    return systemRoles.includes(slug)
  }

  private formatDate(dateString: string): string {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  }

  async getRoleStatistics(): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const rolesResult = await this.getRoles()

      if (!rolesResult.success || !rolesResult.data) {
        return rolesResult
      }

      const totalRoles = rolesResult.data.length
      const systemRoles = rolesResult.data.filter(role => this.isSystemRole(role.slug))
      const customRoles = rolesResult.data.filter(role => !this.isSystemRole(role.slug))

      const totalPermissions = rolesResult.data.reduce((acc, role) =>
        acc + (role.permissions?.length || 0), 0
      )

      return {
        success: true,
        data: {
          totalRoles,
          systemRoles: systemRoles.length,
          customRoles: customRoles.length,
          totalPermissions,
          averagePermissionsPerRole: totalRoles > 0 ? (totalPermissions / totalRoles).toFixed(2) : 0,
          rolesByPermissionCount: this.groupRolesByPermissionCount(rolesResult.data)
        }
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching role statistics' }
    }
  }

  private groupRolesByPermissionCount(roles: Role[]): any[] {
    const groups = [
      { name: 'No Permissions', count: 0 },
      { name: '1-5 Permissions', count: 0 },
      { name: '6-10 Permissions', count: 0 },
      { name: '11-20 Permissions', count: 0 },
      { name: '20+ Permissions', count: 0 }
    ]

    roles.forEach(role => {
      const permissionCount = role.permissions?.length || 0

      if (permissionCount === 0) {
        groups[0].count++
      } else if (permissionCount <= 5) {
        groups[1].count++
      } else if (permissionCount <= 10) {
        groups[2].count++
      } else if (permissionCount <= 20) {
        groups[3].count++
      } else {
        groups[4].count++
      }
    })

    return groups
  }
}