<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-2">
      {{ label }}
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
      :rows="rows"
      :placeholder="placeholder"
      :required="required"
      :class="[
        'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
        error ? 'border-red-300 bg-red-50' : 'border-gray-300',
      ]"
    />
    <p v-if="error" class="mt-2 text-xs text-red-600 flex items-center">
      <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
      {{ error }}
    </p>
    <p v-else-if="hint" class="mt-2 text-xs text-gray-500">
      {{ hint }}
    </p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string
  label?: string
  rows?: number
  placeholder?: string
  error?: string
  hint?: string
  required?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()
</script>