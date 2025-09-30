<template>
  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
    <div v-if="showSettings" @mouseleave="handleMouseLeave()" @mouseenter="handleMouseEnter()"
      class="absolute right-0 mt-2 w-120 bg-white dark:bg-gray-900 rounded-xl shadow-xl z-20 border border-gray-200 dark:border-gray-700 overflow-hidden">
      <!-- Header -->
      <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">Settings</h3>
        <button @click="emitClose" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-5 py-4 space-y-6 text-sm text-gray-700 dark:text-gray-200">
        <!-- Appearance -->
        <div>
          <div class="mb-4">
            <label class="block font-medium">Appearance</label>
            <small class="block text-xs text-gray-500 dark:text-gray-400">
              Customize the look and feel of the application
            </small>
          </div>
          <div class="flex gap-2">
            <!-- SVG Toggle Buttons -->
            <button @click="toggleTheme('light')" class="rounded-lg shadow"
              :class="{ 'border-theme border-1': themeMode === 'light' }">
              <img src="/img/appearance/light.svg" alt="Light Mode" class="rounded-lg p-1" />
            </button>
            <button @click="toggleTheme('dark')" class="rounded-lg shadow"
              :class="{ 'border-theme border-1': themeMode === 'dark' }">
              <img src="/img/appearance/dark.svg" alt="Dark Mode" class="rounded-lg p-1" />
            </button>
            <button @click="toggleTheme('system')" class="rounded-lg shadow"
              :class="{ 'border-theme border-1': themeMode === 'system' }">
              <img src="/img/appearance/system.svg" alt="System Default" class="rounded-lg p-1" />
            </button>
          </div>
        </div>

        <!-- Theme Color -->
        <div>
          <div class="mb-4">
            <label class="block font-medium">Theme Color</label>
            <small class="block text-xs text-gray-500 dark:text-gray-400">
              Personalize your experience with a custom theme color
            </small>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div v-for="color in themeColors" :key="color.name" @click="themeColor = color.name"
              class="flex items-center gap-2 cursor-pointer p-2 rounded-md transition"
              :class="themeColor === color.name
                ? 'bg-blue-100 text-white dark:bg-blue-800'
                : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700'">
              <span class="h-4 w-4 rounded text-xs" :style="{ backgroundColor: color.value }"></span>
              <span class="text-sm"
                :class="themeColor === color.name ? 'font-semibold text-gray-800 dark:text-white' : 'text-gray-600 dark:text-gray-400'">
                {{ color.name }}
              </span>
            </div>
          </div>
        </div>

        <!-- Font Size -->
        <div>
          <div class="mb-4">
            <label class="block font-medium">Font Size</label>
            <small class="block text-xs text-gray-500 dark:text-gray-400">
              Adjust the text size across the application
            </small>
          </div>
          <input type="range" min="10" max="18" step="2" v-model="fontSize"
            class="w-full accent-blue-600 h-1 hover:cursor-pointer bg-gray-200 dark:bg-gray-700 rounded-lg" />
          <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span>Extra Small</span>
            <span>Medium</span>
            <span>Extra Large</span>
          </div>
        </div>

        <!-- Show Server Time -->
        <div class="flex items-center justify-between">
          <label class="font-medium">Server Time</label>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="showServerTime" class="sr-only peer">
            <div
              class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:bg-blue-600 transition-all">
            </div>
            <div
              class="absolute left-0.5 top-0.5 bg-white dark:bg-gray-300 w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4">
            </div>
          </label>
        </div>

        <!-- Notification Sound -->
        <div class="flex items-center justify-between mt-4">
          <label class="font-medium">Notification Sound</label>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="notificationSound" class="sr-only peer">
            <div
              class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:bg-blue-600 transition-all">
            </div>
            <div
              class="absolute left-0.5 top-0.5 bg-white dark:bg-gray-300 w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4">
            </div>
          </label>
        </div>


      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'

const props = defineProps({
  showSettings: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close'])

function emitClose() {
  emit('close')
}

const closeTimer = ref(null)
function handleMouseLeave() {
  if (closeTimer.value) clearTimeout(closeTimer.value)
  closeTimer.value = setTimeout(() => {
    emitClose()
  }, 1000)
}
function handleMouseEnter() {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value)
    closeTimer.value = null
  }
}

import useDarkMode from '~/composables/useDarkMode.js'
const { themeMode, isDark, toggleTheme } = useDarkMode()

const { fontSize, themeColor, notificationSound, showServerTime } = useSidebar();

const themeColors = [
  { name: 'Blue', value: '#3B82F6' },
  { name: 'Green', value: '#10B981' },
  { name: 'Purple', value: '#8B5CF6' },
  { name: 'Orange', value: '#F97316' },
  { name: 'Pink', value: '#EC4899' },
  { name: 'Teal', value: '#14B8A6' },
  { name: 'Gray', value: '#6B7280' },
  { name: 'Red', value: '#EF4444' },
  { name: 'Mint', value: '#3dd6bb' },
  { name: 'Bronze', value: '#a86f3d' },
  { name: 'Indigo', value: '#6366f1' },
]

</script>
