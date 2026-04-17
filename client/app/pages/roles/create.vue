<template>
  <div class="p-6">
    <SharedPageHeader title="Create Role" description="Add a new role and configure its permissions." />
    <div class="mt-6">
      <RolesForm />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
</script>
