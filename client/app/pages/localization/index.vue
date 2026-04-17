<template>
  <div class="max-auto">
    <!-- Loading State -->
    <UILoadingSpinner v-if="isLoading" size="lg" />

    <!-- Error State -->
    <UIEmptyState v-else-if="!isLoading && error" title="Failed to load languages" :description="error"
      icon="localization" />

    <!-- Success State -->
    <div v-else>
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <UICard title="Total Languages" :value="totalItems" color="blue" />
        <UICard title="Active Languages" :value="activeLanguagesCount" color="green" />
        <UICard title="LTR Languages" :value="ltrLanguagesCount" color="purple" />
        <UICard title="RTL Languages" :value="rtlLanguagesCount" color="yellow" />
      </div>

      <!-- UI Translations Summary with Add Button -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ t('translations.title') }}</h2>
            <p class="text-gray-600 mt-1">
              {{ t('translations.description') }} <strong>{{ currentLocale.toUpperCase() }}</strong> 
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center w-full lg:w-auto">
            <div class="bg-blue-50 text-blue-700 px-4 py-3 rounded-lg">
              <div class="text-xs font-semibold uppercase tracking-wide">Total keys</div>
              <div class="text-2xl font-bold">{{ translationCount }}</div>
            </div>
            <div class="w-full sm:w-auto flex-1">
              <input v-model="translationSearch" type="text" placeholder="Search translations..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <UIButton variant="primary" size="sm" @click="openAddModal" title="Add Translation" class="hover:shadow-md">
              <template #icon>
                <UIIconsPlus class="h-4 w-4" />
              </template>
              {{ t('common.save') }}
            </UIButton>
          </div>
        </div>
      </div>

      <!-- Translations Table -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Translation</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="([key, value]) in paginatedEntries" :key="key" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900 font-mono break-all">{{ key }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  <div v-if="editingKey === key" class="flex flex-col gap-2">
                    <textarea v-model="editValue" rows="2"
                      class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" />
                    <div class="flex gap-2">
                      <UIButton variant="secondary" size="xs" outlined @click="cancelEdit" class="hover:shadow-md">
                        {{ t('common.cancel') }}
                      </UIButton>
                      <UIButton variant="primary" size="xs" :disabled="isSaving" @click="saveTranslation(key)"
                        class="hover:shadow-md">
                        {{ isSaving ? 'Saving...' : t('common.save') }}
                      </UIButton>
                    </div>
                    <div v-if="editError" class="text-red-600 text-xs">{{ editError }}</div>
                  </div>
                  <div v-else class="flex justify-between items-start gap-2">
                    <span class="break-all">{{ value }}</span>
                    <div class="flex gap-2">
                      <UIButton variant="secondary" size="xs" outlined @click="startEdit(key, value)" class="hover:shadow-md">
                        <template #icon><UIIconsPencil class="h-4 w-4" /></template>
                        {{ t('common.edit') }}
                      </UIButton>
                      <UIButton variant="danger" size="xs" outlined @click="deleteTranslation(key)" class="hover:shadow-md">
                        <template #icon><UIIconsTrash class="h-4 w-4" /></template>
                        {{ t('common.delete') }}
                      </UIButton>
                    </div>
                  </div>
                </td>
              </tr>
              <tr v-if="paginatedEntries.length === 0">
                <td colspan="2" class="px-6 py-8 text-center text-sm text-gray-500">No translations found.</td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination Controls -->
          <div v-if="totalFilteredCount > 0" class="px-6 py-3 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="text-sm text-gray-500">Showing {{ from }} to {{ to }} of {{ totalFilteredCount }} results</div>
              <div class="flex items-center gap-4">
                <select v-model="itemsPerPage" class="text-sm border rounded px-2 py-1">
                  <option :value="10">10 per page</option>
                  <option :value="25">25 per page</option>
                  <option :value="50">50 per page</option>
                  <option :value="100">100 per page</option>
                </select>
                <div class="flex gap-2">
                  <button @click="prevPage" :disabled="currentPage === 1"
                    class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">Previous</button>
                  <span class="px-3 py-1">Page {{ currentPage }} of {{ totalPages }}</span>
                  <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">Next</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Add Translation Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Translation (Multi-Locale)</h3>
        
        <div class="space-y-4">
          <!-- Key input -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Key <span class="text-red-500">*</span></label>
            <input v-model="newKey" type="text" placeholder="e.g., auth.login.error"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
            <p class="text-xs text-gray-500 mt-1">Unique identifier (dot notation recommended)</p>
          </div>

          <!-- Locale translations table -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Translations per language</label>
            <div class="border rounded-lg overflow-hidden">
              <table class="w-full">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Language</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Translation</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="lang in availableLanguages" :key="lang.code">
                    <td class="px-4 py-2 text-sm font-medium">
                      {{ lang.name }} ({{ lang.code.toUpperCase() }})
                    </td>
                    <td class="px-4 py-2">
                      <textarea
                        v-model="bulkTranslations[lang.code]"
                        rows="1"
                        class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 text-sm"
                        :placeholder="`Enter ${lang.name} translation...`"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <p class="text-xs text-gray-500 mt-1">Leave empty for languages you don't want to add now.</p>
          </div>

          <div v-if="addError" class="text-red-600 text-sm">{{ addError }}</div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
          <UIButton variant="secondary" size="sm" outlined @click="closeAddModal" class="hover:shadow-md">
            {{ t('common.cancel') }}
          </UIButton>
          <UIButton variant="primary" size="sm" :disabled="isAdding" @click="createNewTranslation" class="hover:shadow-md">
            {{ isAdding ? 'Adding...' : 'Add Translation(s)' }}
          </UIButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, watch, ref } from 'vue'
import { useLocalization } from '~/composables/useLocalization'

const {
  currentLocale,
  allLanguages,
  availableLanguages,
  translations,
  isLoading,
  error,
  initialized,
  initialize,
  refreshTranslations,
  storeTranslation,
  deleteTranslation,
} = useLocalization()

const { t } = useLocalization()

// Local UI state
const translationSearch = ref('')

// Editing state
const editingKey = ref<string | null>(null)
const editValue = ref('')
const isSaving = ref(false)
const editError = ref('')

// Add modal state (bulk)
const showAddModal = ref(false)
const newKey = ref('')
const bulkTranslations = ref<Record<string, string>>({})
const isAdding = ref(false)
const addError = ref('')

// Pagination state
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Computed for summary cards
const totalItems = computed(() => allLanguages.value.length)
const activeLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.is_active).length)
const ltrLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.direction === 'ltr').length)
const rtlLanguagesCount = computed(() => allLanguages.value.filter((lang: any) => lang.direction === 'rtl').length)

// Filtered & paginated entries
const filteredTranslationEntries = computed<[string, string][]>(() => {
  const entries = Object.entries(translations.value).sort(([a], [b]) => a.localeCompare(b))
  if (!translationSearch.value) return entries
  const query = translationSearch.value.toLowerCase()
  return entries.filter(([key, value]) =>
    key.toLowerCase().includes(query) || value.toLowerCase().includes(query)
  )
})
const totalFilteredCount = computed(() => filteredTranslationEntries.value.length)
const totalPages = computed(() => Math.ceil(totalFilteredCount.value / itemsPerPage.value))
const paginatedEntries = computed<[string, string][]>(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredTranslationEntries.value.slice(start, end)
})
const from = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1)
const to = computed(() => Math.min(currentPage.value * itemsPerPage.value, totalFilteredCount.value))
const translationCount = computed(() => totalFilteredCount.value)

// Reset page on search or page size change
watch([translationSearch, itemsPerPage], () => { currentPage.value = 1 })

const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++ }
const prevPage = () => { if (currentPage.value > 1) currentPage.value-- }

// Edit handlers
const startEdit = (key: string, currentValue: string) => {
  editingKey.value = key
  editValue.value = currentValue
  editError.value = ''
}
const cancelEdit = () => {
  editingKey.value = null
  editValue.value = ''
  editError.value = ''
}
const saveTranslation = async (key: string) => {
  if (!editValue.value.trim()) {
    editError.value = 'Translation cannot be empty'
    return
  }
  isSaving.value = true
  editError.value = ''
  const result = await storeTranslation(key, editValue.value)
  if (result.success) {
    cancelEdit()
  } else {
    editError.value = result.message || 'Failed to save translation'
  }
  isSaving.value = false
}


const openAddModal = () => {
  newKey.value = ''

  const initial: Record<string, string> = {}
  availableLanguages.value.forEach((lang: any) => {
    initial[lang.code] = ''
  })
  bulkTranslations.value = initial
  addError.value = ''
  showAddModal.value = true
}

const closeAddModal = () => {
  showAddModal.value = false
  newKey.value = ''
  bulkTranslations.value = {}
  addError.value = ''
}

const createNewTranslation = async () => {
  const key = newKey.value.trim()
  if (!key) {
    addError.value = 'Key is required'
    return
  }

  // Collect non-empty translations
  const translationsToAdd: { locale: string; value: string }[] = []
  for (const [locale, value] of Object.entries(bulkTranslations.value)) {
    if (value && value.trim()) {
      translationsToAdd.push({ locale, value: value.trim() })
    }
  }

  if (translationsToAdd.length === 0) {
    addError.value = 'At least one translation value is required'
    return
  }

  isAdding.value = true
  addError.value = ''

  let hasError = false
  let lastError = ''

  for (const { locale, value } of translationsToAdd) {
    const result = await storeTranslation(key, value, locale)
    if (!result.success) {
      hasError = true
      lastError = result.message || `Failed to save for ${locale}`
    }
  }

  if (!hasError) {
    closeAddModal()
    const currentLocaleUpdated = translationsToAdd.some(t => t.locale === currentLocale.value)
    if (currentLocaleUpdated) {
      await refreshTranslations()
    }
  } else {
    addError.value = lastError || 'Failed to add some translations'
  }

  isAdding.value = false
}

// Refresh data
const refreshData = async () => {
  await refreshTranslations()
  if (!initialized.value) await initialize()
}

// Watch locale changes
watch(currentLocale, async () => { await refreshTranslations() })

// Initialize on mount
onMounted(async () => {
  if (!initialized.value) await initialize()
})
</script>