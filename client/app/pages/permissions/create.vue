<template>
  <div class="p-6">
    <SharedPageHeader :title="t('permissions.create')" :description="t('permissions.description_for_create')" />
    <div class="mt-6">
      <PermissionsForm />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })
const { t } = useLocalization()
</script>
