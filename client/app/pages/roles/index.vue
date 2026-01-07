<template>
  <div class="p-6">
    <!-- Header -->
    <SharedPageHeader title="Roles Management" description="Manage and monitor all system roles">
      <template #actions>
        <UIButton variant="primary" @click="navigateToCreate">
          <template #icon>
            <UIIconsPlus class="h-5 w-5" />
          </template>
          Add Role
        </UIButton>
      </template>
    </SharedPageHeader>

    <!-- Filters -->
    <RolesFilters :role-options="roleOptions" :loading-roles="loadingRoles" @search="handleSearch"
      @filter="handleFilter">
      <template #advanced-filters>
        <div v-if="showAdvancedFilters" class="mt-4">
          <button @click="showAdvancedFilters = !showAdvancedFilters"
            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 focus:outline-none">
            <UIIconsDocument class="-ml-1 mr-2 h-5 w-5 text-gray-400" />
            Advanced Filters
          </button>
        </div>

        <RolesAdvancedFilters v-if="showAdvancedFilters" v-model:filters="advancedFilters"
          @apply="applyAdvancedFilters" @clear="clearAdvancedFilters" />
      </template>
    </RolesFilters>

    <!-- Table -->
    <RolesTable :loading="isLoading" :roles="roles" />
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { notification } from '~/utils/notification'

const router = useRouter()
const api = useApi()

// State
const roles = ref<Array<any>>([])
const selectedRoles = ref<Array<number>>([])
const isLoading = ref(false)
const loadingRoles = ref(false)
const showAdvancedFilters = ref(false)
const roleOptions = ref<Array<any>>([])
const showBulkModal = ref(false)
const showDeleteModal = ref(false)
const bulkActionType = ref<'activate' | 'deactivate' | 'delete'>('delete')
const roleToDelete = ref<any>(null)
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


// Methods
const fetchRoles = async () => {
  try {
    isLoading.value = true

    const response = await api.role.getRoles()
    if(response.success && response.data) {
      roles.value = response.data
    }
  } catch (error) {
    notification.error('Failed to fetch roles')
  } finally {
    isLoading.value = false
  }
}

const fetchRolesOptions = async () => {
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
  fetchRoles()
}

const handleFilter = (filter: any) => {
  Object.assign(filters, filter)
  filters.page = 1
  fetchRoles()
}

const applyAdvancedFilters = () => {
  Object.assign(filters, advancedFilters)
  filters.page = 1
  fetchRoles()
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

  fetchRoles()
}

const navigateToCreate = () => {
  router.push('/roles/create')
}

onMounted(() => {
  fetchRoles()
  fetchRolesOptions()
})
</script>