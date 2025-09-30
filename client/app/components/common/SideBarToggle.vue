<template>
    <button
        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200 mr-2"
        :class="[!isExpanded ? 'bg-gray-100 dark:bg-gray-800' : '']"
        @click="toggleSidebar"
        aria-label="Toggle sidebar"
      >
    <svg class="h-6 w-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>
</template>
<script setup>

const { 
  isExpanded, 
  isMobileOpen, 
  isHovered, 
  openSubmenu, 
  toggleSidebar, 
  toggleMobileSidebar, 
  setIsHovered, 
  toggleSubmenu 
} = useSidebar();

</script>