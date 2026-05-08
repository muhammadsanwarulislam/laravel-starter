<template>
  <div class="relative">
    <div v-if="$slots.prefix" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <slot name="prefix" />
    </div>

    <select
      :id="name"
      :name="name"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      class="block w-full py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white appearance-none transition-all duration-200"
      :class="{
        'pl-10': $slots.prefix,
        'pr-10': $slots.suffix,
        'opacity-50 cursor-not-allowed': disabled,
        'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20': error,
        'border-gray-300 dark:border-gray-600': !error
      }"
      @change="handleChange"
      @blur="handleBlur"
    >
      <option v-if="placeholder" value="">{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>

    <div v-if="$slots.suffix" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
      <slot name="suffix" />
    </div>
    <!-- Default chevron if no suffix provided -->
    <div v-else class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
      <UIIconsChevronDown class="h-5 w-5 text-gray-400" />
    </div>
  </div>
</template>

<script setup lang="ts">
interface Option {
  value: string | number
  label: string
}

const props = defineProps<{
  modelValue: string | number | null
  name?: string
  options: Option[]
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void
  (e: 'blur', event: FocusEvent): void
}>()

const handleChange = (event: Event) => {
  const target = event.target as HTMLSelectElement
  const value = target.value === '' ? null : target.value
  emit('update:modelValue', value)
}

const handleBlur = (event: FocusEvent) => {
  emit('blur', event)
}
</script>