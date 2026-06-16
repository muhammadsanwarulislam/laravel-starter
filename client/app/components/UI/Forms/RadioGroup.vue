<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
    <div class="flex space-x-6">
      <label v-for="option in options" :key="option.value" class="flex items-center">
        <input
          type="radio"
          :value="option.value"
          :checked="modelValue === option.value"
          @change="emit('update:modelValue', option.value)"
          class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
        />
        <span class="ml-2 block text-sm text-gray-700">{{ option.label }}</span>
      </label>
    </div>
    <p v-if="error" class="mt-2 text-xs text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
interface RadioOption {
  value: any
  label: string
}

defineProps<{
  label: string
  modelValue: any
  options: RadioOption[]
  error?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()
</script>