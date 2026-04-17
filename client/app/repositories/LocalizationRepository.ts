import { BaseRepository } from './BaseRepository'
import type { Language, ApiResponse } from '~/api/types/api.types'

export class LocalizationRepository extends BaseRepository {
  constructor() {
    super()
  }

  async getLanguages(): Promise<ApiResponse<Record<string, Language>>> {
    return this.get<Record<string, Language>>('/languages')
  }

  async getCurrentLocale(): Promise<ApiResponse<{ locale: string }>> {
    return this.get<{ locale: string }>('/locale/current')
  }

  async getUiTranslations(): Promise<ApiResponse<Record<string, string>>> {
    return this.get<Record<string, string>>('/translations/ui')
  }

  async storeUiTranslation(key: string, value: string, locale: string, group: string = 'ui'): Promise<{ success: boolean; data?: any; message?: string }> {
    return this.post('/localization/translations/ui', { key, value, locale, group });
}

  async setLocale(locale: string): Promise<ApiResponse<{ locale: string; translations: Record<string, string> }>> {
    return this.post<{ locale: string; translations: Record<string, string> }>('/locale/set', { locale })
  }

  async createLanguage(data: any): Promise<ApiResponse<Language>> {
    return this.post<Language>('/languages', data)
  }

  async updateLanguage(id: number, data: any): Promise<ApiResponse<Language>> {
    return this.put<Language>(`/languages/${id}`, data)
  }

  async deleteLanguage(id: number): Promise<ApiResponse<void>> {
    return this.delete<void>(`/languages/${id}`)
  }

  async deleteTranslation(id: number): Promise<ApiResponse<void>> {
    return this.delete<void>(`/localization/translations/ui/${id}`)
  }
}
