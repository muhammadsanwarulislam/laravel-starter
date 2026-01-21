import type { ApiResponse } from '~/api/types/api.types'

export abstract class BaseRepository {
  protected baseUrl: string
  
  constructor(baseUrl?: string) {
    const config = useRuntimeConfig()
    this.baseUrl = baseUrl || config.public.apiBaseUrl
  }

  protected async request<T>(
    endpoint: string,
    options: any = {}
  ): Promise<ApiResponse<T>> {
    const headers = this.buildHeaders(options.headers)
    
    try {
      const response = await $fetch<ApiResponse<T>>(`${this.baseUrl}${endpoint}`, {
        ...options,
        headers
      })
      
      return response
    } catch (error: any) {
      return this.handleError(error)
    }
  }

  protected buildHeaders(customHeaders?: any): Record<string, string> {
    const headers: Record<string, string> = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(customHeaders || {})
    }

    // Add auth token
    if (process.client) {
      const token = localStorage.getItem('auth_token') ?? localStorage.getItem('otp_token')
      if (token) {
        headers['Authorization'] = `Bearer ${token}`
      }
    }

    // Add locale
    const locale = useCookie('locale').value
    if (locale) {
      headers['X-Locale'] = locale
    }

    return headers
  }

  protected handleError(error: any): ApiResponse {
    // Handle 401 Unauthorized
    if (error?.response?.status === 401) {
      // We'll handle this in the composable
      console.error('Authentication error:', error)
    }

    return {
      success: false,
      message: error.data?.message || error.message || 'An error occurred',
      errors: error.data?.errors
    }
  }

  protected async get<T>(endpoint: string, params?: any): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint, {
      method: 'GET',
      params
    })
  }

  protected async post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint, {
      method: 'POST',
      body: data
    })
  }

  protected async put<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint, {
      method: 'PUT',
      body: data
    })
  }

  protected async delete<T>(endpoint: string): Promise<ApiResponse<T>> {
    return this.request<T>(endpoint, {
      method: 'DELETE'
    })
  }
}