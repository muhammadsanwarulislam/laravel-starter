<template>
    <div>
        <h1 class="text-2xl font-semibold leading-tight dark:text-gray-300">{{ t('page_title') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ t('page_description') }}</p>

        <!-- Language Selector -->
        <div class="mt-6">
            <label for="language-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('select_language') }}
            </label>
            <select 
                id="language-select"
                v-model="selectedLocale" 
                @change="changeLanguage"
                class="block w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="en">English</option>
                <option value="bn">Bengali</option>
                <option value="hi">Hindi</option>
                <option value="ar">Arabic</option>
                <option value="fa">Persian</option>
            </select>
        </div>

        <!-- Actions Bar -->
        <div class="mt-6 flex flex-wrap gap-4 items-center justify-between">
            <div class="flex flex-wrap gap-2">
                <button 
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ t('add') }}
                </button>
                <button 
                    @click="openBulkCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ t('bulk_add') }}
                </button>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <button 
                    @click="copyTranslations"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    {{ t('copy_json') }}
                </button>
                <button 
                    @click="downloadTranslations"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ t('download_json') }}
                </button>
            </div>
        </div>

        <!-- Translations Content -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ t('current_translations') }}</h2>
            
            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ t('loading') }}</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">{{ t('error') }}</h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Success State -->
            <div v-else-if="translations" class="space-y-6">
                <!-- Translation Keys Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ t('translation_keys') }}</h3>
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md overflow-hidden">
                            <div class="max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ t('key') }}
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ t('value') }}
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ t('actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                        <tr v-for="(value, key) in translations" :key="key" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ key }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ value }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button 
                                                        @click="editTranslation(key)"
                                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                        :title="t('edit_translation')"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button 
                                                        @click="deleteTranslation(key)"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                        :title="t('delete_translation')"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Language Info & Usage -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ t('language_info') }}</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ t('current_locale') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-white bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded text-xs">
                                        {{ currentLocale }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ t('language_name') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ getLanguageName(currentLocale) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ t('translation_count') }}:</span>
                                    <span class="font-medium text-gray-900 dark:text-white bg-green-100 dark:bg-green-900 px-2 py-1 rounded text-xs">
                                        {{ Object.keys(translations).length }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Sample Usage -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-3">{{ t('sample_usage') }}</h4>
                            <div class="space-y-3 text-sm">
                                <div class="bg-white dark:bg-gray-800 rounded p-3 border border-blue-200 dark:border-blue-700">
                                    <p class="text-blue-700 dark:text-blue-400 font-mono text-xs">
                                        &lt;span&gt;&#123;&#123; t('welcome') &#125;&#125;&lt;/span&gt;
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-1">
                                        → "{{ t('welcome') }}"
                                    </p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded p-3 border border-blue-200 dark:border-blue-700">
                                    <p class="text-blue-700 dark:text-blue-400 font-mono text-xs">
                                        &lt;button&gt;&#123;&#123; t('submit') &#125;&#125;&lt;/button&gt;
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-1">
                                        → "{{ t('submit') }}"
                                    </p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded p-3 border border-blue-200 dark:border-blue-700">
                                    <p class="text-blue-700 dark:text-blue-400 font-mono text-xs">
                                        &lt;h1&gt;&#123;&#123; t('dashboard') &#125;&#125;&lt;/h1&gt;
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-1">
                                        → "{{ t('dashboard') }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-4">📝</div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ t('no_translations') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ t('select_language_prompt') }}</p>
            </div>
        </div>

        <!-- Create/Edit Translation Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ isEditing ? t('edit_translation') : t('add_translation') }}
                    </h2>
                    
                    <form @submit.prevent="submitTranslation" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ t('translation_key') }} *
                            </label>
                            <input
                                v-model="form.key"
                                type="text"
                                required
                                :disabled="isEditing"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="e.g., welcome_message"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ t('key_hint') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ t('translation_value') }} *
                            </label>
                            <textarea
                                v-model="form.value"
                                required
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :placeholder="t('value_placeholder')"
                            ></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600"
                            >
                                {{ t('cancel') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ isSubmitting ? t('saving') : (isEditing ? t('update') : t('create')) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Create Modal -->
        <div v-if="showBulkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ t('bulk_add_translations') }}
                    </h2>
                    
                    <form @submit.prevent="submitBulkTranslations" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ t('json_data') }} *
                            </label>
                            <textarea
                                v-model="bulkForm.jsonData"
                                required
                                rows="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono text-sm"
                                :placeholder="t('json_placeholder')"
                            ></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ t('json_hint') }}
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeBulkModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600"
                            >
                                {{ t('cancel') }}
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {{ isSubmitting ? t('saving') : t('import') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useNotification } from '~/composables/useNotification';
import { useLocale } from '~/composables/useLocale';

const { locale, t } = useLocale();

const isLoading = ref(false);
const error = ref(null);
const translations = ref(null);
const selectedLocale = ref('en');
const currentLocale = ref('en');

// Modal states
const showModal = ref(false);
const showBulkModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);
const editingKey = ref('');

// Form data
const form = ref({
    key: '',
    value: ''
});

const bulkForm = ref({
    jsonData: ''
});

const { add: notify } = useNotification()

// Computed properties for dynamic page content
const pageTitle = computed(() => t('current_translations'));
const pageDescription = computed(() => `Managing translations for ${getLanguageName(currentLocale.value)} language`);

// Methods
const getLanguageName = (code) => {
    const languages = {
        en: 'English',
        bn: 'Bengali',
        hi: 'Hindi',
        ar: 'Arabic',
        fa: 'Persian'
    };
    return languages[code] || code;
};

const getCookie = (name) => {
    if (process.client) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    return null;
};

const setCookie = (name, value, days = 365) => {
    if (process.client) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value}; ${expires}; path=/`;
    }
};

const loadTranslations = async (locale = null) => {
    const targetLocale = locale || currentLocale.value;
    
    isLoading.value = true;
    error.value = null;

    try {
        const res = await $http(`translations/${targetLocale}`, { 
            method: 'GET' 
        });
        
        if (res.data && res.data.data) {
            translations.value = res.data.data;
        } else {
            translations.value = {};
        }
        
        // Update current locale
        currentLocale.value = targetLocale;
        selectedLocale.value = targetLocale;
        
        // Save to cookie
        setCookie('locale', targetLocale);
        
    } catch (err) {
        error.value = err.data?.message || err.message || 'Failed to load translations';
        notify(error.value, 'error');
    } finally {
        isLoading.value = false;
    }
};

const changeLanguage = async () => {
    await loadTranslations(selectedLocale.value);
};

// Creation functionality methods
const openCreateModal = () => {
    isEditing.value = false;
    editingKey.value = '';
    form.value = { key: '', value: '' };
    showModal.value = true;
};

const openBulkCreateModal = () => {
    bulkForm.value.jsonData = '';
    showBulkModal.value = true;
};

const editTranslation = (key) => {
    isEditing.value = true;
    editingKey.value = key;
    form.value = {
        key: key,
        value: translations.value[key]
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.value = { key: '', value: '' };
};

const closeBulkModal = () => {
    showBulkModal.value = false;
    bulkForm.value.jsonData = '';
};

const submitTranslation = async () => {
    isSubmitting.value = true;

    try {
        if (isEditing.value) {
            // Update existing translation
            const response = await $http(`translations/${currentLocale.value}`, {
                method: 'PUT',
                body: {
                    key: editingKey.value,
                    value: form.value.value
                }
            });

            if (response.data) {
                // Update local state
                if (translations.value) {
                    translations.value[editingKey.value] = form.value.value;
                }
                notify('Translation updated successfully', 'success');
            }
        } else {
            // Create new translation
            const response = await $http(`translations/${currentLocale.value}`, {
                method: 'POST',
                body: {
                    key: form.value.key,
                    value: form.value.value
                }
            });

            if (response.data) {
                // Update local state
                if (!translations.value) {
                    translations.value = {};
                }
                translations.value[form.value.key] = form.value.value;
                notify('Translation created successfully', 'success');
            }
        }

        closeModal();
    } catch (err) {
        const errorMessage = err.data?.message || err.message || 'Failed to save translation';
        notify(errorMessage, 'error');
    } finally {
        isSubmitting.value = false;
    }
};

const submitBulkTranslations = async () => {
    isSubmitting.value = true;

    try {
        // Parse JSON data
        const translationsData = JSON.parse(bulkForm.value.jsonData);
        
        const response = await $http(`translations/${currentLocale.value}/bulk`, {
            method: 'POST',
            body: {
                translations: translationsData
            }
        });

        if (response.data) {
            // Reload translations to get updated data
            await loadTranslations();
            notify('Translations imported successfully', 'success');
            closeBulkModal();
        }
    } catch (err) {
        if (err instanceof SyntaxError) {
            notify('Invalid JSON format', 'error');
        } else {
            const errorMessage = err.data?.message || err.message || 'Failed to import translations';
            notify(errorMessage, 'error');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const deleteTranslation = async (key) => {
    if (!confirm(`Are you sure you want to delete the translation "${key}"?`)) {
        return;
    }

    try {
        const response = await $http(`translations/${currentLocale.value}`, {
            method: 'DELETE',
            body: {
                key: key
            }
        });

        if (response.data) {
            // Remove from local state
            if (translations.value && translations.value[key]) {
                delete translations.value[key];
            }
            notify('Translation deleted successfully', 'success');
        }
    } catch (err) {
        const errorMessage = err.data?.message || err.message || 'Failed to delete translation';
        notify(errorMessage, 'error');
    }
};

const copyTranslations = async () => {
    try {
        const jsonString = JSON.stringify(translations.value, null, 2);
        await navigator.clipboard.writeText(jsonString);
        notify('Translations copied to clipboard', 'success');
    } catch (err) {
        console.error('Failed to copy translations:', err);
        notify('Failed to copy translations', 'error');
    }
};

const downloadTranslations = () => {
    const jsonString = JSON.stringify(translations.value, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `translations-${currentLocale.value}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
};

// Initialize
onMounted(() => {
    // Get locale from cookie or default to 'en'
    const savedLocale = getCookie('locale');
    const initialLocale = savedLocale || 'en';
    
    currentLocale.value = initialLocale;
    selectedLocale.value = initialLocale;
    
    loadTranslations(initialLocale);
});

// Watch for locale changes
watch(currentLocale, (newLocale) => {
    console.log('Locale changed to:', newLocale);
});

definePageMeta({
    middleware: 'auth'
});
</script>

<style scoped>
/* Custom scrollbar for the table */
.max-h-96::-webkit-scrollbar {
    width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.dark .max-h-96::-webkit-scrollbar-track {
    background: #374151;
}

.dark .max-h-96::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .max-h-96::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>