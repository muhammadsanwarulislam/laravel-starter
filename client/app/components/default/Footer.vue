<template>
  <footer class="p-4 fixed bottom-0 bg-white border-t dark:bg-gray-800 dark:border-gray-600 transition-all duration-300 ease-in-out z-30"
    :class="[
      isMobile 
        ? (isMobileOpen ? 'w-full left-0' : 'w-full left-0')
        : (isExpanded ? 'w-[calc(100%-16rem)] left-64' : 'w-[calc(100%-3.75rem)] left-15')
    ]">
    <div class="mx-auto max-w-screen-xl text-center" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
      <span class="block text-sm">&copy; 2025 <a href="https://example.com" class="hover:underline">Your Company</a>.
        All Rights Reserved.</span>
      <span class="block text-sm">Built with <a href="https://vuejs.org/" class="hover:underline">Vue.js</a> and <a
          href="https://nuxt.com/" class="hover:underline">Nuxt.js</a>.</span>
    </div>
  </footer>
</template>

<script setup>
const props = defineProps({ darkMode: Boolean })

const {
  isExpanded,
  isMobile,
  isMobileOpen,
} = useSidebar();
</script>