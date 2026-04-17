<template>
  <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4 flex items-center justify-between">
      <!-- Left section: Logo and mobile menu toggle -->
      <div class="flex items-center space-x-4">
        <!-- Mobile menu button (hidden on lg and above) -->
        <button @click="$emit('toggleSidebar')"
          class="lg:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Logo/Brand -->
        <div class="flex items-center space-x-2">
          <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <span class="text-xl font-bold text-gray-800 dark:text-white">Admin Panel</span>
        </div>
      </div>

      <!-- Right section: User menu -->
      <div class="flex items-center space-x-4">
        <!-- User menu -->
        <div class="relative">
          <button @click="toggleUserMenu"
            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <div class="h-8 w-8 overflow-hidden rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
              <img v-if="userAvatarUrl" :src="userAvatarUrl" alt="Profile photo" class="h-full w-full object-cover" />
              <span v-else>{{ userInitials }}</span>
            </div>
            <div class="hidden md:block text-left">
              <p class="text-sm font-medium text-gray-800 dark:text-white">{{ userName }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ userRole }}</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
              fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>

          <!-- User dropdown menu -->
          <div v-if="showUserMenu"
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
              <p class="text-sm font-medium text-gray-800 dark:text-white">{{ userName }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ userEmail }}</p>
            </div>
            <div class="py-2">
              <NuxtLink to="/auth/profile"
                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
              </NuxtLink>
              <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
              <button @click="handleLogout"
                class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

const emit = defineEmits(['toggleSidebar'])

const auth = useAuth()
const router = useRouter()
const showUserMenu = ref(false)

const user = computed(() => auth.user.value)
const userName = computed(() => user.value?.name || 'User')
const userEmail = computed(() => user.value?.email || 'user@example.com')
const userRole = computed(() => {
  if (user.value?.roles?.some(r => r.slug === 'super_admin')) return 'Super Admin'
  if (user.value?.roles?.some(r => r.slug === 'admin')) return 'Admin'
  return 'User'
})
const userAvatarUrl = computed(() => user.value?.avatar_url || null)

const userInitials = computed(() => {
  const name = userName.value
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2)
})

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const handleLogout = async () => {
  const result = await auth.logout()
  if (result.success) {
    notification.success('Logged out successfully')
    router.push('/')
  } else {
    notification.error(result.message || 'Logout failed')
  }
}

onMounted(() => {
  auth.initialize()
})
</script>

<style scoped>
header {
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.dark header {
  background-color: rgba(31, 41, 55, 0.95);
}
</style>
