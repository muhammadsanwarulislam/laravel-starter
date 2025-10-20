<template>
  <aside v-if="sidebarReady"
    class="top-0 left-0 h-screen bg-white dark:bg-gray-900 z-40 transition-all duration-300 ease-in-out" :class="[
      isMobile
        ? (isMobileOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full pointer-events-none')
        : (isExpanded ? 'w-64' : 'w-15')
    ]">
    <!-- Navigation Menu -->
    <nav class=" h-[calc(100vh-2rem)] py-1" :class="[isExpanded || isMobileOpen ? 'overflow-y-auto' : '']">
      <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex" class="">
        <h2 v-if="menuGroup.title && (isExpanded || isMobileOpen)"
          class="text-xs uppercase text-gray-500 dark:text-gray-400 mb-1 p-2 flex items-center">
          <span>{{ t(menuGroup.title) }}</span>
        </h2>
        <ul class="space-y-2 px-2">
          <li v-for="(item, itemIndex) in menuGroup.items" :key="item.name" class="relative has-submenu mb-1">

            <!-- Regular Menu Items -->
            <div v-if="!item.subItems">
              <router-link :to="item.path" @click="setActiveItem(item.path)"
                class="flex items-center rounded-lg transition-colors" :class="[
                  isActive(item.path)
                    ? 'bg-theme-100 dark:bg-theme-900/20 text-theme-600 dark:text-theme-400'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800',
                  isExpanded || isMobileOpen ? 'p-2' : 'p-0 justify-center'
                ]">
                <span v-if="(isExpanded || isMobileOpen) || (!isExpanded && !isMobile)" class="flex-shrink-0"
                  :class="[!isExpanded && !isMobile ? 'p-2' : '']">
                  <span v-html="item.icon"></span>
                </span>

                <span v-if="isExpanded || isMobileOpen" class="ml-3 text-sm font-medium">
                  {{ t('item.name') }}
                </span>
              </router-link>
            </div>
            <!-- Submenu trigger button -->
            <div v-else-if="item.subItems">

              <div @mouseenter="!isExpanded && !isMobile ? handleFloatingEnter(`${groupIndex}-${itemIndex}`) : null"
                @mouseleave="!isExpanded && !isMobile ? handleFloatingLeave(`${groupIndex}-${itemIndex}`) : null">
                <button @click="isExpanded || isMobileOpen ? toggleSubmenu(`${groupIndex}-${itemIndex}`) : null"
                  class="w-full flex items-center rounded-lg transition-colors" :class="[
                    isSubmenuOpen(`${groupIndex}-${itemIndex}`)
                      ? 'bg-theme-50 dark:bg-theme-900/20 text-theme-600 dark:text-theme-400'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800',
                    isExpanded || isMobileOpen ? 'p-2' : 'p-0 justify-center'
                  ]">
                  <span v-if="(isExpanded || isMobileOpen) || (!isExpanded && !isMobile)" class="flex-shrink-0"
                    :class="[!isExpanded && !isMobile ? 'p-2' : '']">
                    <span v-html="item.icon"></span>
                  </span>

                  <span v-if="isExpanded || isMobileOpen" class="ml-3 text-sm font-medium">
                    {{ item.name }}
                  </span>
                  <svg v-if="isExpanded || isMobileOpen" class="w-4 h-4 ml-auto transition-transform"
                    :class="{ 'rotate-180': isSubmenuOpen(`${groupIndex}-${itemIndex}`) }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <!-- Submenu Items - Expanded State -->
                <div v-if="isExpanded || isMobileOpen"
                  class="mt-1 ml-9 space-y-1 overflow-hidden transition-all duration-300"
                  :class="isSubmenuOpen(`${groupIndex}-${itemIndex}`) ? 'max-h-96' : 'max-h-0'">
                  <router-link v-for="subItem in item.subItems" :key="subItem.name" :to="subItem.path"
                    @click="setActiveItem(subItem.path)" class="block py-2 px-3 text-sm rounded-lg transition-colors"
                    :class="isActive(subItem.path)
                      ? 'text-theme-600 dark:text-theme-400'
                      : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">
                    {{ subItem.name }}
                  </router-link>
                </div>

                <!-- Floating submenu panel for collapsed sidebar (desktop only) -->
                <div
                  v-if="item.subItems && !isExpanded && !isMobile && floatingPanelOpen === `${groupIndex}-${itemIndex}`"
                  class="space-y-1 min-w-64 p-4 absolute left-10 ml-5 top-0 bg-white dark:bg-gray-900 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700">
                  <div class="text-theme-600 dark:text-theme-400 text-base font-semibold">
                    {{ item.name }}
                  </div>

                  <router-link v-for="subItem in item.subItems" :key="subItem.name" :to="subItem.path"
                    class="block py-2 px-3 text-sm rounded-lg transition-colors" :class="isActive(subItem.path)
                      ? 'text-theme-600 dark:text-theme-400'
                      : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">
                    {{ subItem.name }}
                  </router-link>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { useRoute } from "vue-router";
import { useLocale } from '~/composables/useLocale';

const { locale, t } = useLocale();

const sidebarReady = ref(false)
onMounted(() => {
  sidebarReady.value = true
})
// Sidebar composable
const {
  isExpanded,
  isMobile,
  isMobileOpen,
  openSubmenu,
  toggleSubmenu
} = useSidebar();

const route = useRoute();

const menuGroups = getMenu().menuGroups;

const isActive = (path) => route.path === path;
const isSubmenuOpen = (item) => openSubmenu.value === item;

const floatingPanelOpen = ref(null)

const closeTimer = ref(null)
function handleFloatingLeave(key) {
  if (closeTimer.value) clearTimeout(closeTimer.value)
  closeTimer.value = setTimeout(() => {
    closeFloatingPanel(key)
  }, 1000)
}
function handleFloatingEnter(key) {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value)
    closeTimer.value = null
  }
  openFloatingPanel(key)
}

function openFloatingPanel(key) { floatingPanelOpen.value = key }
function closeFloatingPanel(key) { if (floatingPanelOpen.value === key) floatingPanelOpen.value = null }
</script>
