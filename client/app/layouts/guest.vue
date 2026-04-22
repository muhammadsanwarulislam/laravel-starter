<template>
  <BaseLayout>
    <template #header>
      <LayoutNavBar />
    </template>

    <!-- Main content -->
    <main class="flex-1">
      <slot />
      <UILoadingSpinner v-if="isLoading" />
      <!-- Language Selector -->
      <LayoutLanguageSelector />
    </main>

    <template #footer>
      <LayoutFooter />
    </template>

  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";

const isLoading = ref(true)

onNuxtReady(() => {
  isLoading.value = false
})
</script>