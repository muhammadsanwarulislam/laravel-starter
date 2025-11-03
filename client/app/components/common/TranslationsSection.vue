<!-- components/common/TranslationsSection.vue -->
<template>
  <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Translations</h3>
    
    <div v-for="field in enabledFields" :key="field.key" class="mb-4">
      <h4 class="text-md font-medium text-gray-900 mb-2">{{ field.label }}</h4>
      
      <TranslationField
        v-for="language in languages"
        :key="`${field.key}-${language.code}`"
        :field="field"
        :language="language"
        :model-value="getTranslationValue(field.key, language.code)"
        @update:model-value="setTranslationValue(field.key, language.code, $event)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import TranslationField from './TranslationField.vue';

interface TranslatableField {
  key: string;
  label: string;
  enabled: boolean;
}

interface Language {
  code: string;
  name: string;
  required: boolean;
}

interface Props {
  enabledFields: TranslatableField[];
  languages: Language[];
  translations: Record<string, Record<string, string>>;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  'update:translations': [value: Record<string, Record<string, string>>];
}>();

// Helper function to get translation value safely
const getTranslationValue = (fieldKey: string, languageCode: string) => {
  // Initialize the structure if it doesn't exist
  if (!props.translations[fieldKey]) {
    props.translations[fieldKey] = {};
  }
  
  // Return the value or empty string if undefined
  return props.translations[fieldKey][languageCode] || '';
};

// Helper function to set translation value safely
const setTranslationValue = (fieldKey: string, languageCode: string, value: string) => {
  // Create a new translations object to maintain reactivity
  const newTranslations = { ...props.translations };
  
  // Initialize the structure if it doesn't exist
  if (!newTranslations[fieldKey]) {
    newTranslations[fieldKey] = {};
  }
  
  // Set the value
  newTranslations[fieldKey][languageCode] = value;
  
  // Emit the updated translations
  emit('update:translations', newTranslations);
};
</script>