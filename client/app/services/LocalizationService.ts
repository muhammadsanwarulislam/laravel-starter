import { LocalizationRepository } from '../repositories/LocalizationRepository'
import type { Language } from '~/api/types/api.types'

export class LocalizationService {
  private repository: LocalizationRepository

  constructor() {
    this.repository = new LocalizationRepository()
  }

  async setLocale(locale: string): Promise<{ success: boolean; message?: string; data?: { locale: string; translations: Record<string, string> } }> {
    try {
      const response = await this.repository.setLocale(locale)
      
      if (response.success) {
        return { success: true, data: response.data }
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

  async getCurrentLocale(): Promise<{ success: boolean; data?: { locale: string; locales?: Record<string, Language> }; message?: string }> {
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

  async storeUiTranslation(key: string, value: string, locale: string, group: string = 'ui'): Promise<{ success: boolean; data?: any; message?: string }> {
  try {
    const response = await this.repository.storeUiTranslation(key, value, locale, group);
    if (response.success) {
      return { success: true, data: response.data };
    }
    return { success: false, message: response.message };
  } catch (error) {
    return { success: false, message: 'An error occurred while updating translation' };
  }
}

 async deleteTranslation(id: number): Promise<{ success: boolean; message?: string }> {
    try {
      const response = await this.repository.deleteTranslation(id);
      if (response.success) {
        return { success: true };
      }
      return { success: false, message: response.message };
    } catch (error) {
      return { success: false, message: 'An error occurred while deleting translation' };
    }
  }
}
