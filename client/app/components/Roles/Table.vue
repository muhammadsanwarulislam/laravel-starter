<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Roles List</h3>
          <p class="text-sm text-gray-500 mt-1">
            Total roles: {{ roles.length }}
          </p>
        </div>

        <slot name="bulk-actions" />
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center py-12">
      <UILoadingSpinner size="lg" />
    </div>

    <!-- Empty State -->
    <div v-else-if="roles.length === 0" class="text-center py-12">
      <UIEmptyState title="No roles found" description="Get started by creating a new role." icon="roles">
        <slot name="empty-state-actions" />
      </UIEmptyState>
    </div>

    <!-- Roles Table -->
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Description
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Permissions
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Created
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <RolesTableRow v-for="role in roles" :key="role.id" :role="role" @view="handleView" @edit="handleEdit"
            @delete="handleDelete" />
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!loading && roles.length > 0" class="px-6 py-4 border-t border-gray-200">
      <UIPagination :current-page="currentPage" :last-page="lastPage" :total="total" :from="from" :to="to"
        @page-change="$emit('page-change', $event)" />
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  roles: any[]
  loading?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  view: [role: any]
  edit: [role: any]
  delete: [role: any]
}>()

const handleView = (role: any) => emit('view', role)
const handleEdit = (role: any) => emit('edit', role)
const handleDelete = (role: any) => emit('delete', role)
</script>