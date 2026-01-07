import { UserRepository } from '../repositories/UserRepository'
import type { User, CreateUserData, UpdateUserData } from '~/api/types/api.types'

export class UserService {
  private repository: UserRepository

  constructor() {
    this.repository = new UserRepository()
  }

  async getUsers(params?: any): Promise<{ success: boolean; data?: User[]; pagination?: any; message?: string }> {
    try {
      const response = await this.repository.getAll(params)
      
      if (response.success && response.data) {
        const { data, ...pagination } = response.data
        
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
      
      return { success: false, message: response.message || 'Failed to fetch users' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching users' }
    }
  }

  async getUserById(id: number): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.getById(id)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch user' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching user' }
    }
  }

  async createUser(data: CreateUserData): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.create(data)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to create user' }
    } catch (error) {
      return { success: false, message: 'An error occurred while creating user' }
    }
  }

  async updateUser(id: number, data: UpdateUserData): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.update(id, data)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to update user' }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating user' }
    }
  }

  async deleteUser(id: number): Promise<{ success: boolean; message?: string }> {
    try {
      const response = await this.repository.deleteById(id)

      if (response.success) {
        return { success: true }
      }
      
      return { success: false, message: response.message || 'Failed to delete user' }
    } catch (error) {
      return { success: false, message: 'An error occurred while deleting user' }
    }
  }

  async updateUserStatus(id: number, status: boolean): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.updateStatus(id, status)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to update user status' }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating user status' }
    }
  }

  async assignRoles(id: number, roleIds: number[]): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.assignRoles(id, roleIds)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to assign roles' }
    } catch (error) {
      return { success: false, message: 'An error occurred while assigning roles' }
    }
  }

  async removeRoles(id: number, roleIds: number[]): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      // Get current user roles
      const userResult = await this.getUserById(id)
      if (!userResult.success || !userResult.data) {
        return userResult
      }

      const currentRoles = userResult.data.roles || []
      const updatedRoleIds = currentRoles
        .filter(role => !roleIds.includes(role.id))
        .map(role => role.id)

      return await this.assignRoles(id, updatedRoleIds)
    } catch (error) {
      return { success: false, message: 'An error occurred while removing roles' }
    }
  }

  async getProfile(): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.getProfile()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch profile' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching profile' }
    }
  }

  async updateProfile(data: UpdateUserData): Promise<{ success: boolean; data?: User; message?: string }> {
    try {
      const response = await this.repository.updateProfile(data)
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to update profile' }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating profile' }
    }
  }

  async searchUsers(query: string, params?: any): Promise<{ success: boolean; data?: User[]; pagination?: any; message?: string }> {
    try {
      const searchParams = {
        ...params,
        search: query
      }
      return await this.getUsers(searchParams)
    } catch (error) {
      return { success: false, message: 'An error occurred while searching users' }
    }
  }

  async getUsersByRole(roleSlug: string): Promise<{ success: boolean; data?: User[]; message?: string }> {
    try {
      const usersResult = await this.getUsers()
      
      if (!usersResult.success || !usersResult.data) {
        return usersResult
      }

      const filteredUsers = usersResult.data.filter(user =>
        user.roles?.some(role => role.slug === roleSlug)
      )

      return { success: true, data: filteredUsers }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching users by role' }
    }
  }

  async getUsersByStatus(status: boolean): Promise<{ success: boolean; data?: User[]; pagination?: any; message?: string }> {
    try {
      return await this.getUsers({ status })
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching users by status' }
    }
  }

  async getUsersByDateRange(startDate: string, endDate: string): Promise<{ success: boolean; data?: User[]; message?: string }> {
    try {
      const usersResult = await this.getUsers()
      
      if (!usersResult.success || !usersResult.data) {
        return usersResult
      }

      const start = new Date(startDate)
      const end = new Date(endDate)
      
      const filteredUsers = usersResult.data.filter(user => {
        const createdAt = new Date(user.created_at)
        return createdAt >= start && createdAt <= end
      })

      return { success: true, data: filteredUsers }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching users by date range' }
    }
  }

  async getUserStatistics(): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const usersResult = await this.getUsers()
      
      if (!usersResult.success || !usersResult.data) {
        return usersResult
      }

      const users = usersResult.data
      const totalUsers = users.length
      const activeUsers = users.filter(user => user.status).length
      const verifiedUsers = users.filter(user => user.email_verified_at).length
      
      // Group by role
      const rolesCount: Record<string, number> = {}
      users.forEach(user => {
        user.roles?.forEach(role => {
          rolesCount[role.name] = (rolesCount[role.name] || 0) + 1
        })
      })

      // Group by creation date (last 30 days)
      const thirtyDaysAgo = new Date()
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30)
      
      const recentUsers = users.filter(user => {
        const createdAt = new Date(user.created_at)
        return createdAt >= thirtyDaysAgo
      })

      const dailyRegistrations = this.groupUsersByDay(recentUsers)

      return {
        success: true,
        data: {
          totalUsers,
          activeUsers,
          inactiveUsers: totalUsers - activeUsers,
          verifiedUsers,
          unverifiedUsers: totalUsers - verifiedUsers,
          rolesCount,
          dailyRegistrations,
          activePercentage: totalUsers > 0 ? ((activeUsers / totalUsers) * 100).toFixed(2) : 0,
          verifiedPercentage: totalUsers > 0 ? ((verifiedUsers / totalUsers) * 100).toFixed(2) : 0
        }
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching user statistics' }
    }
  }

  private groupUsersByDay(users: User[]): any[] {
    const groups: Record<string, number> = {}
    
    users.forEach(user => {
      const date = new Date(user.created_at).toISOString().split('T')[0]
      groups[date] = (groups[date] || 0) + 1
    })

    return Object.entries(groups).map(([date, count]) => ({
      date,
      count
    }))
  }

  async exportUsers(format: 'json' | 'csv' = 'json'): Promise<{ success: boolean; data?: string; message?: string }> {
    try {
      const usersResult = await this.getUsers()
      
      if (!usersResult.success || !usersResult.data) {
        return usersResult
      }

      const usersWithDetails = usersResult.data.map(user => ({
        id: user.id,
        name: user.name,
        email: user.email,
        phone: user.phone || '',
        status: user.status ? 'Active' : 'Inactive',
        verified: user.email_verified_at ? 'Yes' : 'No',
        roles: user.roles?.map(role => role.name).join(', ') || '',
        createdAt: this.formatDate(user.created_at),
        lastUpdated: this.formatDate(user.updated_at)
      }))

      let exportedData: string
      
      if (format === 'csv') {
        const headers = ['ID', 'Name', 'Email', 'Phone', 'Status', 'Verified', 'Roles', 'Created At', 'Last Updated']
        const rows = usersWithDetails.map(user => [
          user.id,
          user.name,
          user.email,
          user.phone,
          user.status,
          user.verified,
          user.roles,
          user.createdAt,
          user.lastUpdated
        ])
        
        const csvContent = [
          headers.join(','),
          ...rows.map(row => row.join(','))
        ].join('\n')
        
        exportedData = csvContent
      } else {
        exportedData = JSON.stringify(usersWithDetails, null, 2)
      }

      return { success: true, data: exportedData }
    } catch (error) {
      return { success: false, message: 'An error occurred while exporting users' }
    }
  }

  async bulkUpdateStatus(userIds: number[], status: boolean): Promise<{ success: boolean; message?: string }> {
    try {
      const results = await Promise.all(
        userIds.map(id => this.updateUserStatus(id, status))
      )

      const failedUpdates = results.filter(result => !result.success)
      
      if (failedUpdates.length > 0) {
        return {
          success: false,
          message: `Failed to update ${failedUpdates.length} user(s)`
        }
      }

      return { success: true }
    } catch (error) {
      return { success: false, message: 'An error occurred while bulk updating user status' }
    }
  }

  async bulkDeleteUsers(userIds: number[]): Promise<{ success: boolean; message?: string }> {
    try {
      const results = await Promise.all(
        userIds.map(id => this.deleteUser(id))
      )

      const failedDeletes = results.filter(result => !result.success)
      
      if (failedDeletes.length > 0) {
        return {
          success: false,
          message: `Failed to delete ${failedDeletes.length} user(s)`
        }
      }

      return { success: true }
    } catch (error) {
      return { success: false, message: 'An error occurred while bulk deleting users' }
    }
  }

  async getUserActivity(userId: number): Promise<{ success: boolean; data?: any; message?: string }> {
    try {
      const activities = [
        {
          id: 1,
          action: 'login',
          description: 'User logged in',
          timestamp: new Date().toISOString(),
          ip: '192.168.1.1',
          userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        },
        {
          id: 2,
          action: 'profile_update',
          description: 'Updated profile information',
          timestamp: new Date(Date.now() - 86400000).toISOString(),
          ip: '192.168.1.1',
          userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        },
        {
          id: 3,
          action: 'password_change',
          description: 'Changed password',
          timestamp: new Date(Date.now() - 172800000).toISOString(),
          ip: '192.168.1.2',
          userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        }
      ]

      return { success: true, data: activities }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching user activity' }
    }
  }

  private formatDate(dateString: string): string {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  }
}
