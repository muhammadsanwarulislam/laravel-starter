<template>
  <aside class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full flex flex-col">
    <nav class="flex-1 overflow-y-auto p-4">
      <ul class="space-y-1">
        <template v-for="(section, sectionIndex) in menuItems" :key="sectionIndex">
          <li v-if="section.title" class="pt-4">
            <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              {{ section.title }}
            </p>
          </li>

          <!-- Menu Items -->
          <li v-for="item in section.items" :key="item.id">
            <NuxtLink :to="item.to" :class="['flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors',
              item.isActive
                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600'
                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700']">
              <component :is="getIconComponent(item.icon)" class="h-5 w-5" />
              <span>{{ item.title }}</span>
              <span v-if="item.badge"
                class="ml-auto bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                {{ item.badge }}
              </span>
            </NuxtLink>
          </li>
        </template>
      </ul>
    </nav>

    <!-- Sidebar footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
      <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <div class="flex items-center space-x-3">
          <div class="h-10 w-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
            <component :is="getIconComponent('Help')" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
          </div>
          <div>
            <p class="text-sm font-medium text-gray-800 dark:text-white">Need help?</p>
            <p class="text-xs text-gray-600 dark:text-gray-300">Check our documentation</p>
          </div>
        </div>
        <button
          class="mt-3 w-full bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 text-sm font-medium py-2 px-4 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
          View Docs
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useMenu } from '~/composables/useMenu'

const menu = useMenu()
const menuItems = computed(() => menu.menuItems.value)

// Helper function to get icon component dynamically
const getIconComponent = (iconName: string) => {
  const iconComponents: Record<string, any> = {
    'Dashboard': resolveComponent('UIIconsDashboard'),
    'Users': resolveComponent('UIIconsUsers'),
    'RolePermissions': resolveComponent('UIIconsRolePermissions'),
    'Settings': resolveComponent('UIIconsSettings'),
    'Localization': resolveComponent('UIIconsLocalization'),
    'Profile': resolveComponent('UIIconsProfile'),
    'Help': resolveComponent('UIIconsHelp')
  }

  return iconComponents[iconName] || resolveComponent('UIIconsDashboard')
}
</script>

<style scoped>
aside {
  width: 260px;
  min-width: 260px;
}

nav a.router-link-active {
  font-weight: 600;
}

nav a:hover {
  transform: translateX(2px);
  transition: transform 0.2s ease;
}
</style>