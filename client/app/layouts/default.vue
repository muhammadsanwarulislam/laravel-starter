<template>
  <BaseLayout>
    <template #header>
      <LayoutHeader />
    </template>

    <template #sidebar>
      <LayoutSidebar />
    </template>

    <div class="p-6 flex-1">
      <slot></slot>
      
      <!-- Language Selector -->
      <LayoutLanguageSelector />
    </div>

    <template #footer>
      <LayoutFooter />
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";
</script>