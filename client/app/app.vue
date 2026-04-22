<template>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
</template>

<script setup lang="ts">
import { useAuth } from '~/composables/auth/useAuth'

const auth = useAuth()
const locale = useLocalization()

onMounted(() => {
  auth.initialize()
  locale.initialize()
})
</script>
