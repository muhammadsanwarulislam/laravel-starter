import { UserRepository } from '../repositories/UserRepository'
import type { User, UserProfileResponse, CreateUserData, UpdateUserData } from '~/api/types/api.types'

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
        return { success: true, data: response }
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

  async getProfile(): Promise<{ success: boolean; data?: UserProfileResponse; message?: string }> {
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

  async updateProfile(data: UpdateUserData): Promise<{ success: boolean; data?: UserProfileResponse; message?: string; errors?: Record<string, string[]> }> {
    try {
      const response = await this.repository.updateProfile(data)

      if (response.success && response.data) {
        return { success: true, data: response.data }
      }

      return { success: false, message: response.message || 'Failed to update profile', errors: response.errors }
    } catch (error) {
      return { success: false, message: 'An error occurred while updating profile' }
    }
  }

  async uploadFile(
    file: File,
    type: string,
    attachableType?: string,
    attachableId?: number,
    replaceExisting = false,
  ): Promise<{ success: boolean; data?: FileManager; message?: string }> {
    const formData = new FormData();
    formData.append("file", file);
    formData.append("type", type);
    if (attachableType) formData.append("attachable_type", attachableType);
    if (attachableId) formData.append("attachable_id", String(attachableId));
    if (replaceExisting) formData.append("replace_existing", "true");

    try {
      const response = await this.repository.uploadFile(formData);
      if (response.success && response.data) {
        return {
          success: true,
          data: response.data,
          message: response.message,
        };
      }
      return { success: false, message: response.message || "Upload failed" };
    } catch (error) {
      return { success: false, message: "An error occurred during upload" };
    }
  }
}
