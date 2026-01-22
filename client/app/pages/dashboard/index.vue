<template>
  <div class="p-6">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600 mt-2">Welcome back, {{ auth.user?.value?.name || 'User' }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <Card title="Total Users" :value="stats.users" color="blue" />
      <Card title="Active Roles" :value="stats.roles" color="green" />
      <Card title="Permissions" :value="stats.permissions" color="purple" />
      <Card title="Languages" :value="stats.languages" color="yellow" />
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ["auth"] });
import Card from '~/components/UI/Card.vue'
import { useAuth } from '~/composables/auth/useAuth';

const auth = useAuth()
const api = useApi()

const stats = ref({
  users: 0,
  roles: 0,
  permissions: 0,
  languages: 0
})

onMounted(async () => {
  try {
    const [usersRes, rolesRes, permissionsRes, languagesRes] = await Promise.all([
      api.user.getUsers({ limit: 1 }),
      api.role.getRoles(),
      api.permission.getPermissions(),
      api.localization.getLanguages()
    ])

    stats.value = {
      users: usersRes.data?.length || 0,
      roles: rolesRes.data?.length || 0,
      permissions: permissionsRes.data?.length || 0,
      languages: Object.keys(languagesRes.data || {}).length || 0
    }
  } catch (error) {
    console.error('Failed to fetch stats:', error)
  }
})
</script>