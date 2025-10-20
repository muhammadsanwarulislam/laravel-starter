<!-- components/forms/LanguageForm.vue -->
<template>
  <form @submit.prevent="$emit('submit', formData)">
    <div class="space-y-4">
      <!-- Code Field -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
        <input
          v-model="formData.code"
          type="text"
          required
          maxlength="5"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          placeholder="en, fr, es, etc."
        />
        <p class="text-xs text-gray-500 mt-1">2-5 character language code (ISO 639)</p>
      </div>

      <!-- Name Field -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
        <input
          v-model="formData.name"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          placeholder="English, French, Spanish, etc."
        />
      </div>

      <!-- Native Name Field -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Native Name *</label>
        <input
          v-model="formData.native_name"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          placeholder="English, Français, Español, etc."
        />
      </div>

      <!-- Direction Field -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Direction *</label>
        <select
          v-model="formData.direction"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="ltr">Left to Right (LTR)</option>
          <option value="rtl">Right to Left (RTL)</option>
        </select>
      </div>

      <!-- Sort Order Field -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
        <input
          v-model="formData.sort_order"
          type="number"
          min="1"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />
        <p class="text-xs text-gray-500 mt-1">Determines display order in language lists</p>
      </div>

      <!-- Status Checkbox -->
      <div class="flex items-center space-x-2">
        <input
          v-model="formData.is_active"
          type="checkbox"
          id="is_active"
          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        >
        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
      </div>

      <!-- Default Checkbox -->
      <div class="flex items-center space-x-2">
        <input
          v-model="formData.is_default"
          type="checkbox"
          id="is_default"
          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
          :disabled="language?.is_default"
        >
        <label for="is_default" class="text-sm font-medium text-gray-700">
          Set as default language
          <span v-if="language?.is_default" class="text-orange-600 text-xs ml-1">
            (Current default)
          </span>
        </label>
      </div>
    </div>

    <div class="flex justify-end space-x-3 pt-6">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
      >
        Cancel
      </button>
      <button
        type="submit"
        :disabled="loading"
        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
      >
        {{ loading ? 'Saving...' : (language ? 'Update' : 'Create') }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
interface Language {
  id: number;
  code: string;
  name: string;
  native_name: string;
  direction: string;
  is_active: boolean;
  is_default: boolean;
  sort_order: number;
}

interface Props {
  language?: Language | null;
  loading?: boolean;
  totalLanguages?: number;
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  totalLanguages: 0
});

const emit = defineEmits<{
  submit: [formData: any];
  cancel: [];
}>();

const formData = reactive({
  code: '',
  name: '',
  native_name: '',
  direction: 'ltr',
  is_active: true,
  is_default: false,
  sort_order: props.totalLanguages + 1
});

// Reset form when language changes
watch(() => props.language, (newLanguage) => {
  if (newLanguage) {
    formData.code = newLanguage.code;
    formData.name = newLanguage.name;
    formData.native_name = newLanguage.native_name;
    formData.direction = newLanguage.direction;
    formData.is_active = newLanguage.is_active;
    formData.is_default = newLanguage.is_default;
    formData.sort_order = newLanguage.sort_order;
  } else {
    formData.code = '';
    formData.name = '';
    formData.native_name = '';
    formData.direction = 'ltr';
    formData.is_active = true;
    formData.is_default = false;
    formData.sort_order = props.totalLanguages + 1;
  }
});

// Update sort order when total languages changes
watch(() => props.totalLanguages, (newTotal) => {
  if (!props.language) {
    formData.sort_order = newTotal + 1;
  }
});
</script>