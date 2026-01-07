export const useTheme = () => {
  const theme = ref<'light' | 'dark'>('light')
  const isDark = ref(false)

  const initTheme = () => {
    if (process.client) {
      // Check localStorage first
      const savedTheme = localStorage.getItem('theme') as 'light' | 'dark'
      
      if (savedTheme) {
        theme.value = savedTheme
        console.log(`📁 Loaded theme from localStorage: ${savedTheme}`)
      } else {
        // Check system preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        theme.value = prefersDark ? 'dark' : 'light'
        console.log(`🌐 Using system preference: ${prefersDark ? 'dark' : 'light'}`)
      }
      
      updateTheme()
    }
  }

  const updateTheme = () => {
    if (process.client) {
      const newIsDark = theme.value === 'dark'
      
      // Only update if changed
      if (isDark.value !== newIsDark) {
        isDark.value = newIsDark
        
        // Update HTML class
        const htmlEl = document.documentElement
        if (newIsDark) {
          htmlEl.classList.add('dark')
          console.log('✨ Added "dark" class to html')
        } else {
          htmlEl.classList.remove('dark')
          console.log('✨ Removed "dark" class from html')
        }
        
        // Store in localStorage
        localStorage.setItem('theme', theme.value)
        console.log(`💾 Saved theme to localStorage: ${theme.value}`)
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('theme-change', { 
          detail: { theme: theme.value, isDark: newIsDark }
        }))
      }
    }
  }

  const setTheme = (newTheme: 'light' | 'dark') => {
    console.log(`🔄 Setting theme to: ${newTheme}`)
    theme.value = newTheme
    updateTheme()
  }

  const toggleTheme = () => {
    console.log(`🔄 Toggling theme from: ${theme.value}`)
    setTheme(theme.value === 'dark' ? 'light' : 'dark')
  }

  onMounted(() => {
    initTheme()
    
    // Listen for system theme changes
    if (process.client) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      
      const handleSystemThemeChange = (e: MediaQueryListEvent) => {
        // Only update if no theme is saved in localStorage
        if (!localStorage.getItem('theme')) {
          console.log(`🔄 System theme changed to: ${e.matches ? 'dark' : 'light'}`)
          theme.value = e.matches ? 'dark' : 'light'
          updateTheme()
        }
      }
      
      mediaQuery.addEventListener('change', handleSystemThemeChange)
      
      // Cleanup
      onUnmounted(() => {
        mediaQuery.removeEventListener('change', handleSystemThemeChange)
      })
    }
  })

  const themeClasses = computed(() => {
    return isDark.value ? 'dark' : ''
  })

  // For debugging
  const debugInfo = computed(() => ({
    theme: theme.value,
    isDark: isDark.value,
    htmlClass: process.client ? (document.documentElement.classList.contains('dark') ? 'dark' : 'light') : 'server',
    localStorage: process.client ? (localStorage.getItem('theme') || 'not set') : 'server'
  }))

  return {
    theme,
    isDark,
    setTheme,
    toggleTheme,
    themeClasses,
    debugInfo // Optional, for debugging
  }
}