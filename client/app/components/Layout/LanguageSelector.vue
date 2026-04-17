<template>
  <div>
    <!-- Floating Button -->
    <button
      ref="triggerButton"
      @click="openSheet"
      class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      :aria-label="`Change language (currently ${getLanguageName(currentLocale)})`"
    >
      <span class="text-2xl">{{ getLanguageIcon(currentLocale) }}</span>
      <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
    </button>

    <!-- Bottom Sheet (Portal to body for better stacking) -->
    <Teleport to="body">
      <Transition name="sheet-backdrop">
        <div
          v-if="isSheetOpen"
          class="fixed inset-0 z-50"
          @click.self="closeSheet"
        >
          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300"></div>

          <!-- Sheet Content -->
          <Transition name="sheet-slide">
            <div
              v-if="isSheetOpen"
              class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl max-h-[90vh] overflow-hidden flex flex-col"
              :dir="currentDirection"
              role="dialog"
              aria-modal="true"
              aria-label="Language selection"
            >
              <!-- Drag Handle -->
              <div class="flex justify-center pt-3 shrink-0">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
              </div>

              <!-- Header -->
              <div class="p-6 border-b border-gray-100 dark:border-gray-700 shrink-0">
                <div class="flex items-center justify-between">
                  <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                      {{ currentDirection === 'rtl' ? 'اللغة' : 'Language' }}
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                      {{ currentDirection === 'rtl' ? 'اختر لغتك المفضلة' : 'Select your preferred language' }}
                    </p>
                  </div>
                  <button
                    @click="closeSheet"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400"
                    aria-label="Close"
                  >
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                <!-- Current Language Badge -->
                <div
                  class="mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800"
                >
                  <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="text-3xl">{{ getLanguageIcon(currentLocale) }}</span>
                    <div>
                      <div class="font-semibold text-gray-900 dark:text-white">{{ getLanguageName(currentLocale) }}</div>
                      <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ currentDirection === 'rtl' ? 'اللغة الحالية' : 'Currently selected' }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Language List (with loading and error states) -->
              <div class="flex-1 overflow-y-auto">
                <!-- Loading -->
                <div v-if="isLoading" class="p-8 text-center">
                  <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                  <p class="text-gray-500 mt-2">Loading languages...</p>
                </div>

                <!-- Error -->
                <div v-else-if="error" class="p-8 text-center">
                  <div class="text-red-500 text-4xl mb-2">⚠️</div>
                  <p class="text-gray-600 mb-3">{{ error }}</p>
                  <button
                    @click="retryLoad"
                    class="text-indigo-600 hover:text-indigo-700 text-sm font-medium"
                  >
                    Try again
                  </button>
                </div>

                <!-- Language Grid -->
                <div v-else class="p-6">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button
                      v-for="lang in availableLanguages"
                      :key="lang.code"
                      @click="selectLanguage(lang.code)"
                      :class="[
                        'p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 text-left focus:outline-none focus:ring-2 focus:ring-indigo-500',
                        currentLocale === lang.code
                          ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-500/50'
                          : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md'
                      ]"
                      :aria-current="currentLocale === lang.code ? 'true' : undefined"
                    >
                      <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <span class="text-2xl">{{ getLanguageIcon(lang.code) }}</span>
                        <div class="flex-1">
                          <div class="font-medium text-gray-900 dark:text-white">{{ lang.name }}</div>
                          <div class="text-sm text-gray-500 dark:text-gray-400">{{ lang.native_name }}</div>
                        </div>
                        <svg
                          v-if="currentLocale === lang.code"
                          class="w-5 h-5 text-indigo-600 dark:text-indigo-400 shrink-0"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue'
import { useLocalization } from '~/composables/useLocalization'

const {
  currentLocale,
  availableLanguages,
  isLoading,
  error,
  initialize,
  setLocale,
  getLanguageName,
  getLanguageIcon,
  fetchAvailableLocales,
} = useLocalization()

// Sheet state
const isSheetOpen = ref(false)
const triggerButton = ref<HTMLButtonElement | null>(null)

// Direction for RTL support
const currentDirection = computed(() => {
  const lang = availableLanguages.value.find(l => l.code === currentLocale.value)
  return lang?.direction === 'rtl' ? 'rtl' : 'ltr'
})

// Open sheet with focus management
const openSheet = async () => {
  // Ensure languages are loaded
  if (availableLanguages.value.length === 0 && !isLoading.value) {
    await fetchAvailableLocales()
  }
  isSheetOpen.value = true
  // Prevent body scroll
  document.body.style.overflow = 'hidden'
  // After sheet animation, focus first language button for accessibility
  await nextTick()
  const firstLangButton = document.querySelector('[role="dialog"] button:not([aria-hidden])')
  if (firstLangButton instanceof HTMLElement) {
    firstLangButton.focus()
  }
}

// Close sheet and restore scroll
const closeSheet = () => {
  isSheetOpen.value = false
  document.body.style.overflow = ''
  // Return focus to trigger button
  triggerButton.value?.focus()
}

// Select language and close sheet
const selectLanguage = async (locale: string) => {
  if (locale === currentLocale.value) {
    closeSheet()
    return
  }
  await setLocale(locale)
  closeSheet()
}

// Retry loading languages on error
const retryLoad = async () => {
  await fetchAvailableLocales()
}

// Keyboard: Escape to close
const handleKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && isSheetOpen.value) {
    closeSheet()
  }
}

// Initialize on mount
onMounted(async () => {
  await initialize()
  window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
  // Clean up body scroll if component unmounts while sheet open
  if (isSheetOpen.value) {
    document.body.style.overflow = ''
  }
})
</script>

<style scoped>
/* Backdrop fade transition */
.sheet-backdrop-enter-active,
.sheet-backdrop-leave-active {
  transition: opacity 0.3s ease;
}
.sheet-backdrop-enter-from,
.sheet-backdrop-leave-to {
  opacity: 0;
}
.sheet-backdrop-enter-to,
.sheet-backdrop-leave-from {
  opacity: 1;
}

/* Sheet slide-up transition */
.sheet-slide-enter-active,
.sheet-slide-leave-active {
  transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
}
.sheet-slide-enter-from,
.sheet-slide-leave-to {
  transform: translateY(100%);
}
.sheet-slide-enter-to,
.sheet-slide-leave-from {
  transform: translateY(0);
}

/* RTL adjustments for sheet content */
[dir="rtl"] .space-x-3 > :not([hidden]) ~ :not([hidden]) {
  margin-right: 0.75rem;
  margin-left: 0;
}
[dir="rtl"] .text-left {
  text-align: right;
}
</style>