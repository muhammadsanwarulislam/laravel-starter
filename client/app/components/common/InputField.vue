<!-- components/common/InputField.vue -->
<template>
  <div :class="containerClass">
    <label class="block text-sm font-medium text-gray-700 mb-1">
      {{ field.label }}
      <span v-if="field.required" class="text-red-500">*</span>
    </label>
    
    <!-- Text Input -->
    <input
      v-if="field.type === 'text' || field.type === 'email' || field.type === 'tel' || field.type === 'password'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :type="field.type"
      :required="field.required"
      :placeholder="field.placeholder"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
    />
    
    <!-- Select Dropdown -->
    <select
      v-else-if="field.type === 'select'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :required="field.required"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <option value="" disabled selected>{{ field.defaultOption || `Select ${field.label}` }}</option>
      <option v-for="option in field.options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    
    <!-- Textarea -->
    <textarea
      v-else-if="field.type === 'textarea'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :required="field.required"
      :placeholder="field.placeholder"
      :rows="field.rows || 3"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
    ></textarea>
    
    <!-- Checkbox -->
    <div v-else-if="field.type === 'checkbox'" class="flex items-center">
      <input
        type="checkbox"
        :id="field.key"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
      />
      <label :for="field.key" class="ml-2 block text-sm text-gray-700">
        {{ field.label }}
      </label>
    </div>
    
    <!-- Field hint -->
    <p v-if="field.hint" class="text-xs text-gray-500 mt-1">{{ field.hint }}</p>
  </div>
</template>

<script setup lang="ts">
interface FormField {
  key: string;
  label: string;
  type: 'text' | 'email' | 'tel' | 'password' | 'select' | 'textarea' | 'checkbox';
  required?: boolean;
  placeholder?: string;
  options?: { value: any; label: string }[];
  rows?: number;
  hint?: string;
  translatable?: boolean;
  showOnEdit?: boolean;
  defaultOption?: string;
  layout?: 'full' | 'half' | 'third';
}

interface Props {
  field: FormField;
  modelValue: any;
  containerClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
  containerClass: ''
});

const emit = defineEmits<{
  'update:modelValue': [value: any];
}>();
</script>

<style scoped>
input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"],
select,
textarea {
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:focus,
select:focus,
textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

input[type="checkbox"] {
  transition: all 0.2s ease-in-out;
}

input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}
</style>