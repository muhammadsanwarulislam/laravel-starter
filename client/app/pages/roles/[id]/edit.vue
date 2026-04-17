<template>
  <div class="p-6">
    <SharedPageHeader title="Edit Role" description="Update role details and manage assigned permissions." />
    <div class="mt-6">
      <RolesForm :role-id="roleId" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const roleId = computed(() => Number(route.params.id))
</script>
