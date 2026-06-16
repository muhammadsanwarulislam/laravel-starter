<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-2">
      {{ label }}
    </label>
    <div class="relative">
      <select
        :id="id"
        :value="modelValue"
        @change="emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
        :required="required"
        :class="[
          'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200 appearance-none',
          error ? 'border-red-300 bg-red-50' : 'border-gray-300',
        ]"
      >
        <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <UIIconsChevronDown class="h-5 w-5 text-gray-400" />
      </div>
    </div>
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
interface SelectOption {
  value: any
  label: string
}

defineProps<{
  id: string
  modelValue: any
  options: SelectOption[]
  label?: string
  placeholder?: string
  error?: string
  hint?: string
  required?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any): void
}>()
</script>