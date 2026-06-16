<template>
  <div class="p-4">
    <RolesForm />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const { t } = useLocalization()
</script>
