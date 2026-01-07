import { PermissionRepository } from '../repositories/PermissionRepository'
import type { Permission } from '~/api/types/api.types'

export class PermissionService {
  private repository: PermissionRepository

  constructor() {
    this.repository = new PermissionRepository()
  }

  async getPermissions(): Promise<{ success: boolean; data?: Permission[]; message?: string }> {
    try {
      const response = await this.repository.getAll()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch permissions' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permissions' }
    }
  }

  async getModules(): Promise<{ success: boolean; data?: string[]; message?: string }> {
    try {
      const response = await this.repository.getModules()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch modules' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching modules' }
    }
  }

  async getPermissionsByModule(module: string): Promise<{ success: boolean; data?: Permission[]; message?: string }> {
    try {
      const response = await this.repository.getByModule(module)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch permissions by module' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permissions by module' }
    }
  }

  async syncPermissions(modules: Record<string, string[]>): Promise<{ success: boolean; message?: string }> {
    try {
      const response = await this.repository.sync(modules)
      
      if (response.success) {
        return { success: true }
      }
      
      return { success: false, message: response.message || 'Failed to sync permissions' }
    } catch (error) {
      return { success: false, message: 'An error occurred while syncing permissions' }
    }
  }

  // Business logic methods
  async getPermissionsGroupedByModule(): Promise<{ success: boolean; data?: Record<string, Permission[]>; message?: string }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      const grouped: Record<string, Permission[]> = {}
      
      permissionsResult.data.forEach(permission => {
        if (!grouped[permission.module]) {
          grouped[permission.module] = []
        }
        grouped[permission.module].push(permission)
      })

      return { success: true, data: grouped }
    } catch (error) {
      return { success: false, message: 'An error occurred while grouping permissions' }
    }
  }

  async getPermissionsWithUsage(): Promise<{ success: boolean; data?: any[]; message?: string }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      const permissionsWithUsage = permissionsResult.data.map(permission => ({
        ...permission,
        roleCount: 0, // You would fetch this from RoleService
        isSystemPermission: this.isSystemPermission(permission.slug),
        isCorePermission: permission.module === 'system',
        createdAtFormatted: this.formatDate(permission.created_at),
        updatedAtFormatted: this.formatDate(permission.updated_at)
      }))

      return { success: true, data: permissionsWithUsage }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permissions with usage' }
    }
  }

  async validatePermissionSlug(slug: string): Promise<{ success: boolean; valid: boolean; message?: string }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return { success: false, valid: false, message: permissionsResult.message }
      }

      const exists = permissionsResult.data.some(permission => permission.slug === slug)

      return { 
        success: true, 
        valid: !exists,
        message: exists ? 'Permission slug already exists' : 'Permission slug is available'
      }
    } catch (error) {
      return { success: false, valid: false, message: 'An error occurred while validating permission slug' }
    }
  }

  async searchPermissions(query: string): Promise<{ success: boolean; data?: Permission[]; message?: string }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      const filteredPermissions = permissionsResult.data.filter(permission =>
        permission.name.toLowerCase().includes(query.toLowerCase()) ||
        permission.slug.toLowerCase().includes(query.toLowerCase()) ||
        permission.module.toLowerCase().includes(query.toLowerCase()) ||
        permission.description?.toLowerCase().includes(query.toLowerCase())
      )

      return { success: true, data: filteredPermissions }
    } catch (error) {
      return { success: false, message: 'An error occurred while searching permissions' }
    }
  }

  async getPermissionStatistics(): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      const permissions = permissionsResult.data
      const totalPermissions = permissions.length
      
      const groupedByModule = this.groupPermissionsByModule(permissions)
      const systemPermissions = permissions.filter(perm => this.isSystemPermission(perm.slug))
      
      const modulesWithCount = Object.entries(groupedByModule).map(([module, perms]) => ({
        module,
        count: perms.length,
        permissions: perms.map(p => p.name)
      }))

      return {
        success: true,
        data: {
          totalPermissions,
          systemPermissions: systemPermissions.length,
          customPermissions: totalPermissions - systemPermissions.length,
          totalModules: modulesWithCount.length,
          modulesWithCount,
          averagePermissionsPerModule: modulesWithCount.length > 0 ? 
            (totalPermissions / modulesWithCount.length).toFixed(2) : 0,
          permissionsByAction: this.groupPermissionsByAction(permissions)
        }
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching permission statistics' }
    }
  }

  private groupPermissionsByModule(permissions: Permission[]): Record<string, Permission[]> {
    const grouped: Record<string, Permission[]> = {}
    
    permissions.forEach(permission => {
      if (!grouped[permission.module]) {
        grouped[permission.module] = []
      }
      grouped[permission.module].push(permission)
    })

    return grouped
  }

  private groupPermissionsByAction(permissions: Permission[]): Record<string, number> {
    const actions: Record<string, number> = {}
    
    permissions.forEach(permission => {
      // Extract action from slug (e.g., "users.create" -> "create")
      const parts = permission.slug.split('.')
      const action = parts.length > 1 ? parts[1] : 'other'
      
      actions[action] = (actions[action] || 0) + 1
    })

    return actions
  }

  async generateDefaultPermissions(): Promise<Record<string, string[]>> {
    return {
      users: ['view', 'create', 'edit', 'delete', 'export', 'bulk_update', 'view_activity'],
      roles: ['view', 'create', 'edit', 'delete', 'assign_permissions', 'export'],
      permissions: ['view', 'manage', 'sync'],
      translations: ['view', 'edit', 'export'],
      files: ['view', 'upload', 'delete', 'download'],
      settings: ['view', 'edit', 'backup', 'restore'],
      dashboard: ['view', 'export', 'customize'],
      system: ['maintenance', 'logs', 'monitoring'],
      notifications: ['view', 'create', 'send', 'manage_templates'],
      reports: ['view', 'generate', 'export']
    }
  }

  async syncDefaultPermissions(): Promise<{ success: boolean; message?: string }> {
    try {
      const defaultPermissions = await this.generateDefaultPermissions()
      return await this.syncPermissions(defaultPermissions)
    } catch (error) {
      return { success: false, message: 'An error occurred while syncing default permissions' }
    }
  }

  async exportPermissions(format: 'json' | 'csv' = 'json'): Promise<{ success: boolean; data?: string; message?: string }> {
    try {
      const permissionsResult = await this.getPermissionsWithUsage()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      let exportedData: string
      
      if (format === 'csv') {
        const headers = ['ID', 'Name', 'Slug', 'Module', 'Description', 'Created At', 'Last Updated', 'Type']
        const rows = permissionsResult.data.map(permission => [
          permission.id,
          permission.name,
          permission.slug,
          permission.module,
          permission.description || '',
          permission.createdAtFormatted,
          permission.updatedAtFormatted,
          permission.isSystemPermission ? 'System' : 'Custom'
        ])
        
        const csvContent = [
          headers.join(','),
          ...rows.map(row => row.join(','))
        ].join('\n')
        
        exportedData = csvContent
      } else {
        exportedData = JSON.stringify(permissionsResult.data, null, 2)
      }

      return { success: true, data: exportedData }
    } catch (error) {
      return { success: false, message: 'An error occurred while exporting permissions' }
    }
  }

  async comparePermissions(permissionSet1: string[], permissionSet2: string[]): Promise<{ 
    success: boolean; 
    data?: {
      onlyInSet1: string[],
      onlyInSet2: string[],
      common: string[],
      differences: {
        permission: string,
        inSet1: boolean,
        inSet2: boolean
      }[]
    }; 
    message?: string 
  }> {
    try {
      const permissionsResult = await this.getPermissions()
      
      if (!permissionsResult.success || !permissionsResult.data) {
        return permissionsResult
      }

      const permissionSlugs = permissionsResult.data.map(p => p.slug)
      
      const validSet1 = permissionSet1.filter(slug => permissionSlugs.includes(slug))
      const validSet2 = permissionSet2.filter(slug => permissionSlugs.includes(slug))

      const onlyInSet1 = validSet1.filter(slug => !validSet2.includes(slug))
      const onlyInSet2 = validSet2.filter(slug => !validSet1.includes(slug))
      const common = validSet1.filter(slug => validSet2.includes(slug))

      const differences = permissionSlugs.map(slug => ({
        permission: slug,
        inSet1: validSet1.includes(slug),
        inSet2: validSet2.includes(slug)
      }))

      return {
        success: true,
        data: {
          onlyInSet1,
          onlyInSet2,
          common,
          differences
        }
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while comparing permissions' }
    }
  }

  async generatePermissionSlug(name: string, module: string): Promise<{ success: boolean; data?: string; message?: string }> {
    try {
      // Generate slug from name and module
      const baseSlug = name.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim()
      
      const modulePrefix = module.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim()
      
      let slug = `${modulePrefix}.${baseSlug}`
      let counter = 1
      let isUnique = false

      // Check if slug exists and generate unique one
      while (!isUnique && counter < 100) {
        const validation = await this.validatePermissionSlug(slug)
        if (validation.success && validation.valid) {
          isUnique = true
        } else {
          slug = `${modulePrefix}.${baseSlug}-${counter}`
          counter++
        }
      }

      if (!isUnique) {
        return { success: false, message: 'Could not generate unique permission slug' }
      }

      return { success: true, data: slug }
    } catch (error) {
      return { success: false, message: 'An error occurred while generating permission slug' }
    }
  }

  // Helper methods
  private isSystemPermission(slug: string): boolean {
    const systemPermissionPrefixes = ['system.', 'admin.', 'super.']
    return systemPermissionPrefixes.some(prefix => slug.startsWith(prefix))
  }

  private formatDate(dateString: string): string {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  }
}
