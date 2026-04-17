import { ref, readonly } from 'vue'
import { useRouter } from 'vue-router'
import type { User, CreateUserData } from '~/api/types/api.types'
import { useApi } from '~/composables/useApi'
import { notification } from '~/utils/notification'

export const useUsers = () => {
  const users = ref<User[]>([])
  const loading = ref(false)
  const pagination = ref(null)
  const api = useApi()
  const router = useRouter()

  const fetchUsers = async (params = { limit: 5 }) => {
    loading.value = true
    const response = await api.user.getUsers(params)
    if (response.success) {
      users.value = response.data ?? []
      pagination.value = response.pagination
    } else {
      notification.error(response.message || 'Failed to fetch users')
    }
    loading.value = false
  }

  const updateStatus = async (userId: number, status: boolean) => {
    const response = await api.user.updateUserStatus(userId, status)
    if (response.success) {
      notification.success(response.message || 'User status updated successfully')

      const index = users.value.findIndex(u => Number(u.id) === Number(userId))
      if (index !== -1) {
        users.value[index] = { ...users.value[index], status }
      }
    } else {
      notification.error(response.message || 'Failed to update user status')
    }
  }

  const createUser = async (userData: CreateUserData) => {
    const response = await api.user.createUser(userData)
    if (response.success) {
      notification.success(response.message || 'User created successfully')
      users.value.unshift(response.data as User)
      router.push('/users')
    } else {
      notification.error(response.message || 'Failed to create user')
    }
    return response
  }

  const updateUser = async (id: number, userData: Partial<User>) => {
    const response = await api.user.updateUser(id, userData)
    if (response.success) {
      notification.success(response.message || 'User updated successfully')

      fetchUsers()
      router.push('/users')
    } else {
      notification.error(response.message || 'Failed to update user')
    }
    return response
  }

  const deleteUser = async (userId: number) => {
    const response = await api.user.deleteUser(userId)
    if (response.success) {
      notification.success(response.message || 'User deleted successfully')

      fetchUsers()
    } else {
      notification.error(response.message || 'Failed to delete user')
    }
    return response.success
  }

  return {
    users: readonly(users),
    loading: readonly(loading),
    pagination: readonly(pagination),
    fetchUsers,
    createUser,
    updateUser,
    updateStatus,
    deleteUser
  }
}