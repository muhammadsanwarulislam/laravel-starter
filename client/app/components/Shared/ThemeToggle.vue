<template>
  <button
    @click="toggleTheme"
    :aria-label="`Switch to ${isDark ? 'light' : 'dark'} theme`"
    :class="[
      'p-2 rounded-lg transition-all duration-300 relative overflow-hidden group',
      isDark 
        ? 'bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-900/50' 
        : 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700'
    ]"
    :title="`Current: ${isDark ? 'Dark' : 'Light'}`"
  >
    <!-- Background effect -->
    <div class="absolute inset-0 bg-linear-to-r from-primary-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity" />
    
    <!-- Icons with better visibility -->
    <div class="relative z-10 flex items-center justify-center">
      <!-- Sun (Light mode icon) -->
      <svg 
        v-if="isDark" 
        class="w-5 h-5 text-yellow-500 transform transition-transform group-hover:rotate-45"
        fill="currentColor" 
        viewBox="0 0 20 20"
      >
        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
      </svg>
      
      <!-- Moon (Dark mode icon) -->
      <svg 
        v-else 
        class="w-5 h-5 text-blue-600 dark:text-gray-300 transform transition-transform group-hover:-rotate-12"
        fill="currentColor" 
        viewBox="0 0 20 20"
      >
        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
      </svg>
    </div>
    
    <!-- Tooltip text -->
    <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
      Switch to {{ isDark ? 'light' : 'dark' }} mode
    </div>
  </button>
</template>

<script setup>
const { isDark, toggleTheme } = useTheme()

// Add a visual indicator in console for debugging
const logThemeChange = () => {
  console.log(`Theme toggled to: ${isDark.value ? 'DARK' : 'LIGHT'}`)
  console.log(`HTML class:`, document.documentElement.classList.contains('dark') ? 'dark' : 'light')
  console.log(`LocalStorage theme:`, localStorage.getItem('theme'))
}

const handleToggle = () => {
  toggleTheme()
  logThemeChange()
}
</script>