<template>
  <div class="p-4 rounded-lg shadow">
    <PermissionsForm :permission-id="permissionId" />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const permissionId = computed(() => Number(route.params.id))
const { t } = useLocalization()
</script>
