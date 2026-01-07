import { LocalizationRepository } from '../repositories/LocalizationRepository'
import type { Language } from '~/api/types/api.types'

export class LocalizationService {
  private repository: LocalizationRepository

  constructor() {
    this.repository = new LocalizationRepository()
  }

  async setLocale(locale: string): Promise<{ success: boolean; message?: string }> {
    try {
      const response = await this.repository.setLocale(locale)
      
      if (response.success) {
        // Update client-side storage
        if (process.client) {
          localStorage.setItem('locale', locale)
          useCookie('locale').value = locale
        }
        return { success: true }
      }
      
      return { success: false, message: response.message || 'Failed to set locale' }
    } catch (error) {
      return { success: false, message: 'An error occurred while setting locale' }
    }
  }

  async getLanguages(): Promise<{ success: boolean; data?: Record<string, Language>; message?: string }> {
    try {
      const response = await this.repository.getLanguages()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch languages' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching languages' }
    }
  }

  async getCurrentLocale(): Promise<{ success: boolean; data?: { locale: string }; message?: string }> {
    try {
      const response = await this.repository.getCurrentLocale()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch current locale' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching current locale' }
    }
  }

  async getUiTranslations(): Promise<{ success: boolean; data?: Record<string, string>; message?: string }> {
    try {
      const response = await this.repository.getUiTranslations()
      
      if (response.success && response.data) {
        return { success: true, data: response.data }
      }
      
      return { success: false, message: response.message || 'Failed to fetch UI translations' }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching UI translations' }
    }
  }

  // Business logic methods
  async getActiveLanguages(): Promise<{ success: boolean; data?: Language[]; message?: string }> {
    try {
      const languagesResult = await this.getLanguages()
      
      if (!languagesResult.success || !languagesResult.data) {
        return languagesResult
      }

      const activeLanguages = Object.values(languagesResult.data).filter(
        (language: Language) => language.is_active
      )

      return { success: true, data: activeLanguages }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching active languages' }
    }
  }

  async getDefaultLanguage(): Promise<{ success: boolean; data?: Language; message?: string }> {
    try {
      const languagesResult = await this.getLanguages()
      
      if (!languagesResult.success || !languagesResult.data) {
        return languagesResult
      }

      const defaultLanguage = Object.values(languagesResult.data).find(
        (language: Language) => language.is_default
      )

      return { 
        success: true, 
        data: defaultLanguage,
        message: defaultLanguage ? undefined : 'No default language found'
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching default language' }
    }
  }

  async getLanguageByCode(code: string): Promise<{ success: boolean; data?: Language; message?: string }> {
    try {
      const languagesResult = await this.getLanguages()
      
      if (!languagesResult.success || !languagesResult.data) {
        return languagesResult
      }

      const language = languagesResult.data[code]
      
      return { 
        success: true, 
        data: language,
        message: language ? undefined : `Language with code '${code}' not found`
      }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching language by code' }
    }
  }

  async getSortedLanguages(): Promise<{ success: boolean; data?: Language[]; message?: string }> {
    try {
      const languagesResult = await this.getLanguages()
      
      if (!languagesResult.success || !languagesResult.data) {
        return languagesResult
      }

      const languages = Object.values(languagesResult.data)
      const sortedLanguages = languages.sort((a: Language, b: Language) => 
        a.sort_order - b.sort_order
      )

      return { success: true, data: sortedLanguages }
    } catch (error) {
      return { success: false, message: 'An error occurred while fetching sorted languages' }
    }
  }

  async getBrowserLocale(): Promise<string> {
    if (process.client) {
      return navigator.language || 'en-US'
    }
    return 'en-US'
  }

  async getStoredLocale(): Promise<string | null> {
    if (process.client) {
      return localStorage.getItem('locale')
    }
    return null
  }

  async getPreferredLocale(): Promise<string> {
    try {
      // 1. Check stored locale
      const storedLocale = await this.getStoredLocale()
      if (storedLocale) {
        return storedLocale
      }

      // 2. Check browser locale
      const browserLocale = await this.getBrowserLocale()
      const browserLangCode = browserLocale.split('-')[0]

      // 3. Check if browser locale is available
      const languagesResult = await this.getActiveLanguages()
      if (languagesResult.success && languagesResult.data) {
        const availableLanguages = languagesResult.data
        const browserLangMatch = availableLanguages.find(
          lang => lang.code === browserLangCode || lang.code === browserLocale
        )
        
        if (browserLangMatch) {
          return browserLangMatch.code
        }
      }

      // 4. Fallback to default language
      const defaultLangResult = await this.getDefaultLanguage()
      if (defaultLangResult.success && defaultLangResult.data) {
        return defaultLangResult.data.code
      }

      // 5. Ultimate fallback
      return 'en'
    } catch (error) {
      return 'en'
    }
  }

  async initializeLocale(): Promise<{ success: boolean; locale?: string; message?: string }> {
    try {
      const preferredLocale = await this.getPreferredLocale()
      const result = await this.setLocale(preferredLocale)
      
      if (result.success) {
        return { success: true, locale: preferredLocale }
      }
      
      return { success: false, message: result.message }
    } catch (error) {
      return { success: false, message: 'An error occurred while initializing locale' }
    }
  }
}
