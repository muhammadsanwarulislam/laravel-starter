<template>
  <BaseLayout>
    <template #header>
      <LayoutNavBar />
    </template>

    <!-- Main content -->
    <main class="flex-1">
      <slot />

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
</script>