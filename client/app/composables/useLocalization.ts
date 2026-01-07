import { services } from '~/services'

export const useLocalization = () => {
  const currentLocale = ref('en')
  const availableLocales = ref<Record<string, any>>({})
  const availableLanguages = ref<any[]>([])
  const translations = ref<Record<string, string>>({})
  
  const initialize = () => {
    if (process.client) {
      const savedLocale = localStorage.getItem('locale') || 'en'
      currentLocale.value = savedLocale
      useCookie('locale').value = savedLocale
    }
  }
  
  const setLocale = async (locale: string) => {
    try {
      const result = await services.localization.setLocale(locale)
      
      if (result.success) {
        currentLocale.value = locale
        
        if (process.client) {
          localStorage.setItem('locale', locale)
          useCookie('locale').value = locale
          window.location.reload()
        }

        await fetchTranslations()
        
        return { success: true }
      }
      
      return { success: false, message: result.message }
    } catch (error) {
      return { success: false, message: 'Failed to change language' }
    }
  }
  
  const fetchTranslations = async () => {
    try {
      console.log('Fetching translations...')
      const result = await services.localization.getUiTranslations()
      if (result.success && result.data) {
        translations.value = result.data
        return { success: true }
      }
      return { success: false, message: result.message }
    } catch (error) {
      console.error('Failed to fetch translations:', error)
      return { success: false, message: 'Failed to fetch translations' }
    }
  }
  
  const fetchAvailableLocales = async () => {
    try {
      const result = await services.localization.getLanguages()
      if (result.success && result.data) {
        availableLocales.value = result.data
        availableLanguages.value = Object.values(result.data)
          .filter((lang: any) => lang.is_active)
          .sort((a: any, b: any) => a.sort_order - b.sort_order)
        return { success: true }
      }
      return { success: false, message: result.message }
    } catch (error) {
      console.error('Failed to fetch locales:', error)
      return { success: false, message: 'Failed to fetch locales' }
    }
  }
  
  const translate = (key: string, replacements: Record<string, string> = {}): string => {
    let translation = translations.value[key] || key
    
    Object.entries(replacements).forEach(([placeholder, value]) => {
      translation = translation.replace(`:${placeholder}`, value)
    })
    
    return translation
  }
  
  const getLanguageName = (code: string): string => {
    const lang = availableLanguages.value.find(l => l.code === code)
    return lang ? lang.name : code.toUpperCase()
  }
  
  const getLanguageIcon = (code: string): string => {
    const flags: Record<string, string> = {
      'en': '🇺🇸',
      'es': '🇪🇸',
      'fr': '🇫🇷',
      'de': '🇩🇪',
      'it': '🇮🇹',
      'pt': '🇵🇹',
      'ru': '🇷🇺',
      'zh': '🇨🇳',
      'ja': '🇯🇵',
      'ko': '🇰🇷',
      'ar': '🇸🇦',
      'hi': '🇮🇳',
      'bn': '🇧🇩',
      'fa': '🇮🇷',
      'he': '🇮🇱',
      'id': '🇮🇩',
      'nl': '🇳🇱',
      'pl': '🇵🇱',
      'sv': '🇸🇪',
      'tr': '🇹🇷',
      'uk': '🇺🇦',
      'ur': '🇵🇰',
      'vi': '🇻🇳',
      'zh-CN': '🇨🇳',
      'zh-TW': '🇹🇼',
      'cs': '🇨🇿',
      'el': '🇬🇷',
      'hu': '🇭🇺',
      'ro': '🇷🇴',
      'sr': '🇷🇸',
      'bg': '🇧🇬',
      'da': '🇩🇰',
      'fi': '🇫🇮',
      'no': '🇳🇴',
      'sk': '🇸🇰',
      'sl': '🇸🇮',
    }
    return flags[code] || '🌐'
  }
  
  // Initialize
  if (process.client) {
    initialize()
  }
  
  return {
    currentLocale,
    availableLocales,
    availableLanguages,
    translations,
    setLocale,
    fetchTranslations,
    fetchAvailableLocales,
    translate,
    getLanguageName,
    getLanguageIcon,
    initialize
  }
}