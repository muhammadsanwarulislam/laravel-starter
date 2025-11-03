<!-- components/common/TranslationField.vue -->
<template>
  <div class="mb-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">
      {{ field.label }} ({{ language.name }})
      <span v-if="language.required" class="text-red-500">*</span>
    </label>
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      type="text"
      :required="language.required"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      :placeholder="`Enter ${field.label.toLowerCase()} in ${language.name}`"
    />
  </div>
</template>

<script setup lang="ts">
interface FormField {
  key: string;
  label: string;
}

interface Language {
  code: string;
  name: string;
  required: boolean;
}

interface Props {
  field: FormField;
  language: Language;
  modelValue: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();
</script>

<style scoped>
input[type="text"] {
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>