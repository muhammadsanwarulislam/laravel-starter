<template>
  <div class="h-screen flex flex-col overflow-hidden bg-white dark:bg-gray-900">
    <!-- ======================= Header ===================================== -->
    <div v-if="$slots.header" class="shrink-0 z-40">
      <slot name="header"></slot>
    </div>

    <!-- ======================= Content ===================================== -->
    <div class="flex-1 flex min-h-0 overflow-hidden relative">
      <aside v-if="$slots.sidebar" :class="[
        'flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800',
        'transition-all duration-300 ease-in-out z-50',
        isMobile
          ? 'fixed inset-y-0 left-0 w-64 sm:w-72 shadow-2xl transform transition-transform duration-300'
          : 'relative',
        !isMobile ? 'shrink-0' : '',
        isMobile && !drawerOpen ? '-translate-x-full' : 'translate-x-0',
        'md:relative md:translate-x-0 md:shadow-none',
      ]" :aria-hidden="isMobile && !drawerOpen">
        <slot name="sidebar" :mobile-open="drawerOpen" :close-mobile="closeDrawer" />
      </aside>

      <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        <div class="absolute opacity-[0.02] dark:opacity-[0.03] pointer-events-none" style="
            background-image: radial-gradient(
              circle at 1px 1px,
              currentColor 1px,
              transparent 0
            );
            background-size: 24px 24px;
          " />

        <!--======================= Body Slot =====================================-->
        <div class="flex-1 overflow-y-auto scrollbar-thin">
          <div v-if="$slots.body" class="flex-1">
            <slot name="body" />
          </div>
          <div v-else class="flex-1">
            <slot />
          </div>
        </div>
        <!--======================= Footer Slot =====================================-->
        <div v-if="$slots.footer" class="shrink-0">
          <slot name="footer" />
        </div>
      </main>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";

const props = defineProps({
  layoutMode: { type: String, default: "default" },
});
const isMobile = ref(false);
const drawerOpen = ref(false);

const checkMobile = () => {
  const wasMobile = isMobile.value;
  isMobile.value = window.innerWidth < 768;
  if (wasMobile && !isMobile.value) {
    drawerOpen.value = false;
    setBodyScrollLock(false);
  }
};

const setBodyScrollLock = (lock) => {
  if (lock) {
    document.body.style.overflow = "hidden";
    document.body.style.position = "relative";
  } else {
    document.body.style.overflow = "";
    document.body.style.position = "";
  }
};

const openDrawer = () => {
  if (!isMobile.value) return;
  drawerOpen.value = true;
  setBodyScrollLock(true);
};

const closeDrawer = () => {
  drawerOpen.value = false;
  setBodyScrollLock(false);
};

const toggleDrawer = () => {
  if (drawerOpen.value) closeDrawer();
  else openDrawer();
};

const handleEscape = (e) => {
  if (e.key === "Escape" && isMobile.value && drawerOpen.value) {
    closeDrawer();
  }
};

let resizeTimer;
const handleResize = () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(checkMobile, 150);
};

onMounted(() => {
  checkMobile();
  window.addEventListener("resize", handleResize);
  window.addEventListener("keydown", handleEscape);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
  window.removeEventListener("keydown", handleEscape);
  clearTimeout(resizeTimer);
  setBodyScrollLock(false);
});

defineExpose({ openDrawer, closeDrawer, toggleDrawer, isMobile, drawerOpen });
</script>

<style>
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
  background: #475569;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

aside {
  will-change: transform;
}
</style>
