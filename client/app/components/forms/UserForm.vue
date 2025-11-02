<template>
  <form @submit.prevent="submitForm">
    <div class="space-y-4">
      <!-- Field Configuration Section -->
      <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
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
          
          <div v-for="language in languages" :key="language.code" class="mb-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ field.label }} ({{ language.name }})
              <span v-if="language.required" class="text-red-500">*</span>
            </label>
            <input
              v-model="formData.translations[field.key][language.code]"
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
        <h3 class="text-lg font-medium text-gray-900 mb-3">User Information</h3>
        
        <!-- Name (when translations are disabled) -->
        <div v-if="!hasEnabledTranslations" class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Name <span class="text-red-500">*</span>
          </label>
          <input
            v-model="formData.name"
            type="text"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter name"
          />
        </div>
        
        <!-- Email -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            v-model="formData.email"
            type="email"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter email address"
          />
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Phone <span class="text-red-500">*</span>
          </label>
          <input
            v-model="formData.phone"
            type="tel"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter phone number"
          />
        </div>

        <!-- Password (only show for new users) -->
        <div v-if="!user" class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Password <span class="text-red-500">*</span>
          </label>
          <input
            v-model="formData.password"
            type="password"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter password"
          />
          <p class="text-xs text-gray-500 mt-1">
            Password must be at least 8 characters long
          </p>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select
            v-model="formData.status"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option :value="true">Active</option>
            <option :value="false">Inactive</option>
          </select>
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
        {{ loading ? 'Saving...' : (user ? 'Update' : 'Create') }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
interface User {
  id: number;
  name: string;
  email: string;
  phone: string;
  status: boolean;
  translations?: {
    name: {
      en: string;
      bn: string;
    };
  };
}

interface Props {
  user?: User | null;
  loading?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  submit: [formData: any];
  cancel: [];
}>();

// Define translatable fields configuration
const translatableFields = ref([
  { key: 'name', label: 'Name', enabled: true }
]);

// Define supported languages
const languages = [
  { code: 'en', name: 'English', required: true },
  { code: 'bn', name: 'Bangla', required: false }
];

// Computed property to check if any translations are enabled
const hasEnabledTranslations = computed(() => {
  return translatableFields.value.some(field => field.enabled);
});

// Computed property to get only enabled translatable fields
const enabledTranslatableFields = computed(() => {
  return translatableFields.value.filter(field => field.enabled);
});

// Initialize form data with proper structure
const formData = reactive({
  name: '',
  translations: {} as Record<string, Record<string, string>>,
  email: '',
  phone: '',
  password: '',
  status: true
});

// Initialize translations structure
translatableFields.value.forEach(field => {
  formData.translations[field.key] = {};
  languages.forEach(lang => {
    formData.translations[field.key][lang.code] = '';
  });
});

// Reset form when user changes (for editing)
watch(() => props.user, (newUser) => {
  if (newUser) {
    // Populate non-translatable fields
    formData.name = newUser.name;
    formData.email = newUser.email;
    formData.phone = newUser.phone;
    formData.password = ''; 
    formData.status = newUser.status;
    
    // Populate translatable fields
    if (newUser.translations) {
      translatableFields.value.forEach(field => {
        if (newUser.translations?.[field.key]) {
          languages.forEach(lang => {
            formData.translations[field.key][lang.code] = newUser.translations[field.key][lang.code] || '';
          });
        }
      });
    }
  } else {
    // Reset form for new user creation
    formData.name = '';
    formData.email = '';
    formData.phone = '';
    formData.password = '';
    formData.status = true;
    
    // Reset translations
    translatableFields.value.forEach(field => {
      languages.forEach(lang => {
        formData.translations[field.key][lang.code] = '';
      });
    });
  }
}, { immediate: true });

// Submit form with proper data structure
const submitForm = () => {
  const payload: any = {
    email: formData.email,
    phone: formData.phone,
    status: formData.status
  };
  
  // Add name field
  if (hasEnabledTranslations.value && formData.translations.name?.en) {
    payload.name = formData.translations.name.en;
  } else {
    payload.name = formData.name;
  }
  
  // Add translations if enabled
  if (hasEnabledTranslations.value) {
    payload.translations = {};
    translatableFields.value.forEach(field => {
      if (field.enabled) {
        payload.translations[field.key] = {};
        languages.forEach(lang => {
          if (formData.translations[field.key][lang.code]) {
            payload.translations[field.key][lang.code] = formData.translations[field.key][lang.code];
          }
        });
      }
    });
  }
  
  // Add password only for new users or if provided
  if (!props.user || formData.password) {
    payload.password = formData.password;
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
select {
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:focus,
select:focus {
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