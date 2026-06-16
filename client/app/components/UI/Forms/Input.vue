<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-2">
      {{ label }}
    </label>
    <div class="relative">
      <div v-if="icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <component :is="icon" class="h-5 w-5 text-gray-400" />
      </div>
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        :placeholder="placeholder"
        :required="required"
        :class="[
          'block w-full py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
          icon ? 'pl-10' : 'pl-4',
          error ? 'border-red-300 bg-red-50' : 'border-gray-300',
        ]"
      />
      <slot name="right-icon" />
    </div>
    <p v-if="error" class="mt-2 text-xs text-red-600 flex items-center">
      <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
      {{ error }}
    </p>
    <p v-else-if="hint && !error" class="mt-2 text-xs text-gray-500">
      {{ hint }}
    </p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string | number
  label?: string
  type?: string
  placeholder?: string
  icon?: any   
  error?: string
  hint?: string
  required?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()
</script>