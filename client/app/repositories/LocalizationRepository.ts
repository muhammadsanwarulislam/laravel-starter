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

  async setLocale(locale: string): Promise<ApiResponse<void>> {
    return this.post<void>('/localization/locale/set', { locale })
  }
}