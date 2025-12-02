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
        <DefaultFooter />
    </template>
  </BaseLayout>
</template>

<script setup>
import BaseLayout from "./base.vue";
</script>