<template>
  <div class="p-6">
    <SharedPageHeader title="Create User" description="Add a new user and assign roles." />
    <div class="mt-6">
      <UsersForm />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
</script>
