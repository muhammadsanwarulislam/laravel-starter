<template>
  <tr class="hover:bg-gray-50 transition-colors">
    <!-- Role Info -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <UIAvatar :name="role.name" :variant="getRoleVariant(role.slug)" />
        <div class="ml-4">
          <div class="text-sm font-medium text-gray-900">{{ role.name }}</div>
          <div class="text-xs text-gray-500 mt-0.5 flex items-center">
            <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
            </svg>
            {{ role.slug }}
          </div>
        </div>
      </div>
    </td>

    <!-- Description -->
    <td class="px-6 py-4">
      <div class="text-sm text-gray-900">{{ role.description || 'No description' }}</div>
    </td>

    <!-- Permissions -->
    <td class="px-6 py-4">
      <div class="flex flex-wrap gap-1">
        <span v-if="role.permissions?.length > 0"
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          {{ role.permissions.length }} permissions
        </span>
        <span v-else class="text-sm text-gray-500">
          No permissions
        </span>
      </div>
    </td>

    <!-- Created Date -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
      {{ role.created_at ? formatDate(role.created_at) : 'N/A' }}
    </td>

    <!-- Actions -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center gap-2">
        <UIButton variant="secondary" size="sm" outlined @click="$emit('view', role)" title="View Details"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsEye class="h-4 w-4" />
          </template>
          View
        </UIButton>

        <UIButton variant="warning" size="sm" outlined @click="$emit('edit', role)" title="Edit User"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsPencil class="h-4 w-4" />
          </template>
          Edit
        </UIButton>

        <UIButton variant="danger" size="sm" outlined @click="$emit('delete', role)" title="Delete User"
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
interface Props {
  role: any
}

defineProps<Props>()

const emit = defineEmits<{
  view: [role: any]
  edit: [role: any]
  delete: [role: any]
}>()

const getRoleVariant = (slug: string): string => {
  const variants: Record<string, string> = {
    'admin': 'info',
    'super-admin': 'danger',
    'user': 'gray',
    'moderator': 'success',
    'editor': 'warning'
  }
  return variants[slug] || 'gray'
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>