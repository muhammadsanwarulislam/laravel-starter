<template>
  <div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
      <slot name="icon">
        <UIIconsUsers v-if="icon === 'users'" class="h-16 w-16 text-gray-400" />
        <UIIconsSearch v-else-if="icon === 'search'" class="h-16 w-16 text-gray-400" />
        <UIIconsDocument v-else-if="icon === 'document'" class="h-16 w-16 text-gray-400" />
        <UIIconsLocalization v-else-if="icon === 'localization'" class="h-16 w-16 text-gray-400" />
        <UIIconsExclamation v-else class="h-16 w-16 text-gray-400" />
      </slot>
    </div>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ title }}</h3>
    <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>
    <div v-if="$slots.default" class="mt-6">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string
  description?: string
  icon?: 'users' | 'search' | 'document' | 'default' | 'localization'
}

withDefaults(defineProps<Props>(), {
  icon: 'default'
})
</script>