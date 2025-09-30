<template>
  <BaseLayout>
    <template #header>
      <CommonHeader />
    </template>

    <template #sidebar>
      <CommonSidebar />
    </template>

    <!-- Main content goes in default slot -->
    <div class="p-6 flex-1">
      <slot></slot>
    </div>

    <template #footer>
      <div class="flex justify-center sticky bottom-0 w-full">
        <DefaultFooter />
      </div>
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";
</script>