<template>
  <div class="mt-4 pt-4 border-t border-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Date Range -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Created Between</label>
        <div class="flex gap-2">
          <input 
            v-model="localFilters.startDate"
            type="date"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
          <input 
            v-model="localFilters.endDate"
            type="date"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      </div>

      <!-- Verified Status -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email Verified</label>
        <select 
          v-model="localFilters.verified"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        >
          <option :value="null">All</option>
          <option value="verified">Verified</option>
          <option value="not_verified">Not Verified</option>
        </select>
      </div>

      <!-- Sort By -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
        <select 
          v-model="localFilters.sortBy"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        >
          <option value="created_at:desc">Newest First</option>
          <option value="created_at:asc">Oldest First</option>
          <option value="name:asc">Name A-Z</option>
          <option value="name:desc">Name Z-A</option>
          <option value="updated_at:desc">Last Updated</option>
        </select>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-2 mt-4">
      <UIButton
        variant="secondary"
        @click="handleClear"
      >
        Clear Filters
      </UIButton>
      <UIButton
        variant="primary"
        @click="handleApply"
      >
        Apply Filters
      </UIButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'

interface Props {
  filters: {
    startDate: string | null
    endDate: string | null
    verified: string | null
    sortBy: string
  }
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:filters': [filters: any]
  apply: []
  clear: []
}>()

const localFilters = reactive({
  startDate: props.filters.startDate,
  endDate: props.filters.endDate,
  verified: props.filters.verified,
  sortBy: props.filters.sortBy
})

// Watch for changes and emit updates
watch(
  () => localFilters,
  (newFilters) => {
    emit('update:filters', newFilters)
  },
  { deep: true }
)

const handleApply = () => {
  emit('apply')
}

const handleClear = () => {
  localFilters.startDate = null
  localFilters.endDate = null
  localFilters.verified = null
  localFilters.sortBy = 'created_at:desc'
  emit('clear')
}
</script>