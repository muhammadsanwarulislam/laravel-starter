<template>
  <div class="shrink-0">
    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
      <span class="text-blue-600 font-semibold">{{ initials }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  name: string
  size?: 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md'
})

const initials = computed(() => {
  return props.name
    .split(' ')
    .map(part => part[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
})
</script>