<template>
  <BaseLayout ref="baseLayoutRef">
    <UILoadingSpinner v-if="isLoading" message="Loading"/>

    <template #header>
      <LayoutHeader @toggle-sidebar="toggleSidebarFromHeader" />
    </template>

    <template #sidebar="{ mobileOpen, closeMobile }">
      <LayoutSidebar 
        :mobile-open="mobileOpen" 
        @close-mobile="closeMobile"
      />
    </template>

    <div class="flex-1 flex flex-col min-h-full">
      <div class="flex flex-col flex-1 px-6 py-4 lg:px-8 gap-4 md:gap-6 sm:gap-8 rounded-2xl">
        <slot></slot>
      </div>
      <LayoutLanguageSelector />
    </div>

    <template #footer>
      <LayoutFooter />
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";

const isLoading = ref(true)

const baseLayoutRef = ref(null)

const toggleSidebarFromHeader = () => {
  if (baseLayoutRef.value) {
    baseLayoutRef.value.toggleDrawer()
  }
}

onNuxtReady(() => {
  isLoading.value = false
})
</script>