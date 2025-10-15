<template>
  <footer class="p-4 fixed bottom-0 w-full bg-white border-t dark:bg-gray-800 dark:border-gray-600">
    <div :class="[
      'mx-auto max-w-screen-xl text-center',
      darkMode ? 'text-gray-400' : 'text-gray-500',
      isMobile ? (isMobileOpen ? 'hidden' : '') : '',
      !isMobile && isExpanded ? 'left-64' : '',
    ]">
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
