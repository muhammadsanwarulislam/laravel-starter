<template>
  <tr class="hover:bg-gray-50 transition-colors">
    <!-- Role Info -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center">
        <UIAvatar :name="role.name" />
        <div class="ml-4">
          <div class="text-sm font-medium text-gray-900">{{ role.name }}</div>
        </div>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
interface Props {
  role: any
}

defineProps<Props>()

defineEmits<{
  select: []
  view: []
  edit: []
  delete: []
}>()

const getRoleVariant = (slug: string) => {
  const variants: Record<string, any> = {
    'admin': 'info',
    'super-admin': 'danger',
    'user': 'gray',
    'moderator': 'success',
    'editor': 'warning'
  }
  return variants[slug] || 'gray'
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>