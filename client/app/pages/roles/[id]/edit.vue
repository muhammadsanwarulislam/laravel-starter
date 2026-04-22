<template>
  <div class="p-6">
    <SharedPageHeader :title="t('roles.edit')" :description="t('roles.description_for_edit')" />
    <div class="mt-6">
      <RolesForm :role-id="roleId" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ['auth'] })

const route = useRoute()
const roleId = computed(() => Number(route.params.id))
const { t } = useLocalization()
</script>
