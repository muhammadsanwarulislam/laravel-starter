<template>
  <aside v-if="sidebarReady"
    class="top-0 left-0 h-screen bg-white dark:bg-gray-900 z-40 transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-700 flex flex-col" :class="[
      isMobile
        ? (isMobileOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full pointer-events-none')
        : (isExpanded ? 'w-64' : 'w-15')
    ]">
    
    <!-- Main Navigation Menu -->
    <nav class="flex-1 py-1 overflow-y-auto" :class="[isExpanded || isMobileOpen ? 'overflow-y-auto' : '']">
      <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex" class="mb-4">
        <h2 v-if="menuGroup.title && (isExpanded || isMobileOpen)"
          class="text-xs uppercase text-gray-500 dark:text-gray-400 mb-1 p-2 flex items-center">
          <span>{{ t(toLowerCase(menuGroup.title)) }}</span>
        </h2>
        <ul class="space-y-1 px-2">
          <li v-for="(item, itemIndex) in menuGroup.items" :key="item.name" class="relative has-submenu">
            <!-- Regular Menu Items -->
            <div v-if="!item.subItems">
              <router-link :to="item.path" @click="setActiveItem(item.path)"
                class="flex items-center rounded-lg transition-colors" :class="[
                  isActive(item.path)
                    ? 'bg-theme-100 dark:bg-theme-900/20 text-theme-600 dark:text-theme-400'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800',
                  isExpanded || isMobileOpen ? 'p-2' : 'p-0 justify-center'
                ]">
                <span class="flex-shrink-0" :class="[!isExpanded && !isMobile ? 'p-2' : '']">
                  <span v-html="item.icon"></span>
                </span>
                <span v-if="isExpanded || isMobileOpen" class="ml-3 text-sm font-medium">
                  {{ t(toLowerCase(item.name)) }}
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
                  <span class="flex-shrink-0" :class="[!isExpanded && !isMobile ? 'p-2' : '']">
                    <span v-html="item.icon"></span>
                  </span>

                  <span v-if="isExpanded || isMobileOpen" class="ml-3 text-sm font-medium flex-1 text-left">
                    {{ t(toLowerCase(item.name)) }}
                  </span>
                  <svg v-if="isExpanded || isMobileOpen" class="w-4 h-4 ml-auto transition-transform flex-shrink-0"
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
                    {{ t(toLowerCase(subItem.name)) }}
                  </router-link>
                </div>

                <!-- Floating submenu panel for collapsed sidebar -->
                <div
                  v-if="item.subItems && !isExpanded && !isMobile && floatingPanelOpen === `${groupIndex}-${itemIndex}`"
                  class="space-y-1 min-w-64 p-4 absolute left-10 ml-5 top-0 bg-white dark:bg-gray-900 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700">
                  <div class="text-theme-600 dark:text-theme-400 text-base font-semibold mb-2">
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

    <!-- User Profile Section -->
    <div class="border-t border-gray-200 dark:border-gray-700 p-2">
      <div class="relative">
        <!-- User Profile Button -->
        <button
          @click="toggleUserMenu"
          @mouseenter="!isExpanded && !isMobile ? handleUserMenuEnter() : null"
          @mouseleave="!isExpanded && !isMobile ? handleUserMenuLeave() : null"
          class="w-full flex items-center rounded-lg p-2 transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
          :class="{ 'bg-gray-100 dark:bg-gray-800': userMenuOpen }"
        >
          <!-- User Avatar -->
          <div class="flex-shrink-0 w-8 h-8 rounded-full bg-theme-500 flex items-center justify-center text-white text-sm font-medium">
            {{ getUserInitials }}
          </div>
          
          <!-- User Info (visible when expanded) -->
          <div v-if="isExpanded || isMobileOpen" class="ml-3 flex-1 text-left overflow-hidden">
            <p class="text-sm font-medium truncate">{{ userName }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ userEmail }}</p>
          </div>

          <!-- Dropdown Arrow (visible when expanded) -->
          <svg v-if="isExpanded || isMobileOpen" 
            class="w-4 h-4 ml-2 transition-transform flex-shrink-0"
            :class="{ 'rotate-180': userMenuOpen }" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <!-- User Dropdown Menu -->
        <div v-if="userMenuOpen && (isExpanded || isMobileOpen)"
          class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-30">
          <router-link
            v-for="item in userMenu"
            :key="item.name"
            :to="item.path"
            @click="handleUserMenuItemClick(item)"
            class="flex items-center px-4 py-3 text-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-800"
            :class="isActive(item.path) ? 'text-theme-600 dark:text-theme-400 bg-theme-50 dark:bg-theme-900/20' : 'text-gray-700 dark:text-gray-300'"
          >
            <span class="w-5 h-5 mr-3" v-html="item.icon"></span>
            <span>{{ item.name }}</span>
          </router-link>
        </div>

        <!-- Floating User Menu for Collapsed Sidebar -->
        <div v-if="!isExpanded && !isMobile && floatingUserMenuOpen"
          class="absolute left-12 bottom-0 mb-2 min-w-48 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-30">
          <div class="p-3 border-b border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ userName }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ userEmail }}</p>
          </div>
          <router-link
            v-for="item in userMenu"
            :key="item.name"
            :to="item.path"
            @click="handleUserMenuItemClick(item)"
            class="flex items-center px-4 py-3 text-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-800"
            :class="isActive(item.path) ? 'text-theme-600 dark:text-theme-400 bg-theme-50 dark:bg-theme-900/20' : 'text-gray-700 dark:text-gray-300'"
          >
            <span class="w-5 h-5 mr-3" v-html="item.icon"></span>
            <span>{{ item.name }}</span>
          </router-link>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useLocale } from '~/composables/useLocale';

const { t } = useLocale();
const route = useRoute();
const router = useRouter();

const sidebarReady = ref(false);
onMounted(() => {
  sidebarReady.value = true;
});

// Sidebar composable
const {
  isExpanded,
  isMobile,
  isMobileOpen,
  openSubmenu,
  toggleSubmenu
} = useSidebar();

const { menuGroups, userMenu } = getMenu();

// User data (replace with actual user data from your auth system)
const userName = ref("John Doe");
const userEmail = ref("john.doe@example.com");

// Computed for user initials
const getUserInitials = computed(() => {
  return userName.value
    .split(' ')
    .map(name => name[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
});

// User menu state
const userMenuOpen = ref(false);
const floatingUserMenuOpen = ref(false);

// Floating panel state
const floatingPanelOpen = ref(null);
const closeTimer = ref(null);

// Navigation functions
const isActive = (path) => route.path === path;
const isSubmenuOpen = (item) => openSubmenu.value === item;

const setActiveItem = (path) => {
  if (isMobile.value) {
    isMobileOpen.value = false;
  }
};

// User menu functions
const toggleUserMenu = () => {
  if (isExpanded.value || isMobileOpen.value) {
    userMenuOpen.value = !userMenuOpen.value;
  }
};

const handleUserMenuItemClick = (item) => {
  userMenuOpen.value = false;
  floatingUserMenuOpen.value = false;
  
  if (item.path === '/logout') {
    // Handle logout logic
    console.log('Logging out...');
    // Add your logout logic here
  } else {
    setActiveItem(item.path);
  }
};

// Floating menu functions
const handleFloatingEnter = (key) => {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value);
    closeTimer.value = null;
  }
  floatingPanelOpen.value = key;
};

const handleFloatingLeave = (key) => {
  if (closeTimer.value) clearTimeout(closeTimer.value);
  closeTimer.value = setTimeout(() => {
    if (floatingPanelOpen.value === key) {
      floatingPanelOpen.value = null;
    }
  }, 300);
};

const handleUserMenuEnter = () => {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value);
    closeTimer.value = null;
  }
  floatingUserMenuOpen.value = true;
};

const handleUserMenuLeave = () => {
  if (closeTimer.value) clearTimeout(closeTimer.value);
  closeTimer.value = setTimeout(() => {
    floatingUserMenuOpen.value = false;
  }, 300);
};

const toLowerCase = (str) => str.toLowerCase();

// Close menus when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('aside')) {
    userMenuOpen.value = false;
    floatingUserMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>