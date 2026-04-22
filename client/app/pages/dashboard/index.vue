<template>
  <div class="p-6">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">{{ t("common.dashboard") }}</h1>
      <p class="text-gray-600 mt-2">
        {{ t("common.welcome") }}, {{ auth.user?.value?.name || "User" }}!
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <Card :title="t('common.users')" :value="stats.users" color="blue" />
      <Card :title="t('common.roles')" :value="stats.roles" color="green" />
      <Card :title="t('common.permissions')" :value="stats.permissions" color="purple" />
      <Card :title="t('common.languages')" :value="stats.languages" color="yellow" />
      <Card :title="t('common.active_languages')" :value="activeLanguagesCount" color="green" />
      <Card :title="t('common.ltr_languages')" :value="ltrLanguagesCount" color="purple" />
      <Card :title="t('common.rtl_languages')" :value="rtlLanguagesCount" color="yellow" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, watch, ref } from 'vue'
import { useLocalization } from '~/composables/useLocalization'
import { useAuth } from "~/composables/auth/useAuth"
import Card from "~/components/UI/Card.vue"

definePageMeta({ middleware: ["auth"] })

const { allLanguages} = useLocalization()
const { t } = useLocalization();

const auth = useAuth()
const api = useApi()

const stats = ref({
  users: 0,
  roles: 0,
  permissions: 0,
  languages: 0,
})

const activeLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.is_active).length)
const ltrLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.direction === 'ltr').length)
const rtlLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.direction === 'rtl').length)

onMounted(async () => {
  try {
    const [usersRes, rolesRes, permissionsRes, languagesRes] =
      await Promise.all([
        api.user.getUsers({ limit: 1 }),
        api.role.getRoles(),
        api.permission.getPermissions(),
        api.localization.getLanguages(),
      ]);

    stats.value = {
      users: usersRes.data?.length || 0,
      roles: rolesRes.pagination?.total || rolesRes.data?.length || 0,
      permissions: permissionsRes.data?.length || 0,
      languages: Object.keys(languagesRes.data || {}).length || 0,
    };
  } catch (error) {
    console.error("Failed to fetch stats:", error)
  }
});
</script>
