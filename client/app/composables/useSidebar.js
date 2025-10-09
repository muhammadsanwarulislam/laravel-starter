import { ref, watch, onMounted } from 'vue'

export const useSidebar = () => {
  const isExpanded = useState("isExpanded", () => true);
  const isMobileOpen = useState("isMobileOpen", () => false);
  const isMobile = useState("isMobile", () => false);
  const isHovered = useState("isHovered", () => false);
  const activeItem = useState("activeItem", () => null);
  const openSubmenu = useState("openSubmenu", () => null);
  const fontSize = useState("fontSize", () => 16)
  const themeColor = useState("themeColor", () => 'blue') 
  const notificationSound = useState("notificationSound", () => true)
  const showServerTime = useState("showServerTime", () => true)
  const loginTime = useState("loginTime", () => new Date())

  // Save state to localStorage
  const saveState = () => {
    localStorage.setItem('isExpanded', JSON.stringify(isExpanded.value));
    localStorage.setItem('isMobileOpen', JSON.stringify(isMobileOpen.value));
    localStorage.setItem('isMobile', JSON.stringify(isMobile.value));
    localStorage.setItem('isHovered', JSON.stringify(isHovered.value));
    localStorage.setItem('activeItem', JSON.stringify(activeItem.value));
    localStorage.setItem('openSubmenu', JSON.stringify(openSubmenu.value));
    localStorage.setItem('fontSize', JSON.stringify(fontSize.value))
    localStorage.setItem('themeColor', JSON.stringify(themeColor.value))
    localStorage.setItem('notificationSound', JSON.stringify(notificationSound.value))
    localStorage.setItem('showServerTime', JSON.stringify(showServerTime.value))
    localStorage.setItem('loginTime', JSON.stringify(loginTime.value))
  };
  
  // Watch for changes and save to localStorage
  watch([isExpanded, isMobileOpen, isMobile, isHovered, activeItem, openSubmenu, fontSize, themeColor, notificationSound, showServerTime, loginTime], () => {
    saveState()
    applyFontSize()
    applyThemeColor()
  });
  
  const applyFontSize = () => {
    document.documentElement.style.fontSize = `${fontSize.value}px`
  }
  
  const applyThemeColor = () => {    
    const selected = themeColor.value
    const palette = colorPalette[selected.toLowerCase()] || colorPalette['blue']
    Object.entries(palette).forEach(([key, value]) => {
      document.documentElement.style.setProperty(`--theme-${key}`, value)
    })
    document.documentElement.style.setProperty('--theme-color', palette['500'])
  }

  const handleResize = () => {
    isMobile.value = window.innerWidth < 768;
    if (!isMobile.value) {
      isMobileOpen.value = false;
    }
    saveState();
  };

  onMounted(() => {
    isExpanded.value = ref(JSON.parse(localStorage.getItem('isExpanded')) ?? true);
    isMobileOpen.value = ref(JSON.parse(localStorage.getItem('isMobileOpen')) ?? false);
    isMobile.value = ref(JSON.parse(localStorage.getItem('isMobile')) ?? false);
    isHovered.value = ref(JSON.parse(localStorage.getItem('isHovered')) ?? false);
    activeItem.value = ref(JSON.parse(localStorage.getItem('activeItem')) ?? null);
    openSubmenu.value = ref(JSON.parse(localStorage.getItem('openSubmenu')) ?? null);
    fontSize.value = ref(JSON.parse(localStorage.getItem('fontSize')) ?? 14)
    themeColor.value = ref(JSON.parse(localStorage.getItem('themeColor')) ?? 'blue')
    notificationSound.value = ref(JSON.parse(localStorage.getItem('notificationSound')) ?? true)
    showServerTime.value = ref(JSON.parse(localStorage.getItem('showServerTime')) ?? true)
    loginTime.value = ref(JSON.parse(localStorage.getItem('loginTime')) ?? new Date())

    handleResize()
    applyFontSize()
    applyThemeColor()
    window.addEventListener("resize", handleResize);
  });

  onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
  });

  const toggleSidebar = () => {
    if (isMobile.value) {
      isMobileOpen.value = !isMobileOpen.value;
    } else {
      isExpanded.value = !isExpanded.value;
    }
    saveState();
  };

  const toggleMobileSidebar = () => {
    isMobileOpen.value = !isMobileOpen.value;
  };

  const setIsHovered = (value) => {
    isHovered.value = value;
    saveState();
  };

  const setActiveItem = (item) => {
    activeItem.value = item;
    saveState();
  };

  const toggleSubmenu = (item) => {
    openSubmenu.value = openSubmenu.value === item ? null : item;
    saveState();
  };
  
  const colorPalette = {
    blue: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a'},
    red: { 50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d'},
    green: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d'},
    purple: { 50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6', 900: '#4c1d95'},
    orange: { 50:  '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74', 400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c', 800: '#9a3412', 900: '#7c2d12'},
    yellow: { 50: '#fefce8', 100: '#fef9c3', 200: '#fef08a', 300: '#fde047', 400: '#facc15', 500: '#eab308', 600: '#ca8a04', 700: '#a16207', 800: '#854d0e', 900: '#713f12'},
    pink: { 50: '#fdf2f8', 100: '#fce7f3', 200: '#fbcfe8', 300: '#f9a8d4', 400: '#f472b6', 500: '#ec4899', 600: '#db2777', 700: '#be185d', 800: '#9d174d', 900: '#831843'},
    teal: { 50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4', 300: '#5eead4', 400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e', 800: '#115e59', 900: '#134e4a'},
    gray: { 50: '#f9fafb', 100: '#f3f4f6', 200: '#e5e7eb', 300: '#d1d5db', 400: '#9ca3af', 500: '#6b7280', 600: '#4b5563', 700: '#374151', 800: '#1f2937', 900: '#111827'},
    mint: { 50: '#f4fefc', 100: '#dffcf5', 200: '#c0f9ec', 300: '#99f0de', 400: '#6ee3cd', 500: '#3dd6bb', 600: '#1bb9a0', 700: '#109383', 800: '#0d7268', 900: '#0b5a54'},
    bronze: { 50: '#fdfaf5', 100: '#f5eee3', 200: '#e6d6c0', 300: '#d4b28f', 400: '#c19063', 500: '#a86f3d', 600: '#8a552c', 700: '#6c4121', 800: '#52321a', 900: '#3e2715'},
    indigo: { 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81'}
  }

  return {
    isExpanded: computed(() => (isMobile.value ? false : isExpanded.value)),
    isMobile,
    isMobileOpen,
    isHovered,
    activeItem,
    openSubmenu,
    toggleSidebar,
    toggleMobileSidebar,
    setIsHovered,
    setActiveItem,
    toggleSubmenu,
    fontSize,
    themeColor,
    notificationSound,
    showServerTime,
    loginTime
  };
};