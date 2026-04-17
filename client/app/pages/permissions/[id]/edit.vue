<template>
  <div class="p-6">
    <SharedPageHeader title="Edit Permission" description="Update permission details and keep access rules consistent." />
    <div class="mt-6">
      <PermissionsForm :permission-id="permissionId" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const permissionId = computed(() => Number(route.params.id))
</script>
