<template>
  <div class="p-6">
    <!-- Header -->
    <SharedPageHeader title="Users Management" description="Manage and monitor all system users">
      <template #actions>
        <UIButton variant="primary" @click="navigateToCreate">
          <template #icon>
            <UIIconsPlus class="h-5 w-5" />
          </template>
          Add User
        </UIButton>
      </template>
    </SharedPageHeader>

    <!-- Filters -->
    <UsersFilters :role-options="roleOptions" :loading-roles="loadingRoles" @search="handleSearch"
      @filter="handleFilter">
      <template #advanced-filters>
        <div v-if="showAdvancedFilters" class="mt-4">
          <button @click="showAdvancedFilters = !showAdvancedFilters"
            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 focus:outline-none">
            <UIIconsDocument class="-ml-1 mr-2 h-5 w-5 text-gray-400" />
            Advanced Filters
          </button>
        </div>

        <UsersAdvancedFilters v-if="showAdvancedFilters" v-model:filters="advancedFilters"
          @apply="applyAdvancedFilters" @clear="clearAdvancedFilters" />
      </template>
    </UsersFilters>

    <!-- Users Table -->
    <UsersTable :users="users" :selected-user-ids="selectedUsers" :loading="isLoading"
      :current-page="pagination.currentPage" :last-page="pagination.lastPage" :total="pagination.total"
      :from="pagination.from" :to="pagination.to" @select-all="toggleSelectAll" @select-user="toggleUserSelection"
      @view="navigateToView" @edit="navigateToEdit" @delete="showDeleteConfirm" @page-change="goToPage">
      <!-- Bulk Actions -->
      <template #bulk-actions>
        <UsersBulkActions v-if="selectedUsers.length > 0" :count="selectedUsers.length"
          @activate="showBulkActionModal('activate')" @deactivate="showBulkActionModal('deactivate')"
          @delete="showBulkActionModal('delete')" />
      </template>

      <!-- Empty State Actions -->
      <template #empty-state-actions>
        <UIButton variant="primary" @click="navigateToCreate">
          <template #icon>
            <UIIconsPlus class="h-5 w-5" />
          </template>
          Add User
        </UIButton>
      </template>
    </UsersTable>

    <!-- Modals -->
    <UsersBulkActions v-if="showBulkModal" :action="bulkActionType" :count="selectedUsers.length"
      @confirm="handleBulkAction" @cancel="closeBulkModal" />

    <ModalConfirmationDialog v-if="showDeleteModal" title="Delete User" :message="deleteMessage" type="delete"
      @confirm="confirmDelete" @cancel="closeDeleteModal" />
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { notification } from '~/utils/notification'

definePageMeta({ middleware: ["auth"] });

const router = useRouter()
const api = useApi()

const users = ref<any[]>([])
const selectedUsers = ref<number[]>([])
const isLoading = ref(false)
const loadingRoles = ref(false)
const showAdvancedFilters = ref(false)
const roleOptions = ref<any[]>([])
const showBulkModal = ref(false)
const showDeleteModal = ref(false)
const bulkActionType = ref<'activate' | 'deactivate' | 'delete'>('delete')
const userToDelete = ref<any>(null)
const selectAll = ref(false)


const filters = reactive({
  search: '',
  status: null as boolean | null,
  role: '',
  startDate: null as string | null,
  endDate: null as string | null,
  verified: null as string | null,
  sortBy: 'created_at:desc',
  page: 1,
  limit: 10
})

const advancedFilters = reactive({
  startDate: null as string | null,
  endDate: null as string | null,
  verified: null as string | null,
  sortBy: 'created_at:desc'
})


const pagination = reactive({
  currentPage: 1,
  lastPage: 1,
  total: 0,
  from: 0,
  to: 0,
  perPage: 10
})


const deleteMessage = computed(() => {
  if (userToDelete.value) {
    return `Are you sure you want to delete user "${userToDelete.value.name}"? This action cannot be undone.`
  }
  return ''
})


const fetchUsers = async () => {
  try {
    isLoading.value = true

    const params: any = {
      page: filters.page,
      limit: filters.limit
    }

    if (filters.search) params.search = filters.search
    if (filters.status !== null) params.status = filters.status
    if (filters.role) params.role = filters.role
    if (filters.verified) params.verified = filters.verified === 'verified'
    if (filters.startDate) params.start_date = filters.startDate
    if (filters.endDate) params.end_date = filters.endDate

    if (filters.sortBy) {
      const [sortField, sortDir] = filters.sortBy.split(':')
      params.sort_by = sortField
      params.sort_dir = sortDir
    }

    const response = await api.user.getUsers(params)
    
    if (response.success && response.data) {
      users.value = response.data

      if (response.pagination) {
        Object.assign(pagination, response.pagination)
      }
    } else {
      notification.error(response.message || 'Failed to fetch users')
      users.value = []
    }
  } catch (error) {
    console.error('Error fetching users:', error)
    notification.error('An error occurred while fetching users')
    users.value = []
  } finally {
    isLoading.value = false
  }
}

const fetchRoles = async () => {
  try {
    loadingRoles.value = true
    const response = await api.role.getRoles()

    if (response.success && response.data) {
      roleOptions.value = response.data
    }
  } catch (error) {
    notification.error('Failed to fetch roles')
  } finally {
    loadingRoles.value = false
  }
}

const handleSearch = (search: string) => {
  filters.search = search
  filters.page = 1
  fetchUsers()
}

const handleFilter = (filter: any) => {
  Object.assign(filters, filter)
  filters.page = 1
  fetchUsers()
}

const applyAdvancedFilters = () => {
  Object.assign(filters, advancedFilters)
  filters.page = 1
  fetchUsers()
}

const clearAdvancedFilters = () => {
  advancedFilters.startDate = null
  advancedFilters.endDate = null
  advancedFilters.verified = null
  advancedFilters.sortBy = 'created_at:desc'

  filters.startDate = null
  filters.endDate = null
  filters.verified = null
  filters.sortBy = 'created_at:desc'
  filters.page = 1

  fetchUsers()
}

const toggleSelectAll = (selected: boolean) => {
  if (selected) {
    selectedUsers.value = users.value.map(user => user.id)
  } else {
    selectedUsers.value = []
  }
}

const toggleUserSelection = (userId: number) => {
  const index = selectedUsers.value.indexOf(userId)
  if (index > -1) {
    selectedUsers.value.splice(index, 1)
  } else {
    selectedUsers.value.push(userId)
  }
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= pagination.lastPage) {
    filters.page = page
    fetchUsers()
  }
}

const navigateToCreate = () => {
  router.push('/users/create')
}

const navigateToView = (user: any) => {
  router.push(`/users/${user.id}`)
}

const navigateToEdit = (user: any) => {
  router.push(`/users/${user.id}/edit`)
}

const showDeleteConfirm = (user: any) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  userToDelete.value = null
}

const showBulkActionModal = (action: 'activate' | 'deactivate' | 'delete') => {
  if (selectedUsers.value.length === 0) return

  bulkActionType.value = action
  showBulkModal.value = true
}

const closeBulkModal = () => {
  showBulkModal.value = false
  bulkActionType.value = 'delete'
}

const handleBulkAction = async () => {
  try {
    isLoading.value = true

    switch (bulkActionType.value) {
      case 'activate':
        await api.user.bulkUpdateStatus(selectedUsers.value, true)
        notification.success(`Activated ${selectedUsers.value.length} user(s)`)
        break
      case 'deactivate':
        await api.user.bulkUpdateStatus(selectedUsers.value, false)
        notification.success(`Deactivated ${selectedUsers.value.length} user(s)`)
        break
      case 'delete':
        await api.user.bulkDeleteUsers(selectedUsers.value)
        notification.success(`Deleted ${selectedUsers.value.length} user(s)`)
        break
    }

    selectedUsers.value = []
    selectAll.value = false
    fetchUsers()
  } catch (error) {
    notification.error('An error occurred while performing bulk action')
  } finally {
    isLoading.value = false
    closeBulkModal()
  }
}

const confirmDelete = async () => {
  if (!userToDelete.value) return

  try {
    isLoading.value = true
    const response = await api.user.deleteUser(userToDelete.value.id)

    if (response.success) {
      notification.success(`User "${userToDelete.value.name}" deleted successfully`)
      fetchUsers()
    } else {
      notification.error(response.message || 'Failed to delete user')
    }
  } catch (error) {
    console.error('Error deleting user:', error)
    notification.error('An error occurred while deleting user')
  } finally {
    isLoading.value = false
    closeDeleteModal()
  }
}

onMounted(() => {
  fetchUsers()
  fetchRoles()
})


watch(selectedUsers, (newVal) => {
  selectAll.value = newVal.length === users.value.length && users.value.length > 0
})

watch(() => [filters.startDate, filters.endDate], () => {
  if (filters.startDate && filters.endDate && filters.startDate > filters.endDate) {
    const temp = filters.startDate
    filters.startDate = filters.endDate
    filters.endDate = temp
  }
})
</script>