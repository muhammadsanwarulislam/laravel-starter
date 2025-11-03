<!-- components/common/TranslationSettings.vue -->
<template>
  <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Translation Settings</h3>
    
    <div class="space-y-3">
      <div v-for="field in fields" :key="field.key" class="flex items-center">
        <input
          type="checkbox"
          :id="`field-${field.key}`"
          v-model="field.enabled"
          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        />
        <label :for="`field-${field.key}`" class="ml-2 block text-sm text-gray-700">
          Enable translations for {{ field.label }}
        </label>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface TranslatableField {
  key: string;
  label: string;
  enabled: boolean;
}

interface Props {
  fields: TranslatableField[];
}

defineProps<Props>();
</script>