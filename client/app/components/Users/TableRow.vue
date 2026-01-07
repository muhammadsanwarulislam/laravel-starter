<template>
  <tr :class="rowClasses">
    <!-- Select Checkbox -->
    <td class="px-6 py-4 whitespace-nowrap">
      <input :checked="selected" @change="$emit('select')" type="checkbox"
        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors cursor-pointer hover:border-blue-400" />
    </td>

    <!-- User Info -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <UIAvatar :name="user.name" :size="40" />
        <div class="ml-4">
          <div class="text-sm font-semibold text-gray-900">{{ user.name }}</div>
          <div class="text-sm text-gray-600">{{ user.email }}</div>
          <div v-if="user.phone" class="text-xs text-gray-500 mt-0.5 flex items-center">
            <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
            </svg>
            {{ user.phone }}
          </div>
        </div>
      </div>
    </td>

    <!-- Roles -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex flex-wrap gap-1.5">
        <UIBadge v-for="role in user.roles" :key="role.id" 
                 :variant="getRoleVariant(role.slug)"
                 :outlined="getRoleOutlined(role.slug)">
          {{ role.name }}
        </UIBadge>
        <span v-if="!user.roles?.length" class="text-sm text-gray-400 italic">No roles assigned</span>
      </div>
    </td>

    <!-- Status -->
    <td class="px-6 py-4 whitespace-nowrap">
      <UIBadge :variant="user.status ? 'success' : 'danger'" 
               :outlined="!user.status"
               class="font-medium">
        <template #icon>
          <svg v-if="user.status" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </template>
        {{ user.status ? 'Active' : 'Inactive' }}
      </UIBadge>
    </td>

    <!-- Verified -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <UIIconVerified :verified="!!user.email_verified_at" />
        <span class="ml-2 text-sm text-gray-600">
          {{ user.email_verified_at ? 'Verified' : 'Unverified' }}
        </span>
      </div>
    </td>

    <!-- Created Date -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="text-sm text-gray-700">{{ formatDate(user.created_at) }}</div>
      <div class="text-xs text-gray-500">{{ formatTime(user.created_at) }}</div>
    </td>

    <!-- Actions -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center gap-2">
        <UIButton 
          variant="secondary" 
          size="sm" 
          outlined 
          @click="$emit('view')" 
          title="View Details"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsEye class="h-4 w-4" />
          </template>
          View
        </UIButton>

        <UIButton 
          variant="warning" 
          size="sm" 
          outlined 
          @click="$emit('edit')" 
          title="Edit User"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsPencil class="h-4 w-4" />
          </template>
          Edit
        </UIButton>

        <UIButton 
          variant="danger" 
          size="sm" 
          outlined 
          @click="$emit('delete')" 
          title="Delete User"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsTrash class="h-4 w-4" />
          </template>
          Delete
        </UIButton>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  user: any
  selected?: boolean
  striped?: boolean
}

// Use destructuring to get props values
const { user, selected = false, striped = false } = defineProps<Props>()

defineEmits<{
  select: []
  view: []
  edit: []
  delete: []
}>()

const rowClasses = computed(() => {
  const base = 'transition-colors duration-150 border-b border-gray-100'
  const hover = 'hover:bg-gray-50/80'
  const selectedClass = selected ? 'bg-blue-50/50 hover:bg-blue-50' : ''
  const stripedClass = striped ? 'even:bg-gray-50/30' : ''
  
  return [base, hover, selectedClass, stripedClass].filter(Boolean).join(' ')
})

const getRoleVariant = (slug: string) => {
  const variants: Record<string, string> = {
    'admin': 'info',
    'super-admin': 'danger',
    'user': 'secondary',
    'moderator': 'success',
    'editor': 'warning',
    'manager': 'primary',
    'guest': 'light'
  }
  return variants[slug] || 'secondary'
}

const getRoleOutlined = (slug: string) => {
  // Only outline certain roles for better visual hierarchy
  const outlinedRoles = ['user', 'guest', 'editor']
  return outlinedRoles.includes(slug)
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatTime = (dateString: string) => {
  return new Date(dateString).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>