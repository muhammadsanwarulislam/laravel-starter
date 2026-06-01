<template>
  <header
    class="sticky top-0 z-40 bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border-b border-slate-200/60 dark:border-slate-800/60">
    <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
      <!-- Left: Logo & Mobile Toggle -->
      <div class="flex items-center gap-3">
        <button @click="$emit('toggleSidebar')"
          class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 active:scale-95">
          <UIIconsMenu class="h-5 w-5" />
        </button>

        <!-- Animated Logo -->
        <div class="flex items-center gap-2.5 group cursor-pointer" @click="router.push('/')">
          <div class="relative h-9 w-9">
            <div
              class="absolute inset-0 bg-linear-to-br from-indigo-500 to-violet-600 rounded-xl shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow duration-300">
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
              <UIIconsLogo class="h-6 w-6 text-white" />
            </div>
          </div>
          <div class="hidden sm:block">
            <span
              class="text-lg font-bold bg-linear-to-r from-slate-800 to-slate-600 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
              Admin Panel
            </span>
            <span
              class="hidden md:inline-block ml-2 text-[10px] font-semibold px-1.5 py-0.5 rounded-md bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">
              PRO
            </span>
          </div>
        </div>
      </div>

      <!-- Right: Actions & User -->
      <div class="flex items-center gap-1 sm:gap-2">
        <!-- User Menu -->
        <div class="relative ml-1" ref="userMenuRef">
          <button @click="toggleUserMenu"
            class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 active:scale-95 border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
            <div class="relative">
              <div
                class="h-8 w-8 rounded-full bg-linear-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-sm font-bold shadow-md">
                <img v-if="userAvatarUrl" :src="userAvatarUrl" class="h-full w-full rounded-full object-cover" />
                <span v-else>{{ userInitials }}</span>
              </div>
              <span
                class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900"></span>
            </div>
            <UIIconsChevronDown class="h-4 w-4 text-slate-400 transition-transform duration-200"
              :class="{ 'rotate-180': showUserMenu }" />
          </button>

          <!-- Dropdown with animation -->
          <Transition name="dropdown">
            <div v-if="showUserMenu"
              class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl shadow-slate-200/50 dark:shadow-black/50 border border-slate-200/60 dark:border-slate-700/60 z-50 overflow-hidden">
              <div
                class="p-4 border-b border-slate-100 dark:border-slate-700/50 bg-linear-to-br from-slate-50 to-white dark:from-slate-800/50 dark:to-slate-800">
                <div class="flex items-center gap-3">
                  <div
                    class="h-10 w-10 rounded-full bg-linear-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold">
                    <img v-if="userAvatarUrl" :src="userAvatarUrl" class="h-full w-full rounded-full object-cover" />
                    <span v-else>{{ userInitials }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white">
                      {{ userName }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                      {{ userEmail }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="p-2">
                <NuxtLink to="/auth/profile"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors duration-150">
                  <UIIconsProfile class="h-4 w-4 text-slate-400" />
                  {{ t("common.button.profile") }}
                </NuxtLink>
                <NuxtLink to="/settings"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors duration-150">
                  <UIIconsSettings class="h-4 w-4 text-slate-400" />
                  Settings
                </NuxtLink>
                <div class="my-2 border-t border-slate-100 dark:border-slate-700/50">
                  <UIButton @click="handleLogout" variant="danger" class="w-full mt-2">
                    {{ t("common.button.logout") }}
                  </UIButton>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from "vue";
import { useAuth } from "~/composables/auth/useAuth";
import { notification } from "~/utils/notification";

const emit = defineEmits(["toggleSidebar"]);

const auth = useAuth();
const router = useRouter();
const showUserMenu = ref(false);
const { t } = useLocalization();

// User data
const user = computed(() => auth.user.value);
const userName = computed(() => user.value?.name || "User");
const userEmail = computed(() => user.value?.email || "user@example.com");
const userAvatarUrl = computed(() => user.value?.avatar_url || null);

const userInitials = computed(() => {
  const name = userName.value;
  return name
    .split(" ")
    .map((n) => n[0])
    .join("")
    .toUpperCase()
    .substring(0, 2);
});

// Toggle user menu
const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

// Close user menu
const closeUserMenu = () => {
  showUserMenu.value = false;
};

// Logout handler
const handleLogout = async () => {
  const result = await auth.logout();
  if (result.success) {
    notification.success(t("auth.logout.success"));
    router.push("/");
  } else {
    notification.error(t("auth.logout.failed"));
  }
};

// ==================== AUTO-CLOSE LOGIC ====================
// Reference to the user menu container element
const userMenuRef = ref<HTMLElement | null>(null);

/**
 * Handle click outside the user menu to close it.
 * Listens to global click events and checks if the target is inside the menu.
 */
const handleClickOutside = (event: MouseEvent) => {
  if (
    showUserMenu.value &&
    userMenuRef.value &&
    !userMenuRef.value.contains(event.target as Node)
  ) {
    closeUserMenu();
  }
};

/**
 * Handle Escape key press to close the user menu.
 */
const handleEscape = (event: KeyboardEvent) => {
  if (event.key === "Escape" && showUserMenu.value) {
    closeUserMenu();
  }
};

// Lifecycle hooks for adding/removing event listeners
onMounted(() => {
  auth.initialize();
  // Add global click listener for outside click detection
  document.addEventListener("click", handleClickOutside);
  // Add keyboard listener for Escape key
  document.addEventListener("keydown", handleEscape);
});

onUnmounted(() => {
  // Clean up event listeners to prevent memory leaks
  document.removeEventListener("click", handleClickOutside);
  document.removeEventListener("keydown", handleEscape);
});
</script>

<style scoped>
/* Header backdrop blur */
header {
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.dark header {
  background-color: rgba(31, 41, 55, 0.95);
}

/* Dropdown enter/leave animations */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.dropdown-enter-to,
.dropdown-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
