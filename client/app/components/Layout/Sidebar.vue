<template>
  <aside class="bg-white dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 h-full flex flex-col">
    <div class="px-5 py-6 border-b border-slate-200 dark:border-slate-800">
      <NuxtLink to="/" class="flex items-center gap-3 rounded-3xl bg-slate-100 dark:bg-slate-900 px-4 py-3 transition hover:bg-slate-200 dark:hover:bg-slate-800">
        <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-linear-to-br from-blue-600 to-indigo-500 text-white shadow-lg">
          <component :is="getIconComponent('Dashboard')" class="h-5 w-5" />
        </div>
        <div>
          <p class="text-base font-semibold text-slate-900 dark:text-white">Medxe</p>
          <p class="text-sm text-slate-500 dark:text-slate-400">Admin workspace</p>
        </div>
      </NuxtLink>
    </div>

    <div class="px-5 py-4">
      <div class="rounded-3xl bg-slate-50 dark:bg-slate-900 px-4 py-4 text-slate-600 dark:text-slate-300">
        <p class="text-sm font-semibold text-slate-900 dark:text-white">Navigation</p>
        <p class="mt-1 text-xs leading-5">Access modules, users, and settings from one place.</p>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 pb-4">
      <ul class="space-y-3">
        <li v-for="(section, sectionIndex) in menuItems" :key="sectionIndex" class="space-y-3">
          <div v-if="section.title" class="pt-4">
            <p class="px-3 text-xs font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">
              {{ section.title }}
            </p>
          </div>

          <ul class="space-y-2">
            <li v-for="item in section.items" :key="item.id">
              <NuxtLink
                :to="item.to"
                class="group flex items-center gap-3 rounded-3xl px-4 py-3 transition-all duration-200"
                :class="item.isActive
                  ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 border-l-4 border-blue-600'
                  : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-900 hover:text-slate-900 dark:hover:text-white'"
              >
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white">
                  <component :is="getIconComponent(item.icon)" class="h-5 w-5" />
                </div>
                <span class="font-medium">{{ t(item.title) }}</span>
                <span v-if="item.badge" class="ml-auto rounded-full bg-blue-100 px-2 py-1 text-[11px] font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-200">
                  {{ item.badge }}
                </span>
              </NuxtLink>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- Sidebar footer -->
    <div class="p-5 border-t border-slate-200 dark:border-slate-800">
      <div class="rounded-3xl bg-slate-100 dark:bg-slate-900 p-4">
        <div class="flex items-center gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-white">
            <component :is="getIconComponent('Help')" class="h-5 w-5" />
          </div>
          <div>
            <p class="text-sm font-semibold text-slate-900 dark:text-white">Need help?</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Use the docs or reach out to support.</p>
          </div>
        </div>
        <button
          class="mt-4 w-full rounded-3xl border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 dark:border-blue-900 dark:bg-slate-950 dark:text-blue-300 dark:hover:bg-slate-900"
        >
          View Docs
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed, resolveComponent } from 'vue'
import { useMenu } from '../../composables/useMenu'

const menu = useMenu()
const menuItems = computed(() => menu.menuItems.value)
const { t } = useLocalization()

// Helper function to get icon component dynamically
const getIconComponent = (iconName: string) => {
  const iconComponents: Record<string, any> = {
    'Dashboard': resolveComponent('UIIconsDashboard'),
    'Users': resolveComponent('UIIconsUsers'),
    'Document': resolveComponent('UIIconsDocument'),
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
  width: 280px;
  min-width: 280px;
}

nav a.router-link-active {
  font-weight: 600;
}

nav a:hover {
  transform: translateX(2px);
  transition: transform 0.2s ease;
}
</style>
