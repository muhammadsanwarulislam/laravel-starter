<
<template>
  <aside
    :class="[
      'sidebar relative h-full flex flex-col transition-all duration-500 ease-in-out',
      collapsed ? 'w-20' : 'w-70',
      'bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-r border-slate-200/60 dark:border-slate-800/60',
      mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      'fixed lg:static inset-y-0 left-0 z-40 lg:z-auto',
    ]"
  >
    <!-- Mobile Overlay -->
    <div
      v-if="mobileOpen"
      class="fixed inset-0 bg-black/20 backdrop-blur-sm z-[-1] lg:hidden"
      @click="$emit('closeMobile')"
    ></div>

    <!-- Collapse Toggle (Desktop) -->
    <button
      @click="toggleSidebar"
      class="hidden lg:flex absolute -right-3 top-24 z-20 h-6 w-6 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-200"
    >
      <svg
        class="h-3 w-3 transition-transform duration-300"
        :class="collapsed ? '' : 'rotate-180'"
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

    <!-- Logo Area (Mobile) -->
    <div
      v-if="mobileOpen"
      class="lg:hidden p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between"
    >
      <span class="text-lg font-bold text-slate-800 dark:text-white"
        >Admin Panel</span
      >
      <button
        @click="$emit('closeMobile')"
        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
      >
        <svg
          class="h-5 w-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <!-- Navigation -->
    <nav
      class="flex flex-1 flex-col overflow-y-auto scrollbar-thin px-2 py-4 lg:py-6 lg:px-3 space-y-1"
    >
      <ul class="space-y-2 text-sm font-medium">
        <li
          v-for="(section, sectionIdx) in visibleMenuItems"
          :key="sectionIdx"
          class="space-y-1"
        >
          <!-- Section Title -->
          <div v-if="section.title && !collapsed" class="px-3 mb-2">
            <p
              class="text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-600"
            >
              {{ t(section.title) }}
            </p>
          </div>

          <!-- Items -->
          <ul class="space-y-0.5">
            <li v-for="item in section.items" :key="item.id">
              <NuxtLink
                :to="item.to"
                :class="[
                  'group relative flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all duration-200',
                  item.isActive
                    ? 'bg-linear-to-r from-indigo-500/10 to-violet-500/10 text-indigo-700 dark:text-indigo-300 shadow-sm shadow-indigo-500/10'
                    : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-slate-200',
                ]"
              >
                <!-- Icon -->
                <div
                  :class="[
                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition-all duration-200',
                    item.isActive
                      ? 'bg-linear-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/30'
                      : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-500 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:shadow-sm',
                  ]"
                >
                  <component
                    :is="getIconComponent(item.icon)"
                    class="h-4.5 w-4.5"
                  />
                </div>

                <!-- Label -->
                <span
                  v-if="!collapsed"
                  class="font-medium text-sm truncate transition-all duration-300"
                  :class="item.isActive ? 'font-semibold' : ''"
                >
                  {{ t(item.title) }}
                </span>

                <!-- Collapsed Tooltip -->
                <div
                  v-if="collapsed"
                  class="absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-medium whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 shadow-xl z-50"
                >
                  {{ t(item.title) }}
                  <div
                    class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 border-4 border-transparent border-r-slate-800"
                  ></div>
                </div>

                <!-- Badge -->
                <span
                  v-if="item.badge && !collapsed"
                  class="ml-auto rounded-full bg-indigo-100 dark:bg-indigo-900/40 px-2 py-0.5 text-[10px] font-bold text-indigo-700 dark:text-indigo-300"
                >
                  {{ item.badge }}
                </span>

                <!-- Active Indicator -->
                <div
                  v-if="item.isActive && !collapsed"
                  class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 rounded-r-full bg-linear-to-b from-indigo-500 to-violet-600"
                ></div>
              </NuxtLink>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- User Mini Profile -->
    <div
      class="mt-auto border-t border-slate-200/60 dark:border-slate-800/60 p-3"
    >
      <button
        @click="logout"
        class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all duration-200 active:scale-95"
        :class="collapsed ? 'px-2' : ''"
      >
        <svg
          class="h-4 w-4"
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
import { resolveComponent } from "vue";

const props = defineProps({
  mobileOpen: { type: Boolean, default: false },
});
const emit = defineEmits(["closeMobile"]);

const menu = useMenu();
const auth = useAuth();
const { t } = useLocalization();

const STORAGE_KEY = "sidebar_collapsed";
const collapsed = ref(false);

const toggleSidebar = () => {
  collapsed.value = !collapsed.value;
  localStorage.setItem(STORAGE_KEY, String(collapsed.value));
};

onMounted(() => {
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved !== null) collapsed.value = saved === "true";
});

const visibleMenuItems = computed(() => {
  return menu.menuItems.value.filter((section) => section.items.length > 0);
});

const user = computed(
  () => (auth as any).user || { name: "Guest", role: "", avatar: "" },
);

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
    SuperMarket: resolveComponent("UIIconsSuperMarket"),
    Modules: resolveComponent("UIIconsModules"),
  };
  return iconMap[iconName] || resolveComponent("UIIconsDashboard");
};

const logout = async () => {
  await auth.logout();
  await navigateTo("/auth/login");
};
</script>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
  background: #475569;
}
</style>
