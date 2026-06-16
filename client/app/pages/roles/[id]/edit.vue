<template>
  <div class="p-4">
    <RolesForm :role-id="roleId" />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const roleId = computed(() => Number(route.params.id))
const { t } = useLocalization()
</script>
