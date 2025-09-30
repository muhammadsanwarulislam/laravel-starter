import { ref, watch, onMounted, onBeforeUnmount } from 'vue'

export default function useDarkMode() {
  const themeMode = ref('system') 
  const isDark = ref(false)
  let mediaQuery = null

  const applyTheme = () => {
    if (themeMode.value === 'system') {
      isDark.value = mediaQuery?.matches ?? false
    } else {
      isDark.value = themeMode.value === 'dark'
    }

    document.documentElement.classList.toggle('dark', isDark.value)
  }

  const toggleTheme = (mode) => {
    themeMode.value = mode
  }

  onMounted(() => {
    const saved = localStorage.getItem('themeMode')
    if (saved) themeMode.value = saved

    // Safe access to matchMedia
    if (typeof window !== 'undefined' && 'matchMedia' in window) {
      mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      mediaQuery.addEventListener('change', applyTheme)
    }

    applyTheme()
  })

  onBeforeUnmount(() => {
    mediaQuery?.removeEventListener?.('change', applyTheme)
  })

  watch(themeMode, (newMode) => {
    localStorage.setItem('themeMode', newMode)
    applyTheme()
  })

  return {
    themeMode,
    isDark,
    toggleTheme
  }
}