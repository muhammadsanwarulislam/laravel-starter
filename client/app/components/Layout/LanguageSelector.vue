<template>
    <button @click="languageSheetOpen = true"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-200 group">
        <span class="text-2xl">{{ getLanguageIcon(currentLocale) }}</span>
        <div class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
    </button>

    <!-- Bottom Sheet -->
    <div v-if="languageSheetOpen" class="fixed inset-0 z-50">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="languageSheetOpen = false"></div>

        <!-- Sheet Content -->
        <div
            class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Drag Handle -->
            <div class="flex justify-center pt-3 shrink-0">
                <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
            </div>

            <!-- Header -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Language</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Select your preferred language</p>
                    </div>
                    <button @click="languageSheetOpen = false"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Current Language -->
                <div
                    class="mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                    <div class="flex items-center space-x-3">
                        <span class="text-3xl">{{ getLanguageIcon(currentLocale) }}</span>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ getLanguageName(currentLocale)
                            }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">Currently selected</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language Grid - Scrollable Area -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div v-for="lang in availableLanguages" :key="lang.code" @click="changeLanguage(lang.code)"
                            :class="[
                                'p-4 rounded-xl border-2 cursor-pointer transition-all duration-200',
                                currentLocale === lang.code
                                    ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700'
                            ]">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">{{ getLanguageIcon(lang.code) }}</span>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ lang.name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ lang.native_name }}</div>
                                </div>
                                <svg v-if="currentLocale === lang.code"
                                    class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useLocalization } from '~/composables/useLocalization'

const {
    currentLocale,
    availableLanguages,
    setLocale,
    getLanguageName,
    getLanguageIcon,
    fetchAvailableLocales
} = useLocalization()

const languageSheetOpen = ref(false)

const changeLanguage = async (locale) => {
    languageSheetOpen.value = false
    await setLocale(locale)
}

onMounted(() => {
    fetchAvailableLocales()
})
</script>