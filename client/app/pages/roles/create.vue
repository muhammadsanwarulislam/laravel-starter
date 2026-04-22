<template>
  <div class="p-6">
    <SharedPageHeader :title="t('roles.create')" :description="t('roles.description_for_create')" />
    <div class="mt-6">
      <RolesForm />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const { t } = useLocalization()
</script>
