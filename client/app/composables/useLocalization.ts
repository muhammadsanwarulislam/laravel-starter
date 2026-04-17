import { services } from '~/services'

export const useLocalization = () => {
  const currentLocale = useState<string>('current-locale', () => 'en')
  const allLanguages = useState<any[]>('all-languages', () => [])
  const availableLanguages = useState<any[]>('available-languages', () => [])
  const translations = useState<Record<string, string>>('ui-translations', () => ({}))
  const initialized = useState<boolean>('localization-initialized', () => false)
  const isLoading = useState<boolean>('localization-loading', () => false)
  const error = useState<string | null>('localization-error', () => null)

  const initialize = async () => {
    if (initialized.value) return

    if (typeof window !== 'undefined') {
      const savedLocale = localStorage.getItem('locale') || 'en'
      currentLocale.value = savedLocale
      useCookie('locale').value = savedLocale
    }

    await fetchAllData()
    initialized.value = true
  }

  const fetchAllData = async () => {
    isLoading.value = true
    error.value = null
    try {
      await Promise.all([fetchAvailableLocales(), fetchTranslations()])
    } catch (err: any) {
      error.value = err.message || 'Failed to load localization data'
    } finally {
      isLoading.value = false
    }
  }

  const fetchAvailableLocales = async () => {
    try {
      const result = await services.localization.getLanguages()
      if (result.success && result.data) {
        const langArray = Object.values(result.data) as any[]
        // Store all languages
        allLanguages.value = langArray
        // Store only active, sorted by sort_order
        availableLanguages.value = langArray
          .filter(lang => lang.is_active)
          .sort((a, b) => a.sort_order - b.sort_order)
        return { success: true }
      }
      throw new Error(result.message || 'Failed to fetch languages')
    } catch (err: any) {
      error.value = err.message
      return { success: false, message: err.message }
    }
  }

  const fetchTranslations = async () => {
    try {
      const result = await services.localization.getUiTranslations()
      if (result.success && result.data) {
        translations.value = result.data
        return { success: true }
      }
      throw new Error(result.message || 'Failed to fetch translations')
    } catch (err: any) {
      error.value = err.message
      return { success: false, message: err.message }
    }
  }

  const refreshTranslations = async () => {
    return fetchTranslations()
  }

  const setLocale = async (locale: string) => {
    isLoading.value = true
    error.value = null
    try {
      const result = await services.localization.setLocale(locale)
      if (result.success) {
        currentLocale.value = locale
        if (typeof window !== 'undefined') {
          localStorage.setItem('locale', locale)
          useCookie('locale').value = locale
        }
        if (result.data?.translations) {
          translations.value = result.data.translations
        } else {
          await fetchTranslations()
        }
        return { success: true }
      }
      throw new Error(result.message || 'Failed to set locale')
    } catch (err: any) {
      error.value = err.message
      return { success: false, message: err.message }
    } finally {
      isLoading.value = false
    }
  }

  const translate = (key: string, replacements: Record<string, string> = {}): string => {
    let translation = translations.value[key] || key
    Object.entries(replacements).forEach(([placeholder, value]) => {
      translation = translation.replace(`:${placeholder}`, value)
    })
    return translation
  }

  const t = translate

  const getLanguageName = (code: string): string => {
    const lang = availableLanguages.value.find(l => l.code === code)
    return lang ? lang.name : code.toUpperCase()
  }

  const getLanguageIcon = (code: string): string => {
    const flags: Record<string, string> = {
      en: '🇺🇸', es: '🇪🇸', fr: '🇫🇷', de: '🇩🇪', it: '🇮🇹', pt: '🇵🇹', ru: '🇷🇺',
      zh: '🇨🇳', ja: '🇯🇵', ko: '🇰🇷', ar: '🇸🇦', hi: '🇮🇳', bn: '🇧🇩', fa: '🇮🇷',
      he: '🇮🇱', id: '🇮🇩', nl: '🇳🇱', pl: '🇵🇱', sv: '🇸🇪', tr: '🇹🇷', uk: '🇺🇦',
      ur: '🇵🇰', vi: '🇻🇳', 'zh-CN': '🇨🇳', 'zh-TW': '🇹🇼', cs: '🇨🇿', el: '🇬🇷',
      hu: '🇭🇺', ro: '🇷🇴', sr: '🇷🇸', bg: '🇧🇬', da: '🇩🇰', fi: '🇫🇮', no: '🇳🇴',
      sk: '🇸🇰', sl: '🇸🇮',
    }
    return flags[code] || '🌐'
  }
  const storeTranslation = async (key: string, value: string, locale?: string): Promise<{ success: boolean; message?: string }> => {
    const targetLocale = locale || currentLocale.value;
    isLoading.value = true;
    error.value = null;
    try {
      const result = await services.localization.storeUiTranslation(key, value, targetLocale);
      if (result.success) {
        // Update local translations state
        translations.value = { ...translations.value, [key]: value };
        return { success: true };
      } else {
        error.value = result.message || 'Failed to update translation';
        return { success: false, message: error.value };
      }
    } catch (err: any) {
      error.value = err.message;
      return { success: false, message: err.message };
    } finally {
      isLoading.value = false;
    }
  };

  const deleteTranslation = async (id: number): Promise<{ success: boolean; message?: string }> => {
    isLoading.value = true;
    error.value = null;
    try {
      const result = await services.localization.deleteTranslation(id);
      if (result.success) {
        // Optionally, you could remove the translation from the local state here
        return { success: true };
      } else {
        error.value = result.message || 'Failed to delete translation';
        return { success: false, message: error.value };
      }
    } catch (err: any) {
      error.value = err.message;
      return { success: false, message: err.message };
    } finally {
      isLoading.value = false;
    }
  };

  return {
    // State
    currentLocale,
    allLanguages,
    availableLanguages,
    translations,
    isLoading,
    error,
    initialized,
    // Methods
    initialize,
    setLocale,
    fetchTranslations,
    refreshTranslations,
    fetchAvailableLocales,
    translate,
    t,
    getLanguageName,
    getLanguageIcon,
    storeTranslation,
    deleteTranslation,
  }
}