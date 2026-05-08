<template>
  <aside class="space-y-6">
    <!-- User card -->
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center gap-4">
        <div
          class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xl font-semibold text-white"
        >
          <img v-if="user?.avatar_url" :src="user.avatar_url" alt="Profile" class="h-full w-full object-cover" />
          <span v-else>{{ initials }}</span>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">{{ user?.name || 'User' }}</h3>
          <p class="text-sm text-gray-500">{{ user?.email || 'No email' }}</p>
        </div>
      </div>
      <div class="mt-4 space-y-3 text-sm text-gray-600">
        <div class="flex items-center justify-between">
          <span>{{ t('common.roles') }}</span>
          <span class="font-medium text-gray-900">{{ roleNames }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span>{{ t('common.permissions') }}</span>
          <span class="font-medium text-gray-900">{{ permissions.length }}</span>
        </div>
      </div>
    </section>

    <!-- Account summary -->
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
      <h3 class="text-sm font-semibold text-gray-900">{{ t('profile.account_summary') }}</h3>
      <dl class="mt-4 space-y-3 text-sm">
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500">{{ t('common.status') }}</dt>
          <dd class="font-medium text-gray-900">{{ user?.status ? 'Active' : 'Inactive' }}</dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500">{{ t('common.created_at') }}</dt>
          <dd class="font-medium text-gray-900">{{ formatDate(user?.created_at) }}</dd>
        </div>
        <div class="flex justify-between gap-4">
          <dt class="text-gray-500">{{ t('common.updated_at') }}</dt>
          <dd class="font-medium text-gray-900">{{ formatDate(user?.updated_at) }}</dd>
        </div>
      </dl>
    </section>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  user: any
  permissions: string[]
}>()

const { t } = useLocalization()

const initials = computed(() => {
  const name = props.user?.name || 'User'
  return name
    .split(' ')
    .map((p: string) => p[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const roleNames = computed(() =>
  props.user?.roles?.map((role: any) => role.name).join(', ') || 'No roles'
)

const formatDate = (dateString?: string) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}
</script>