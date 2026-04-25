<template>
  <div class="p-6">
    <SharedPageHeader :title="t('permissions.title')" :description="t('permissions.description')">
      <template #actions>
        <UIButton variant="primary" @click="$router.push('/permissions/create')">
          <template #icon>
            <UIIconsPlus class="h-5 w-5" />
          </template>
          {{ t('common.button.create') }}
        </UIButton>
      </template>
    </SharedPageHeader>

    <GenericTable
      :columns="permissionColumns"
      :data="permissions"
      :loading="loading"
      :pagination="pagination"
      @update:sort="handleSort"
      @update:page="handlePageChange"
    >
      <template #column-module="{ item }">
        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
          {{ item.module }}
        </span>
      </template>

      <template #column-description="{ item }">
        <span class="text-sm text-gray-600">{{ item.description || 'No description' }}</span>
      </template>

      <template #actions="{ item }">
        <UIButton
          variant="secondary"
          size="xs"
          outlined
          title="Edit Permission"
          @click="editPermission(item.id)"
          class="hover:shadow-md mr-2"
        >
          <template #icon>
            <UIIconsPencil class="h-4 w-4" />
          </template>
          {{ t('common.button.edit') }}
        </UIButton>

        <UIButton
          variant="danger"
          size="xs"
          outlined
          @click="openDeleteModal(item.id)"
          title="Delete Permission"
          class="hover:shadow-md"
        >
          <template #icon>
            <UIIconsTrash class="h-4 w-4" />
          </template>
          {{ t('common.button.delete') }}
        </UIButton>
      </template>
    </GenericTable>

    <ModalConfirmationDialog
      v-if="showDeleteModal"
      title="Delete Permission"
      :message="deleteMessage"
      type="delete"
      @confirm="confirmDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useRolePermission } from '~/composables/rolePermission/useRolePermission'
import { formatDate } from '~/utils/date'

definePageMeta({ middleware: ['auth'] })

const router = useRouter()
const { permissions, loading, pagination, fetchPermissions, deletePermission } = useRolePermission()

const showDeleteModal = ref(false)
const deletePermissionId = ref<number | null>(null)
const deleteMessage = ref(
  'Are you sure you want to delete this permission? This action cannot be undone.'
)
const { t } = useLocalization()

const permissionColumns: Column[] = [
  { key: 'id', label: t('common.id'), sortable: true },
  { key: 'name', label: t('common.name'), sortable: true },
  { key: 'slug', label: t('common.slug'), sortable: true },
  { key: 'module', label: t('common.modules'), sortable: true },
  { key: 'description', label: t('common.description'), sortable: true },
  {
    key: 'created_at',
    label: t('common.created_at'),
    sortable: true,
    format: (val) => formatDate(val),
  },
]

const handleSort = (key: string, order: 'asc' | 'desc') => {
  fetchPermissions({ sort_field: key, sort_order: order, page: pagination.value?.currentPage || 1 })
}

const handlePageChange = (page: number) => {
  fetchPermissions({ page })
}

const editPermission = (id: number) => {
  router.push(`/permissions/${id}/edit`)
}

const openDeleteModal = (id: number) => {
  deletePermissionId.value = id
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  deletePermissionId.value = null
}

const confirmDelete = async () => {
  if (deletePermissionId.value) {
    const response = await deletePermission(deletePermissionId.value)
    if (response.success) {
      closeDeleteModal()
    }
  }
}

onMounted(() => fetchPermissions())
</script>
