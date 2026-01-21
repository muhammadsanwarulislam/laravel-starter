<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Users List</h3>
          <p class="text-sm text-gray-500 mt-1">
            Showing {{ from }} to {{ to }} of {{ total }} users
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
    <div v-else-if="users.length === 0" class="text-center py-12">
      <UIEmptyState title="No users" description="Get started by creating a new user." icon="users">
        <slot name="empty-state-actions" />
      </UIEmptyState>
    </div>
    <!-- Users Table -->
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3">
              <input v-model="allSelected" @change="$emit('select-all', allSelected)" type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              User
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Verified
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Created
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <UsersTableRow v-for="user in users" :key="user.id" :user="user" :selected="selectedUserIds.includes(user.id)"
            @select="$emit('select-user', user.id)" @view="$emit('view', user)" @edit="$emit('edit', user)"
            @delete="$emit('delete', user)" />
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!loading && users.length > 0" class="px-6 py-4 border-t border-gray-200">
      <UIPagination :current-page="currentPage" :last-page="lastPage" :total="total" :from="from" :to="to"
        @page-change="$emit('page-change', $event)" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  users: any[]
  selectedUserIds: number[]
  loading?: boolean
  currentPage: number
  lastPage: number
  total: number
  from: number
  to: number
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'select-all': [selected: boolean]
  'select-user': [userId: number]
  view: [user: any]
  edit: [user: any]
  delete: [user: any]
  'page-change': [page: number]
}>()

const allSelected = computed({
  get: () => props.selectedUserIds.length === props.users.length && props.users.length > 0,
  set: (value) => emit('select-all', value)
})
</script>