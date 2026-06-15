<template>
  <div class="p-4 rounded-lg shadow">
    <PermissionsForm />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
const { t } = useLocalization()
</script>
