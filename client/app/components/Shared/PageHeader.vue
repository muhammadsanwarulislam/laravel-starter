<template>
  <div class="mb-8">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ title }}</h1>
        <p v-if="description" class="text-gray-600 mt-2">{{ description }}</p>
      </div>
      <div v-if="$slots.actions" class="flex gap-3">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string
  description?: string
}

defineProps<Props>()
</script>