import { ref, readonly } from 'vue'
import { useRouter } from 'vue-router'
import type { Role, Permission, CreateRoleData, CreatePermissionData } from '~/api/types/api.types'
import { useApi } from '~/composables/useApi'
import { notification } from '~/utils/notification'

export const useRolePermission = () => {
  const roles = ref<Role[]>([])
  const permissions = ref<Permission[]>([])
  const loading = ref(false)
  const pagination = ref(null)
  const api = useApi()
  const router = useRouter()

  const fetchRoles = async (params = { limit: 5 }) => {
    loading.value = true
    const response = await api.role.getRoles(params)
    if (response.success) {
      roles.value = response.data ?? []
      pagination.value = response.pagination
    } else {
      notification.error(response.message || 'Failed to fetch roles')
    }
    loading.value = false
  }

  const fetchPermissions = async (params = { limit: 5 }) => {
    loading.value = true
    const response = await api.permission.getPermissions(params)
    if (response.success) {
      permissions.value = response.data ?? []
      pagination.value = response.pagination
    } else {
      notification.error(response.message || 'Failed to fetch permissions')
    }
    loading.value = false
  }

  const deleteRole = async (id: number) => {
    const response = await api.role.deleteRole(id)
    if (response.success) {
      notification.success(response.message || 'Role updated successfully')

      const index = roles.value.findIndex(u => Number(u.id) === Number(id))
      if (index !== -1) {
        roles.value.splice(index, 1)
      }

      router.push('/roles')
    } else {
      notification.error(response.message || 'Failed to update role')
    }
    return response
  }

  const createRole = async (roleData: CreateRoleData) => {
    const response = await api.role.createRole(roleData)
    if (response.success && response.data) {
      notification.success(response.message || 'Role created successfully')
      router.push('/roles')
    } else {
      notification.error(response.message || 'Failed to create role')
    }
    return response
  }

  const updateRole = async (id: number, roleData: CreateRoleData) => {
    const response = await api.role.updateRole(id, roleData)
    if (response.success && response.data) {
      notification.success(response.message || 'Role updated successfully')
      router.push('/roles')
    } else {
      notification.error(response.message || 'Failed to update role')
    }
    return response
  }

  const createPermission = async (permissionData: CreatePermissionData) => {
    const response = await api.permission.createPermission(permissionData)
    if (response.success && response.data) {
      notification.success(response.message || 'Permission created successfully')
      router.push('/permissions')
    } else {
      notification.error(response.message || 'Failed to create permission')
    }
    return response
  }

  const updatePermission = async (id: number, permissionData: CreatePermissionData) => {
    const response = await api.permission.updatePermission(id, permissionData)
    if (response.success && response.data) {
      notification.success(response.message || 'Permission updated successfully')
      router.push('/permissions')
    } else {
      notification.error(response.message || 'Failed to update permission')
    }
    return response
  }

  const deletePermission = async (id: number) => {
    const response = await api.permission.deletePermission(id)
    if (response.success) {
      notification.success(response.message || 'Permission deleted successfully')

      const index = permissions.value.findIndex(permission => Number(permission.id) === Number(id))
      if (index !== -1) {
        permissions.value.splice(index, 1)
      }

      router.push('/permissions')
    } else {
      notification.error(response.message || 'Failed to delete permission')
    }
    return response
  }

  return {
    roles: readonly(roles),
    permissions: readonly(permissions),
    loading: readonly(loading),
    pagination: readonly(pagination),
    fetchRoles,
    fetchPermissions,
    createRole,
    updateRole,
    deleteRole,
    createPermission,
    updatePermission,
    deletePermission
  }
}
