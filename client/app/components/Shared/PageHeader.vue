<template>
  <div class="mb-4 flex flex-col gap-4">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="font-bold text-gray-900">{{ title }}</h1>
        <p
          v-if="description"
          class="font-xs text-yellow-600 hover:text-yellow-700">
          {{ description }}
        </p>
      </div>
      <div v-if="$slots.actions" class="flex gap-2">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string;
  description?: string;
}

defineProps<Props>();
</script>