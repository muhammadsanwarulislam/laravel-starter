<template>
  <div class="relative">
    <!-- Prefix slot (icon left) -->
    <div
      v-if="$slots.prefix"
      class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
    >
      <slot name="prefix" />
    </div>

    <input
      :id="name"
      :name="name"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :disabled="disabled"
      class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
      :class="{
        'pl-10': $slots.prefix,
        'pr-10': $slots.suffix,
        'opacity-50 cursor-not-allowed': disabled,
        'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20':
          error,
        'border-gray-300 dark:border-gray-600': !error,
      }"
      @input="handleInput"
      @blur="handleBlur"
    />

    <!-- Suffix slot (text or icon right) -->
    <div
      v-if="$slots.suffix"
      class="absolute inset-y-0 right-0 pr-3 flex items-center"
    >
      <slot name="suffix" />
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  modelValue: string;
  name?: string;
  type?: string;
  placeholder?: string;
  required?: boolean;
  autocomplete?: string;
  disabled?: boolean;
  error?: boolean;
}>();

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
  (e: "input", event: Event): void;
  (e: "blur", event: FocusEvent): void;
}>();

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emit("update:modelValue", target.value);
  emit("input", event);
};

const handleBlur = (event: FocusEvent) => {
  emit("blur", event);
};
</script>
