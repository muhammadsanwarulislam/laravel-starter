<template>
  <div class="p-6">
    <SharedPageHeader title="Edit User" description="Update user details, access, and account status." />
    <div class="mt-6">
      <UsersForm :user-id="userId" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const userId = computed(() => Number(route.params.id))
</script>
