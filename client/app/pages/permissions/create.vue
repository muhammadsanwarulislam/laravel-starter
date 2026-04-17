<template>
  <div class="p-6">
    <SharedPageHeader title="Create Permission" description="Add a new permission and assign it to the right module." />
    <div class="mt-6">
      <PermissionsForm />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
</script>
