<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
import { useAuth } from '~/composables/auth/useAuth'
import { useLocalization } from '~/composables/useLocalization'

const auth = useAuth()
const locale = useLocalization()

onMounted(() => {
  auth.initialize()
  locale.initialize()
})
</script>
