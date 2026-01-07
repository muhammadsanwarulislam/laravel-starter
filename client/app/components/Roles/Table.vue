<template>
  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Roles List</h3>
          <p class="text-sm text-gray-500 mt-1">
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
      <UIEmptyState
        title="No users"
        description="Get started by creating a new user."
        icon="users"
      >
        <slot name="empty-state-actions" />
      </UIEmptyState>
    </div>
    <!-- Users Table -->
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <RolesTableRow
            v-for="role in roles"
            :key="role.id"
            :role="role"
          />
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  roles: any[]
  loading?: boolean
}

const props = defineProps<Props>()
</script>