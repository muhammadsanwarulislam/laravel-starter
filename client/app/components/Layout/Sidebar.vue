<template>
  <aside
  :class="[
    'sidebar relative bg-white dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 h-full flex flex-col transition-all duration-300 ease-in-out',
    collapsed ? 'sidebar-collapsed' : 'sidebar-expanded',
  ]"
  :style="{
    width: collapsed ? '80px' : '280px',
    minWidth: collapsed ? '80px' : '280px',
  }"
>
    <!-- Collapse Toggle Button -->
    <button
      @click="toggleSidebar"
      class="absolute -right-3 top-20 z-20 flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-md hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
    >
      <!-- Chevron icon: right when collapsed, left when expanded -->
      <svg
        v-if="collapsed"
        class="h-3 w-3"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 5l7 7-7 7"
        />
      </svg>
      <svg
        v-else
        class="h-3 w-3"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 19l-7-7 7-7"
        />
      </svg>
    </button>

    <!-- Scrollable Nav Area -->
    <nav class="flex-1 overflow-y-auto px-3 pb-4 mt-6">
      <ul class="space-y-4">
        <li
          v-for="(section, sectionIdx) in visibleMenuItems"
          :key="sectionIdx"
          class="space-y-2"
        >
          <!-- Section Title (only when expanded) -->
          <div v-if="section.title && !collapsed" class="px-3 pt-4">
            <p
              class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500"
            >
              {{ t(section.title) }}
            </p>
          </div>

          <!-- Items -->
          <ul class="space-y-1">
            <li v-for="item in section.items" :key="item.id">
              <NuxtLink
                :to="item.to"
                :class="[
                  'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all duration-200',
                  item.isActive
                    ? 'bg-gradient-to-r from-indigo-50 to-blue-50 text-indigo-700 dark:from-indigo-950/30 dark:to-blue-950/30 dark:text-indigo-300 shadow-sm'
                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-900/70 hover:text-slate-900 dark:hover:text-white',
                ]"
                :aria-current="item.isActive ? 'page' : undefined"
              >
                <!-- Icon Container -->
                <div
                  class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition-all duration-200 group-hover:scale-105 dark:bg-slate-900 dark:text-slate-400"
                  :class="
                    item.isActive ? 'bg-white shadow-sm dark:bg-slate-800' : ''
                  "
                >
                  <component
                    :is="getIconComponent(item.icon)"
                    class="h-5 w-5"
                  />
                </div>

                <!-- Label (with tooltip when collapsed) -->
                <span
                  v-if="!collapsed"
                  class="font-medium truncate"
                  :class="item.isActive ? 'font-semibold' : ''"
                >
                  {{ t(item.title) }}
                </span>
                <span
                  v-else
                  class="absolute left-full ml-2 hidden whitespace-nowrap rounded-md bg-slate-800 px-2 py-1 text-xs text-white group-hover:inline-block dark:bg-slate-700"
                >
                  {{ t(item.title) }}
                </span>

                <!-- Badge (only when expanded) -->
                <span
                  v-if="item.badge && !collapsed"
                  class="ml-auto rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-200"
                >
                  {{ item.badge }}
                </span>
              </NuxtLink>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- User Profile Section (at bottom) -->
    <div class="mt-auto border-t border-slate-200 p-3 dark:border-slate-800">
      <NuxtLink
        v-if="!collapsed"
        to="/auth/profile"
        class="flex items-center gap-3 rounded-xl p-2 transition-all hover:bg-slate-100 dark:hover:bg-slate-900"
      >
        <div class="relative">
          <img
            :src="userAvatar"
            :alt="userName"
            class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-800"
          />
          <span
            class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-slate-800"
          />
        </div>
        <div class="flex-1 truncate">
          <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
            {{ userName }}
          </p>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ userRole }}
          </p>
        </div>
        <!-- Chevron right icon -->
        <svg
          class="h-4 w-4 text-slate-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 5l7 7-7 7"
          />
        </svg>
      </NuxtLink>

      <!-- Collapsed avatar-only version -->
      <NuxtLink
        v-else
        to="/auth/profile"
        class="group relative flex justify-center rounded-xl p-2 transition-all hover:bg-slate-100 dark:hover:bg-slate-900"
      >
        <img
          :src="userAvatar"
          :alt="userName"
          class="h-9 w-9 rounded-full object-cover"
        />
        <span
          class="absolute left-full ml-2 hidden whitespace-nowrap rounded-md bg-slate-800 px-2 py-1 text-xs text-white group-hover:inline-block dark:bg-slate-700"
        >
          {{ userName }}
        </span>
      </NuxtLink>

      <!-- Logout Button -->
      <button
        @click="logout"
        class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-red-600 transition-all hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
        :class="collapsed ? 'px-2' : ''"
      >
        <!-- Logout icon (inline SVG) -->
        <svg
          class="h-4 w-4"
          :class="collapsed ? 'mr-0' : 'mr-2'"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
          />
        </svg>
        <span v-if="!collapsed">{{ t("common.button.logout") }}</span>
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from "vue";
import { useMenu } from "../../composables/useMenu";
import { useAuth } from "../../composables/auth/useAuth";
import { useLocalization } from "../../composables/useLocalization";
import { resolveComponent } from "vue";

const menu = useMenu();
const auth = useAuth();
const { t } = useLocalization();

// Collapse state with localStorage persistence
const STORAGE_KEY = "sidebar_collapsed";
const collapsed = ref(false);

const toggleSidebar = () => {
  collapsed.value = !collapsed.value;
  localStorage.setItem(STORAGE_KEY, String(collapsed.value));
};

onMounted(() => {
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved !== null) {
    collapsed.value = saved === "true";
  }
});

// Filter menu items based on permissions/roles (from your existing logic)
const visibleMenuItems = computed(() => {
  return menu.menuItems.value.filter((section) => section.items.length > 0);
});

// User data (adjust based on your actual auth.user structure)
const user = computed(() => (auth as any).user || { name: "Guest", role: "", avatar: "" });
const userName = computed(() => user.value.name || "User");
const userRole = computed(() => user.value.role || "Member");
const userAvatar = computed(
  () =>
    user.value.avatar ||
    `https://ui-avatars.com/api/?background=6366f1&color=fff&name=${encodeURIComponent(userName.value)}`,
);

// Icon mapping (same as your original but with fallback)
const getIconComponent = (iconName: string) => {
  const iconMap: Record<string, any> = {
    Dashboard: resolveComponent("UIIconsDashboard"),
    Users: resolveComponent("UIIconsUsers"),
    Document: resolveComponent("UIIconsDocument"),
    RolePermissions: resolveComponent("UIIconsRolePermissions"),
    Settings: resolveComponent("UIIconsSettings"),
    Localization: resolveComponent("UIIconsLocalization"),
    Profile: resolveComponent("UIIconsProfile"),
    Help: resolveComponent("UIIconsHelp"),
  };
  return iconMap[iconName] || resolveComponent("UIIconsDashboard");
};

// Logout handler
const logout = async () => {
  await auth.logout();
  await navigateTo("/auth/login");
};
</script>

<style scoped>
/* Smooth width transition for the entire aside */
.sidebar {
  transition-property: width, min-width;
}

/* Hide scrollbar for cleaner look (optional) */
.sidebar ::-webkit-scrollbar {
  width: 4px;
}
.sidebar ::-webkit-scrollbar-track {
  background: transparent;
}
.sidebar ::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.dark .sidebar ::-webkit-scrollbar-thumb {
  background: #475569;
}

/* Tooltip fade-in */
.group-hover\:inline-block {
  animation: fadeIn 0.1s ease-in-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateX(-4px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>