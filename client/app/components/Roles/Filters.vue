<template>
  <div class="bg-white shadow rounded-lg mb-6">
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="lg:col-span-2">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <UIIconsSearch class="h-5 w-5 text-gray-400" />
            </div>
            <input 
              v-model="search"
              @input="$emit('search', search)"
              type="text" 
              placeholder="Search users by name, email, phone..." 
              class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
        </div>

        <!-- Status Filter -->
        <div>
          <select 
            v-model="status"
            @change="$emit('filter', { status })"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          >
            <option :value="null">All Status</option>
            <option :value="true">Active</option>
            <option :value="false">Inactive</option>
          </select>
        </div>

        <!-- Role Filter -->
        <div>
          <select 
            v-model="role"
            @change="$emit('filter', { role })"
            :disabled="loadingRoles"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100"
          >
            <option value="">Filter by Role</option>
            <option v-for="roleOption in roleOptions" :key="roleOption.id" :value="roleOption.slug">
              {{ roleOption.name }}
            </option>
          </select>
        </div>
      </div>

      <slot name="advanced-filters" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

interface Props {
  roleOptions: any[]
  loadingRoles?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  search: [value: string]
  filter: [filters: any]
}>()

const search = ref('')
const status = ref<boolean | null>(null)
const role = ref('')

let searchTimeout: NodeJS.Timeout

watch(search, (newVal) => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    emit('search', newVal)
  }, 500)
})

watch([status, role], () => {
  emit('filter', { status: status.value, role: role.value })
})
</script>