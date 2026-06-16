<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 max-h-48 overflow-y-auto p-2 border border-gray-200 rounded-md">
      <label v-for="option in options" :key="option.value" class="flex items-start">
        <input
          type="checkbox"
          :value="option.value"
          :checked="modelValue.includes(option.value)"
          @change="toggle(option.value)"
          class="mt-1 h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
        />
        <div class="ml-2">
          <span class="text-sm font-medium text-gray-700">{{ option.label }}</span>
          <p v-if="option.description" class="text-xs text-gray-500">{{ option.description }}</p>
        </div>
      </label>
    </div>
    <p v-if="error" class="mt-2 text-xs text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
interface Option {
  value: any
  label: string
  description?: string
}

const props = defineProps<{
  label: string
  modelValue: any[]
  options: Option[]
  error?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: any[]): void
}>()

const toggle = (value: any) => {
  const newValue = props.modelValue.includes(value)
    ? props.modelValue.filter(v => v !== value)
    : [...props.modelValue, value]
  emit('update:modelValue', newValue)
}
</script>