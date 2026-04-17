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
  first_page_url?: string
  last_page_url?: string
  next_page_url?: string | null
  prev_page_url?: string | null
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
  country_code_id?: number | null
  phone?: string
  status: boolean
  ui_locale?: string
  avatar_url?: string | null
  email_verified_at?: string
  created_at: string
  updated_at: string
  roles?: Role[]
  profile?: Profile
  files?: FileManager[]
}

export interface Role {
  id: number
  name: string
  slug: string
  description?: string
  is_system: boolean
  level: number
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
  gender?: 'male' | 'female' | 'other'
  type?: 'student' | 'teacher' | 'admin'
  address?: string
  created_at: string
  updated_at: string
}

export interface UserProfileResponse {
  user: User
  permissions: string[]
}

export interface FileManager {
  id: number
  uuid: string
  name: string
  file: string
  type: string
  size: string
  path: string
  created_at: string
  updated_at: string
}

// DTOs
export interface LoginCredentials {
  email?: string;
  phone?: string;
  password?: string;
  locale?: string;
}

export interface RegisterData {
  name: string
  email?: string
  country_code_id: number
  password_confirmation: string
  accepted_terms: boolean
  password: string
  phone: string
  locale?: string
}

export interface UpdateUserData {
  name?: string
  email?: string
  country_code_id?: number | null
  phone?: string
  status?: boolean
  ui_locale?: string
  gender?: 'male' | 'female' | 'other' | ''
  type?: 'student' | 'teacher' | 'admin' | ''
  address?: string
}

export interface ChangePasswordData {
  current_password: string
  password: string
  password_confirmation: string
}

export interface CreateUserData extends UpdateUserData {
  password: string
}

export interface CreateRoleData {
  name: string
  description?: string
  level: number
  permissions?: number[]
}

export interface CreatePermissionData {
  name: string
  module: string
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
  identifier: string;
  identifier_type?: 'email' | 'phone';
  expires_at: string;
  token_type: string;
  token: string;
}

export interface AuthenticatedSessionResponse {
  user: User
  token: string
  token_type: string
  locale?: string
}

export interface OtpSessionData {
  token: string | null
  identifier: string | null
  identifier_type: 'email' | 'phone' | null
}

export interface RegisterResponse {
  user: User
  token: string
  token_type: string
  locale?: string
}

export interface AuthUserResponse {
  user: User
  permissions: string[]
  locale: string
  locales: Record<string, Language>
}
