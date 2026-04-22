<template>
  <div class="p-6">
    <SharedPageHeader :title="t('permissions.edit')" :description="t('permissions.description_for_edit')" />
    <div class="mt-6">
      <PermissionsForm :permission-id="permissionId" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const permissionId = computed(() => Number(route.params.id))
const { t } = useLocalization()
</script>
