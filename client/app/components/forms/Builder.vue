<template>
  <form @submit.prevent="submitForm">
    <div class="space-y-4">
      <!-- Field Configuration Section (for translatable fields) -->
      <div v-if="hasTranslatableFields" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Translation Settings</h3>
        
        <div class="space-y-3">
          <div v-for="field in translatableFields" :key="field.key" class="flex items-center">
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

      <!-- Translatable Fields Section -->
      <div v-if="hasEnabledTranslations" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Translations</h3>
        
        <div v-for="field in enabledTranslatableFields" :key="field.key" class="mb-4">
          <h4 class="text-md font-medium text-gray-900 mb-2">{{ field.label }}</h4>
          
          <div v-for="language in languages" :key="`${field.key}-${language.code}`" class="mb-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ field.label }} ({{ language.name }})
              <span v-if="language.required" class="text-red-500">*</span>
            </label>
            <input
              :value="getTranslationValue(field.key, language.code)"
              @input="setTranslationValue(field.key, language.code, $event.target.value)"
              type="text"
              :required="language.required"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :placeholder="`Enter ${field.label.toLowerCase()} in ${language.name}`"
            />
          </div>
        </div>
      </div>

      <!-- Non-Translatable Fields Section -->
      <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ title }}</h3>
        
        <!-- Dynamic field rendering -->
        <div v-for="field in visibleNonTranslatableFields" :key="field.key" class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ field.label }}
            <span v-if="field.required" class="text-red-500">*</span>
          </label>
          
          <!-- Text Input -->
          <input
            v-if="field.type === 'text' || field.type === 'email' || field.type === 'tel' || field.type === 'password'"
            v-model="formData[field.key]"
            :type="field.type"
            :required="field.required"
            :placeholder="field.placeholder"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          
          <!-- Select Dropdown -->
          <select
            v-else-if="field.type === 'select'"
            v-model="formData[field.key]"
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
            v-model="formData[field.key]"
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
              v-model="formData[field.key]"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label :for="field.key" class="ml-2 block text-sm text-gray-700">
              {{ field.label }}
            </label>
          </div>
          
          <!-- Field hint -->
          <p v-if="field.hint" class="text-xs text-gray-500 mt-1">{{ field.hint }}</p>
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-3 mt-6">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
      >
        Cancel
      </button>
      <button
        type="submit"
        :disabled="loading"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
      >
        {{ loading ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
      </button>
    </div>
  </form>
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
}

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
  fields: FormField[];
  title: string;
  initialData?: Record<string, any>;
  loading?: boolean;
  isEdit?: boolean;
  languages?: Language[];
}

const props = withDefaults(defineProps<Props>(), {
  initialData: () => ({}),
  loading: false,
  isEdit: false,
  languages: () => [
    { code: 'en', name: 'English', required: true },
    { code: 'bn', name: 'Bangla', required: false }
  ]
});

const emit = defineEmits<{
  submit: [formData: any];
  cancel: [];
}>();

// Separate translatable and non-translatable fields
const translatableFields = ref<TranslatableField[]>([]);
const nonTranslatableFields = ref<FormField[]>([]);

// Initialize form data with proper structure
const formData = reactive({
  translations: {} as Record<string, Record<string, string>>
});

// Helper function to get translation value safely
const getTranslationValue = (fieldKey: string, languageCode: string) => {
  // Initialize the structure if it doesn't exist
  if (!formData.translations[fieldKey]) {
    formData.translations[fieldKey] = {};
  }
  
  // Return the value or empty string if undefined
  return formData.translations[fieldKey][languageCode] || '';
};

// Helper function to set translation value safely
const setTranslationValue = (fieldKey: string, languageCode: string, value: string) => {
  // Initialize the structure if it doesn't exist
  if (!formData.translations[fieldKey]) {
    formData.translations[fieldKey] = {};
  }
  
  // Set the value
  formData.translations[fieldKey][languageCode] = value;
};

// Initialize fields
onMounted(() => {
  // Initialize non-translatable fields in formData
  props.fields.forEach(field => {
    if (!field.translatable) {
      // Initialize with empty string if not already present
      if (formData[field.key] === undefined) {
        // For select fields, initialize with empty string to show default option
        formData[field.key] = field.type === 'select' ? '' : '';
      }
    }
  });
  
  // Separate fields
  props.fields.forEach(field => {
    if (field.translatable) {
      translatableFields.value.push({
        key: field.key,
        label: field.label,
        enabled: true
      });
      
      // Initialize translation structure for this field
      if (!formData.translations[field.key]) {
        formData.translations[field.key] = {};
      }
      
      // Initialize each language for this field
      props.languages.forEach(lang => {
        if (formData.translations[field.key][lang.code] === undefined) {
          formData.translations[field.key][lang.code] = '';
        }
      });
    } else {
      nonTranslatableFields.value.push(field);
    }
  });
  
  // If we have initial data, populate the form
  if (Object.keys(props.initialData).length > 0) {
    populateFormData(props.initialData);
  }
});

// Function to populate form data
const populateFormData = (data: Record<string, any>) => {
  // Populate non-translatable fields
  nonTranslatableFields.value.forEach(field => {
    if (data[field.key] !== undefined) {
      formData[field.key] = data[field.key];
    }
  });
  
  // Populate translatable fields
  if (data.translations) {
    translatableFields.value.forEach(field => {
      if (data.translations[field.key]) {
        props.languages.forEach(lang => {
          if (data.translations[field.key][lang.code] !== undefined) {
            setTranslationValue(field.key, lang.code, data.translations[field.key][lang.code]);
          }
        });
      }
    });
  }
};

// Computed properties
const hasTranslatableFields = computed(() => translatableFields.value.length > 0);
const hasEnabledTranslations = computed(() => {
  return translatableFields.value.some(field => field.enabled);
});
const enabledTranslatableFields = computed(() => {
  return translatableFields.value.filter(field => field.enabled);
});

// Filter fields based on edit mode
const visibleNonTranslatableFields = computed(() => {
  return nonTranslatableFields.value.filter(field => {
    // Always show in create mode
    if (!props.isEdit) return true;
    
    // In edit mode, only show if showOnEdit is not explicitly false
    return field.showOnEdit !== false;
  });
});

// Watch for initial data changes
watch(() => props.initialData, (newData) => {
  if (Object.keys(newData).length > 0) {
    populateFormData(newData);
  }
}, { immediate: true, deep: true });

// Submit form
const submitForm = () => {
  const payload: any = {};
  
  // Add non-translatable fields
  visibleNonTranslatableFields.value.forEach(field => {
    if (formData[field.key] !== undefined && formData[field.key] !== '') {
      payload[field.key] = formData[field.key];
    }
  });
  
  // Add translations if enabled
  if (hasEnabledTranslations.value) {
    payload.translations = {};
    translatableFields.value.forEach(field => {
      if (field.enabled) {
        payload.translations[field.key] = {};
        props.languages.forEach(lang => {
          const value = getTranslationValue(field.key, lang.code);
          if (value) {
            payload.translations[field.key][lang.code] = value;
          }
        });
        
        // Also add the default language value as the main field
        if (payload.translations[field.key].en) {
          payload[field.key] = payload.translations[field.key].en;
        }
      }
    });
  }
  
  emit('submit', payload);
};
</script>

<style scoped>
/* Custom styles for better form appearance */
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

button {
  transition: all 0.2s ease-in-out;
}

button:disabled {
  cursor: not-allowed;
}

/* Checkbox styling */
input[type="checkbox"] {
  transition: all 0.2s ease-in-out;
}

input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}
</style>