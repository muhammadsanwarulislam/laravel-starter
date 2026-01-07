export interface ApiResponse<T = any> {
  success: boolean
  message?: string
  data?: T
  errors?: Record<string, string[]>
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
  links: PaginationLink[]
}

export interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

// Entity types
export interface User {
  id: number
  name: string
  email: string
  phone?: string
  status: boolean
  ui_locale?: string
  email_verified_at?: string
  created_at: string
  updated_at: string
  roles?: Role[]
  profile?: Profile
}

export interface Role {
  id: number
  name: string
  slug: string
  description?: string
  permissions?: Permission[]
  created_at: string
  updated_at: string
}

export interface Permission {
  id: number
  name: string
  slug: string
  module: string
  description?: string
  created_at: string
  updated_at: string
}

export interface Language {
  code: string
  name: string
  native_name: string
  is_default: boolean
  is_active: boolean
  sort_order: number
}

export interface Profile {
  id: number
  user_id: number
  avatar?: string
  bio?: string
  company?: string
  job_title?: string
  created_at: string
  updated_at: string
}

// DTOs
export interface LoginCredentials {
  email: string
  password: string
  locale?: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  phone?: string
  locale?: string
}

export interface UpdateUserData {
  name?: string
  email?: string
  phone?: string
  status?: boolean
  ui_locale?: string
}

export interface CreateUserData extends UpdateUserData {
  password: string
}

export interface CreateRoleData {
  name: string
  slug: string
  description?: string
}

export interface MenuItem {
  id: string
  title: string
  icon: string
  to: string
  permissions: string[]
  roles: string[]
  isActive: boolean
  badge?: number | string | null
  children?: MenuItem[]
}

export interface MenuSection {
  title: string
  items: MenuItem[]
}
// Response types
export interface LoginResponse {
  user: User
  token: string
  token_type: string
  locale: string
}

export interface RegisterResponse {
  user: User
  token: string
  token_type: string
}

export interface AuthUserResponse {
  user: User
  permissions: string[]
  locale: string
  locales: Record<string, Language>
}