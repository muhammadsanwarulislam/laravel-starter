<template>
  <button @click="toggleProfileMenu"
    class="flex items-center gap-2 focus:outline-none group p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200"
    :class="{ 'bg-gray-100 dark:bg-gray-800': showProfileMenu }" aria-label="User menu">
    <div class="relative">
      <img :src="user.picture" alt="Profile"
        class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-700 group-hover:border-blue-100 dark:group-hover:border-gray-600 transition-colors duration-200" />
      <span
        class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-gray-800"></span>
    </div>
    <span class="hidden md:inline text-gray-700 dark:text-gray-200 font-medium text-sm">Sanwarul</span>
    <svg
      class="h-4 w-4 ml-1 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors duration-200"
      fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <!-- Profile Dropdown Menu -->
  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
    <div v-if="showProfileMenu" @mouseleave="handleMouseLeave()"
      class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
          <p class="text-sm font-medium text-gray-900 dark:text-white">Sanwarul</p>
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">sanwarul@gmail.com</p>
        </div>
        <button @click="closeProfileMenu"
          class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
          <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <ul class="py-1">
        <li>
          <NuxtLink
            class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150"
            @click="toggleProfileFull">
            <svg class="h-4 w-4 mr-3 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ t('profile') }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink
            class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150"
            @click="toggleLanguageSwitcher">
            <svg class="h-4 w-4 mr-3 text-gray-500 dark:text-gray-400" viewBox="0 0 640 512" fill="currentColor">
              <path
                d="M192 64C209.7 64 224 78.3 224 96L224 128L352 128C369.7 128 384 142.3 384 160C384 177.7 369.7 192 352 192L342.4 192L334 215.1C317.6 260.3 292.9 301.6 261.8 337.1C276 345.9 290.8 353.7 306.2 360.6L356.6 383L418.8 243C423.9 231.4 435.4 224 448 224C460.6 224 472.1 231.4 477.2 243L605.2 531C612.4 547.2 605.1 566.1 589 573.2C572.9 580.3 553.9 573.1 546.8 557L526.8 512L369.3 512L349.3 557C342.1 573.2 323.2 580.4 307.1 573.2C291 566 283.7 547.1 290.9 531L330.7 441.5L280.3 419.1C257.3 408.9 235.3 396.7 214.5 382.7C193.2 399.9 169.9 414.9 145 427.4L110.3 444.6C94.5 452.5 75.3 446.1 67.4 430.3C59.5 414.5 65.9 395.3 81.7 387.4L116.2 370.1C132.5 361.9 148 352.4 162.6 341.8C148.8 329.1 135.8 315.4 123.7 300.9L113.6 288.7C102.3 275.1 104.1 254.9 117.7 243.6C131.3 232.3 151.5 234.1 162.8 247.7L173 259.9C184.5 273.8 197.1 286.7 210.4 298.6C237.9 268.2 259.6 232.5 273.9 193.2L274.4 192L64.1 192C46.3 192 32 177.7 32 160C32 142.3 46.3 128 64 128L160 128L160 96C160 78.3 174.3 64 192 64zM448 334.8L397.7 448L498.3 448L448 334.8z" />
            </svg>
            {{ t('language') }}
          </NuxtLink>
        </li>
        <li>
          <NuxtLink
            class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-150"
            @click="toggleSettings">
            <svg class="h-4 w-4 mr-3 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ t('settings') }}
          </NuxtLink>
        </li>
        <li class="border-t border-gray-100 dark:border-gray-800 mt-1">
          <NuxtLink
            class="flex items-center px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150"
            @click="logout">
            <svg class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            {{ t('signout') }}
          </NuxtLink>
        </li>
      </ul>
    </div>
  </transition>
</template>
<script setup>
import { useAuth } from '~/composables/useAuth'
import { useLocale } from '~/composables/useLocale';

const { t } = useLocale();

const showProfileMenu = ref(false)

const auth = useAuth()
function logout() {
  auth.logout()
  navigateTo('/', { replace: true })
}

const emit = defineEmits(['toggleProfileFull', 'toggleLanguageSwitcher', 'toggleSettings', 'toggleLoginInfo'])

function toggleSettings() {
  emit('toggleSettings')
  closeProfileMenu()
}

function toggleLanguageSwitcher() {
  emit('toggleLanguageSwitcher')
  closeProfileMenu()
}

function toggleProfileFull() {
  emit('toggleProfileFull')
  closeProfileMenu()
}

function toggleLoginInfo() {
  emit('toggleLoginInfo')
  closeProfileMenu()
}

const user = {
  name: 'Admin User',
  email: 'admin@newspaper.com',
  picture: 'https://i.pravatar.cc/100?img=3'
}

const closeTimer = ref(null)
function handleMouseLeave(key) {
  if (closeTimer.value) clearTimeout(closeTimer.value)
  closeTimer.value = setTimeout(() => {
    closeProfileMenu()
  }, 1000)
}

function toggleProfileMenu() {
  showProfileMenu.value = !showProfileMenu.value
}

function closeProfileMenu() {
  showProfileMenu.value = false
}
</script>