<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Localization Management</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
              Manage languages and translations for your application
            </p>
          </div>
          
          <!-- Current Language Indicator -->
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
              <span class="text-2xl">{{ getLanguageIcon(currentLocale) }}</span>
              <div>
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ getLanguageName(currentLocale) }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Current Language</div>
              </div>
            </div>
            
            <!-- Language Switch Dropdown -->
            <div class="relative">
              <button @click="languageDropdownOpen = !languageDropdownOpen"
                      class="flex items-center space-x-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <span class="text-gray-600 dark:text-gray-400">Switch:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ currentLocale.toUpperCase() }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              
              <div v-if="languageDropdownOpen" 
                   @click.outside="languageDropdownOpen = false"
                   class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                <div class="p-2">
                  <div class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Select Language
                  </div>
                  <div v-for="lang in availableLanguages" 
                       :key="lang.code"
                       @click="changeCurrentLanguage(lang.code)"
                       class="flex items-center space-x-3 px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded cursor-pointer transition-colors"
                       :class="{ 'bg-indigo-50 dark:bg-indigo-900/20': currentLocale === lang.code }">
                    <span class="text-lg">{{ getLanguageIcon(lang.code) }}</span>
                    <span class="flex-1 font-medium text-gray-700 dark:text-gray-300">{{ lang.name }}</span>
                    <span v-if="currentLocale === lang.code" class="text-indigo-600 dark:text-indigo-400">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Language Management Section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Available Languages</h2>
            <p class="text-gray-600 dark:text-gray-400">Configure and manage application languages</p>
          </div>
          <button @click="showAddLanguageModal = true"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Language
          </button>
        </div>

        <!-- Languages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="language in languages" :key="language.code"
               class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                  <span class="text-lg font-bold text-white">{{ getLanguageIcon(language.code) }}</span>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 dark:text-white">{{ language.name }}</h3>
                  <div class="flex items-center space-x-2 mt-1">
                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">
                      {{ language.code }}
                    </span>
                    <span v-if="language.is_default" class="text-xs px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded">
                      Default
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex items-center">
                <button @click="toggleLanguageStatus(language)"
                        :class="[
                          'px-3 py-1 text-xs font-medium rounded-full',
                          language.is_active 
                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' 
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                        ]">
                  {{ language.is_active ? 'Active' : 'Inactive' }}
                </button>
              </div>
            </div>
            
            <div class="space-y-2 mb-4">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Native Name:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ language.native_name }}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Direction:</span>
                <span class="font-medium capitalize">{{ language.direction }}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Translations:</span>
                <span class="font-medium">{{ getTranslationStats(language.code) }}</span>
              </div>
            </div>

            <div class="flex items-center space-x-2">
              <button @click="editLanguage(language)"
                      class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                Edit
              </button>
              <button @click="setAsDefaultLanguage(language)" 
                      :disabled="language.is_default"
                      :class="[
                        'flex-1 px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                        language.is_default
                          ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 cursor-default'
                          : 'bg-indigo-600 hover:bg-indigo-700 text-white'
                      ]">
                {{ language.is_default ? 'Current Default' : 'Set as Default' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Translation Management Section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Translation Editor</h2>
            <p class="text-gray-600 dark:text-gray-400">Edit and manage text translations</p>
          </div>
          <div class="flex items-center space-x-4">
            <select v-model="selectedLocaleForEdit" @change="loadTranslations"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <option value="">Select Language to Edit</option>
              <option v-for="lang in languages" :key="lang.code" :value="lang.code">
                {{ lang.name }} ({{ lang.code }})
              </option>
            </select>
            
            <div class="relative">
              <button @click="showAddTranslationModal = true" :disabled="!selectedLocaleForEdit"
                      class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors">
                Add Translation
              </button>
            </div>
          </div>
        </div>

        <!-- Translation Search and Filter -->
        <div v-if="selectedLocaleForEdit" class="mb-6">
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
              <input v-model="searchQuery" @input="filterTranslations"
                     type="text" placeholder="Search translations..."
                     class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
              <select v-model="selectedGroup" @change="filterTranslations"
                      class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Groups</option>
                <option v-for="group in availableGroups" :key="group" :value="group">
                  {{ group }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <!-- Translations List -->
        <div v-if="selectedLocaleForEdit && filteredTranslations.length > 0" class="space-y-4">
          <div v-for="translation in paginatedTranslations" :key="translation.key"
               class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between mb-3">
              <div>
                <div class="flex items-center space-x-2 mb-1">
                  <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded">
                    {{ translation.group }}
                  </span>
                  <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ formatKey(translation.key) }}
                  </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 font-mono mb-2">
                  {{ translation.key }}
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <button @click="editTranslation(translation)"
                        class="px-3 py-1 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                  Edit
                </button>
                <button @click="deleteTranslation(translation)"
                        class="px-3 py-1 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                  Delete
                </button>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Original (English)
                </label>
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                  <div class="text-gray-900 dark:text-white">{{ getEnglishTranslation(translation.key) }}</div>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  {{ getLanguageName(selectedLocaleForEdit) }} Translation
                </label>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                  <div class="text-gray-900 dark:text-white">{{ translation.value }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="filteredTranslations.length > itemsPerPage" class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-700 dark:text-gray-400">
              Showing <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              to <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredTranslations.length) }}</span>
              of <span class="font-medium">{{ filteredTranslations.length }}</span> translations
            </div>
            <div class="flex items-center space-x-2">
              <button @click="currentPage--" :disabled="currentPage === 1"
                      class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
              </button>
              <span class="px-3 py-1 text-gray-700 dark:text-gray-300">
                Page {{ currentPage }} of {{ totalPages }}
              </span>
              <button @click="currentPage++" :disabled="currentPage === totalPages"
                      class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
              </button>
            </div>
          </div>
        </div>

        <!-- Empty States -->
        <div v-if="selectedLocaleForEdit && filteredTranslations.length === 0" class="text-center py-12">
          <div class="max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No translations found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              {{ searchQuery ? 'No translations match your search.' : 'No translations available for this language.' }}
            </p>
            <button @click="showAddTranslationModal = true"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
              Add Your First Translation
            </button>
          </div>
        </div>

        <div v-else-if="!selectedLocaleForEdit" class="text-center py-12">
          <div class="max-w-md mx-auto">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
              <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Select a Language</h3>
            <p class="text-gray-600 dark:text-gray-400">
              Choose a language from the dropdown above to view and edit translations.
            </p>
          </div>
        </div>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                <span class="text-2xl">{{ getLanguageIcon(currentLocale) }}</span>
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ getLanguageName(currentLocale) }}</h3>
              <p class="text-gray-600 dark:text-gray-400">Current Language</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ languages.length }}</h3>
              <p class="text-gray-600 dark:text-gray-400">Available Languages</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalTranslations }}</h3>
              <p class="text-gray-600 dark:text-gray-400">Total Translations</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Language Modal -->
    <LocalizationLanguageModal 
      v-if="showAddLanguageModal || editingLanguage"
      :language="editingLanguage"
      @close="closeLanguageModal"
      @save="saveLanguage"
    />

    <!-- Add/Edit Translation Modal -->
    <LocalizationTranslationModal
      v-if="showAddTranslationModal || editingTranslation"
      :translation="editingTranslation"
      :locale="selectedLocaleForEdit"
      :languages="languages"
      @close="closeTranslationModal"
      @save="saveTranslation"
    />

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
        <div class="flex items-center space-x-3">
          <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-gray-700 dark:text-gray-300">Loading translations...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLocalization } from '~/composables/useLocalization'

// Components (you'll need to create these)
// import LanguageModal from '~/components/localization/LanguageModal.vue'
// import TranslationModal from '~/components/localization/TranslationModal.vue'

// Use localization composable
const { 
  currentLocale, 
  availableLanguages, 
  translations: allTranslations,
  setLocale,
  fetchAvailableLocales,
  getLanguageName,
  getLanguageIcon
} = useLocalization()

// State
const loading = ref(false)
const languages = ref([])
const selectedLocaleForEdit = ref('')
const showAddLanguageModal = ref(false)
const showAddTranslationModal = ref(false)
const editingLanguage = ref(null)
const editingTranslation = ref(null)
const languageDropdownOpen = ref(false)

// Filtering
const searchQuery = ref('')
const selectedGroup = ref('')
const availableGroups = ref(['ui', 'validation', 'auth', 'pagination', 'custom'])

// Pagination
const currentPage = ref(1)
const itemsPerPage = 10

// Computed
const filteredTranslations = computed(() => {
  let filtered = translations.value
  
  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(t => 
      t.key.toLowerCase().includes(query) || 
      t.value.toLowerCase().includes(query)
    )
  }
  
  // Filter by group
  if (selectedGroup.value) {
    filtered = filtered.filter(t => t.group === selectedGroup.value)
  }
  
  return filtered
})

const paginatedTranslations = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredTranslations.value.slice(start, end)
})

const totalPages = computed(() => {
  return Math.ceil(filteredTranslations.value.length / itemsPerPage)
})

const totalTranslations = computed(() => {
  return languages.value.reduce((total, lang) => total + (lang.translation_count || 0), 0)
})

// This would be your dynamic translations state
const translations = ref([])

// Methods
const changeCurrentLanguage = async (locale) => {
  languageDropdownOpen.value = false
  const result = await setLocale(locale)
  if (result.success) {
    // The UI will update automatically through the composable
  }
}

const getTranslationStats = (locale) => {
  const lang = languages.value.find(l => l.code === locale)
  return lang ? (lang.translation_count || 0) + ' translations' : '0 translations'
}

const formatKey = (key) => {
  return key.split('.').pop().replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const getEnglishTranslation = (key) => {
  // This should fetch the English version of the translation
  // For now, return a placeholder
  return `Translation for: ${key}`
}

const filterTranslations = () => {
  currentPage.value = 1
}

const editLanguage = (language) => {
  editingLanguage.value = language
}

const setAsDefaultLanguage = async (language) => {
  if (language.is_default) return
  
  try {
    // Call API to set as default
    // await $fetch(`/api/languages/${language.id}/set-default`, { method: 'POST' })
    
    // Update local state
    languages.value = languages.value.map(lang => ({
      ...lang,
      is_default: lang.code === language.code
    }))
    
    // If this is the current locale, update it
    if (currentLocale.value === language.code) {
      // Optional: Refresh translations
    }
  } catch (error) {
    console.error('Error setting default language:', error)
  }
}

const toggleLanguageStatus = async (language) => {
  try {
    // Call API to toggle status
    // await $fetch(`/api/languages/${language.id}/toggle-status`, { method: 'POST' })
    
    // Update local state
    languages.value = languages.value.map(lang => 
      lang.code === language.code 
        ? { ...lang, is_active: !lang.is_active }
        : lang
    )
  } catch (error) {
    console.error('Error toggling language status:', error)
  }
}

const editTranslation = (translation) => {
  editingTranslation.value = translation
}

const deleteTranslation = async (translation) => {
  if (!confirm('Are you sure you want to delete this translation?')) return
  
  try {
    // Call API to delete
    // await $fetch(`/api/translations/${translation.id}`, { method: 'DELETE' })
    
    // Update local state
    translations.value = translations.value.filter(t => t.id !== translation.id)
  } catch (error) {
    console.error('Error deleting translation:', error)
  }
}

const closeLanguageModal = () => {
  showAddLanguageModal.value = false
  editingLanguage.value = null
}

const closeTranslationModal = () => {
  showAddTranslationModal.value = false
  editingTranslation.value = null
}

const saveLanguage = async (languageData) => {
  try {
    // Call API to save language
    // const method = editingLanguage.value ? 'PUT' : 'POST'
    // const url = editingLanguage.value 
    //   ? `/api/languages/${editingLanguage.value.id}` 
    //   : '/api/languages'
    // 
    // await $fetch(url, { method, body: languageData })
    
    // Refresh languages
    await fetchLanguages()
    closeLanguageModal()
  } catch (error) {
    console.error('Error saving language:', error)
  }
}

const saveTranslation = async (translationData) => {
  try {
    // Call API to save translation
    // const method = editingTranslation.value ? 'PUT' : 'POST'
    // const url = editingTranslation.value 
    //   ? `/api/translations/${editingTranslation.value.id}` 
    //   : '/api/translations'
    // 
    // await $fetch(url, { method, body: translationData })
    
    // Refresh translations
    await loadTranslations()
    closeTranslationModal()
  } catch (error) {
    console.error('Error saving translation:', error)
  }
}

// API Methods
const fetchLanguages = async () => {
  try {
    loading.value = true
    // Use the composable to fetch languages
    const result = await fetchAvailableLocales()
    
    if (result.success && availableLanguages.value) {
      languages.value = availableLanguages.value
      
      // Fetch translation counts for each language
      // This would be a separate API call
      languages.value = languages.value.map(lang => ({
        ...lang,
        translation_count: Math.floor(Math.random() * 100) + 50 // Mock data
      }))
    }
  } catch (error) {
    console.error('Error fetching languages:', error)
  } finally {
    loading.value = false
  }
}

const loadTranslations = async () => {
  if (!selectedLocaleForEdit.value) return
  
  try {
    loading.value = true
    currentPage.value = 1
    
    // This would fetch translations for the selected locale
    // const response = await $fetch(`/api/translations/${selectedLocaleForEdit.value}`)
    // translations.value = response.data
    
    // Mock data for demonstration
    translations.value = [
      { id: 1, key: 'welcome.message', group: 'ui', value: 'Welcome to our application!' },
      { id: 2, key: 'login.title', group: 'ui', value: 'Sign In to Your Account' },
      { id: 3, key: 'validation.required', group: 'validation', value: 'This field is required' },
      { id: 4, key: 'auth.logout', group: 'auth', value: 'Logout' },
      { id: 5, key: 'dashboard.title', group: 'ui', value: 'Dashboard Overview' },
      { id: 6, key: 'profile.edit', group: 'ui', value: 'Edit Profile' },
      { id: 7, key: 'validation.email', group: 'validation', value: 'Please enter a valid email address' },
      { id: 8, key: 'auth.register', group: 'auth', value: 'Create Account' },
      { id: 9, key: 'pagination.next', group: 'pagination', value: 'Next' },
      { id: 10, key: 'pagination.previous', group: 'pagination', value: 'Previous' },
    ]
  } catch (error) {
    console.error('Error loading translations:', error)
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(async () => {
  await fetchLanguages()
  // Set initial locale for editing
  selectedLocaleForEdit.value = currentLocale.value
})
</script>