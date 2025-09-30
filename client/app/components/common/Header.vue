<template>
  <header class="flex items-center justify-between bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 p-3 shadow-sm">
    <div class="flex items-center gap-4">
      <div>
        <commonSideBarToggle />
      </div>
      
      <div class="flex items-center gap-3">
        <div class="h-10 w-10">
          <div v-if="headerReady" class="bg-theme-600 dark:bg-theme-500 p-2 rounded-lg">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
        </div>
        <span class="font-bold text-xl text-gray-800 dark:text-white hidden md:block">Admin</span>
      </div>
      
      <commonSearchBar />
    </div>
    
    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
      <div class="hidden md:block relative">
        <commonDigitalClock />
      </div>
      <div class="relative">
        <commonThemeToggle />
      </div>
      
      <!-- Notifications Dropdown -->
      <div class="relative">
        <div class="relative">
          <button @click="showNotifications = !showNotifications" class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150">
            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full ring-2 ring-white"></span>
          </button>
        </div>
      </div>
      
      <!-- Profile Dropdown -->
      <div class="relative">
        <commonProfile @toggleProfileFull="toggleProfileFull" @toggleSettings="toggleSettings" @toggleLanguageSwitcher="toggleLanguageSwitcher" />
        <commonProfileFull :showProfileFull="showProfileFull" @close="showProfileFull = false" />
        <commonSetting :showSettings="showSettings" @close="showSettings = false" />
        <commonLanguageSwitcher :showLanguageSwitcher="showLanguageSwitcher" @close="showLanguageSwitcher = false" />
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'

const headerReady = ref(false)
onMounted(() => {
  headerReady.value = true
})

const showSettings = ref(false)

function toggleSettings() {
  showSettings.value = true
}

const showLanguageSwitcher = ref(false)

function toggleLanguageSwitcher() {
  showLanguageSwitcher.value = true
}

const showProfileFull = ref(false)

function toggleProfileFull() {
  showProfileFull.value = true
}

const showNotifications = ref(false)
</script>