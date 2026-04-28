<template>
  <div class="p-6">
    <SharedPageHeader title="My Profile"
      description="Review your account details, update your photo, and keep your password secure." />

    <div v-if="loadingProfile" class="flex justify-center py-10">
      <UILoadingSpinner message="Loading" />
    </div>

    <div v-else class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
      <div class="space-y-6">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
              <p class="mt-1 text-sm text-gray-500">Upload a clear square photo for your account menu and profile.</p>
            </div>

            <div class="flex items-center gap-4">
              <div
                class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xl font-semibold text-white">
                <img v-if="displayAvatarUrl" :src="displayAvatarUrl" alt="Profile photo"
                  class="h-full w-full object-cover" />
                <span v-else>{{ initials }}</span>
              </div>

              <div class="space-y-2">
                <input ref="photoInput" type="file" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden"
                  @change="handlePhotoSelected" />
                <button type="button"
                  class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                  @click="photoInput?.click()">
                  Choose Photo
                </button>
                <p class="text-xs text-gray-500">PNG, JPG, or WebP up to 5MB.</p>
              </div>
            </div>
          </div>

          <p v-if="photoError" class="mt-4 text-sm text-red-600">{{ photoError }}</p>
        </section>

        <form @submit.prevent="submitProfile"
          class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
            <p class="mt-1 text-sm text-gray-500">Keep your personal details and contact information up to date.</p>
          </div>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
              <label for="profile-name" class="mb-2 block text-sm font-medium text-gray-700">Full Name</label>
              <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <UIIconsUser class="h-5 w-5 text-gray-400" />
                </div>
                <input id="profile-name" v-model="form.name" type="text" :class="inputClass(errors.name)" class="pl-10"
                  placeholder="John Doe" />
              </div>
              <p v-if="errors.name" class="mt-2 flex items-center text-xs text-red-600">
                <UIIconsExclamation2 class="mr-1 h-4 w-4 text-red-600" />
                {{ errors.name }}
              </p>
            </div>

            <div>
              <label for="profile-email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
              <input id="profile-email" v-model="form.email" type="email" :class="inputClass(errors.email)"
                placeholder="you@example.com" />
              <p v-if="errors.email" class="mt-2 text-xs text-red-600">{{ errors.email }}</p>
            </div>

            <div>
              <label for="profile-locale" class="mb-2 block text-sm font-medium text-gray-700">Preferred
                Language</label>
              <select id="profile-locale" v-model="form.ui_locale" :class="inputClass(errors.ui_locale)">
                <option value="">System Default</option>
                <option v-for="language in languages" :key="language.code" :value="language.code">
                  {{ language.name }}
                </option>
              </select>
              <p v-if="errors.ui_locale" class="mt-2 text-xs text-red-600">{{ errors.ui_locale }}</p>
            </div>

            <div>
              <label for="profile-country-code" class="mb-2 block text-sm font-medium text-gray-700">Country
                Code</label>
              <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <UIIconsPhone class="h-5 w-5 text-gray-400" />
                </div>
                <select id="profile-country-code" v-model.number="form.country_code_id"
                  :class="inputClass(errors.country_code_id)" class="appearance-none pl-10">
                  <option :value="null">Select country code</option>
                  <option v-for="country in countries" :key="country.id" :value="country.id">
                    {{ country.dial_code }} {{ country.name }}
                  </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                  <UIIconsChevronDown class="h-5 w-5 text-gray-400" />
                </div>
              </div>
              <p v-if="errors.country_code_id" class="mt-2 text-xs text-red-600">{{ errors.country_code_id }}</p>
            </div>

            <div>
              <label for="profile-phone" class="mb-2 block text-sm font-medium text-gray-700">Phone Number</label>
              <input id="profile-phone" v-model="form.phone" type="tel" :class="inputClass(errors.phone)"
                placeholder="01XXXXXXXXX" />
              <p v-if="errors.phone" class="mt-2 text-xs text-red-600">{{ errors.phone }}</p>
            </div>
          </div>

          <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900">Profile Details</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <label for="profile-gender" class="mb-2 block text-sm font-medium text-gray-700">Gender</label>
                <select id="profile-gender" v-model="form.gender" :class="inputClass(errors.gender)">
                  <option value="">Select gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
                <p v-if="errors.gender" class="mt-2 text-xs text-red-600">{{ errors.gender }}</p>
              </div>

              <div>
                <label for="profile-type" class="mb-2 block text-sm font-medium text-gray-700">Profile Type</label>
                <select id="profile-type" v-model="form.type" :class="inputClass(errors.type)">
                  <option value="">Select type</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
                </select>
                <p v-if="errors.type" class="mt-2 text-xs text-red-600">{{ errors.type }}</p>
              </div>

              <div class="md:col-span-2">
                <label for="profile-address" class="mb-2 block text-sm font-medium text-gray-700">Address</label>
                <textarea id="profile-address" v-model="form.address" rows="4" :class="inputClass(errors.address)"
                  placeholder="House, road, area, city" />
                <p v-if="errors.address" class="mt-2 text-xs text-red-600">{{ errors.address }}</p>
              </div>
            </div>
          </div>

          <div class="flex justify-end border-t border-gray-200 pt-6">
            <button type="submit" :disabled="saving"
              class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 disabled:bg-green-400">
              <svg v-if="saving" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              Save Changes
            </button>
          </div>
        </form>

        <form @submit.prevent="submitPasswordChange"
          class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
            <p class="mt-1 text-sm text-gray-500">Use a strong password with uppercase, lowercase, number, and special
              character.</p>
          </div>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
              <label for="current-password" class="mb-2 block text-sm font-medium text-gray-700">Current
                Password</label>
              <input id="current-password" v-model="passwordForm.current_password" type="password"
                :class="inputClass(passwordErrors.current_password)" placeholder="Enter current password" />
              <p v-if="passwordErrors.current_password" class="mt-2 text-xs text-red-600">{{
                passwordErrors.current_password }}</p>
            </div>

            <div>
              <label for="new-password" class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
              <input id="new-password" v-model="passwordForm.password" type="password"
                :class="inputClass(passwordErrors.password)" placeholder="Create new password" />
              <p v-if="passwordErrors.password" class="mt-2 text-xs text-red-600">{{ passwordErrors.password }}</p>
            </div>

            <div>
              <label for="confirm-password" class="mb-2 block text-sm font-medium text-gray-700">Confirm
                Password</label>
              <input id="confirm-password" v-model="passwordForm.password_confirmation" type="password"
                :class="inputClass(passwordErrors.password_confirmation)" placeholder="Confirm new password" />
              <p v-if="passwordErrors.password_confirmation" class="mt-2 text-xs text-red-600">{{
                passwordErrors.password_confirmation }}</p>
            </div>
          </div>

          <div class="flex justify-end border-t border-gray-200 pt-6">
            <button type="submit" :disabled="changingPassword"
              class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-gray-800 disabled:bg-gray-500">
              <svg v-if="changingPassword" class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              Update Password
            </button>
          </div>
        </form>
      </div>

      <aside class="space-y-6">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <div class="flex items-center gap-4">
            <div
              class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xl font-semibold text-white">
              <img v-if="displayAvatarUrl" :src="displayAvatarUrl" alt="Profile photo"
                class="h-full w-full object-cover" />
              <span v-else>{{ initials }}</span>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ profileUser?.name || 'User' }}</h3>
              <p class="text-sm text-gray-500">{{ profileUser?.email || 'No email added' }}</p>
            </div>
          </div>
          <div class="mt-4 space-y-3 text-sm text-gray-600">
            <div class="flex items-center justify-between">
              <span>Roles</span>
              <span class="font-medium text-gray-900">{{ roleNames }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Permissions</span>
              <span class="font-medium text-gray-900">{{ permissions.length }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Files</span>
              <span class="font-medium text-gray-900">{{ fileCount }}</span>
            </div>
          </div>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          <h3 class="text-sm font-semibold text-gray-900">Account Summary</h3>
          <dl class="mt-4 space-y-3 text-sm">
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Status</dt>
              <dd class="font-medium text-gray-900">{{ profileUser?.status ? 'Active' : 'Inactive' }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Joined</dt>
              <dd class="font-medium text-gray-900">{{ formatDisplayDate(profileUser?.created_at) }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Last Updated</dt>
              <dd class="font-medium text-gray-900">{{ formatDisplayDate(profileUser?.updated_at) }}</dd>
            </div>
          </dl>
        </section>
      </aside>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { services } from '~/services'
import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

definePageMeta({ middleware: ['auth'] })

const auth = useAuth()
const loadingProfile = ref(true)
const saving = ref(false)
const uploadingPhoto = ref(false)
const changingPassword = ref(false)
const countries = ref<any[]>([])
const languages = ref<any[]>([])
const permissions = ref<string[]>([])
const profileUser = ref<any>(null)
const photoInput = ref<HTMLInputElement | null>(null)
const photoError = ref('')
const photoPreviewUrl = ref<string | null>(null)

const form = reactive({
  name: '',
  email: '',
  phone: '',
  country_code_id: null as number | null,
  ui_locale: '',
  gender: '' as '' | 'male' | 'female' | 'other',
  type: '' as '' | 'student' | 'teacher' | 'admin',
  address: '',
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const errors = reactive<Record<string, string>>({
  name: '',
  email: '',
  phone: '',
  country_code_id: '',
  ui_locale: '',
  gender: '',
  type: '',
  address: '',
})

const passwordErrors = reactive<Record<string, string>>({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const roleNames = computed(() => profileUser.value?.roles?.map((role: any) => role.name).join(', ') || 'No roles')
const fileCount = computed(() => profileUser.value?.files?.length || 0)
const initials = computed(() => {
  const name = profileUser.value?.name || 'User'
  return name.split(' ').map((part: string) => part[0]).join('').toUpperCase().slice(0, 2)
})
const displayAvatarUrl = computed(() => photoPreviewUrl.value || profileUser.value?.avatar_url || null)

const inputClass = (error: string) => [
  'block w-full rounded-xl border px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-indigo-500',
  error ? 'border-red-300 bg-red-50' : 'border-gray-300 bg-white',
]

const clearErrors = (target: Record<string, string>) => {
  Object.keys(target).forEach((key) => {
    target[key] = ''
  })
}

const applyValidationErrors = (target: Record<string, string>, validationErrors?: Record<string, string[]>) => {
  Object.entries(validationErrors || {}).forEach(([key, messages]) => {
    target[key] = Array.isArray(messages) ? messages[0] : String(messages)
  })
}

const applyProfile = (payload: any) => {
  profileUser.value = payload.user
  permissions.value = payload.permissions || []

  form.name = payload.user?.name || ''
  form.email = payload.user?.email || ''
  form.phone = payload.user?.phone || ''
  form.country_code_id = payload.user?.country_code_id ?? null
  form.ui_locale = payload.user?.ui_locale || ''
  form.gender = payload.user?.profile?.gender || ''
  form.type = payload.user?.profile?.type || ''
  form.address = payload.user?.profile?.address || ''
}

const fetchMeta = async () => {
  const [countryResponse, languageResponse] = await Promise.all([
    services.countryCode.getAllCountryCodes(),
    services.localization.getLanguages(),
  ])

  countries.value = countryResponse.data || []
  languages.value = Object.values(languageResponse.data || {})
}

const fetchProfile = async () => {
  loadingProfile.value = true
  try {
    const response = await services.user.getProfile()
    if (response.success && response.data) {
      applyProfile(response.data)
      return
    }

    notification.error(response.message || 'Failed to load profile')
  } finally {
    loadingProfile.value = false
  }
}

const submitProfile = async () => {
  saving.value = true
  clearErrors(errors)

  try {
    const payload = {
      name: form.name.trim() || undefined,
      email: form.email.trim() || undefined,
      phone: form.phone.trim() || null,
      country_code_id: form.country_code_id,
      ui_locale: form.ui_locale || null,
      gender: form.gender || null,
      type: form.type || null,
      address: form.address.trim() || null,
    }

    const response = await services.user.updateProfile(payload)

    if (response.success && response.data) {
      applyProfile(response.data)
      await auth.fetchCurrentUser()
      notification.success(response.message || 'Profile updated successfully')
      return
    }

    applyValidationErrors(errors, response.errors)
    notification.error(response.message || 'Failed to update profile')
  } finally {
    saving.value = false
  }
}

const resetPasswordForm = () => {
  passwordForm.current_password = ''
  passwordForm.password = ''
  passwordForm.password_confirmation = ''
}

const submitPasswordChange = async () => {
  changingPassword.value = true
  clearErrors(passwordErrors)

  try {
    const response = await auth.changePassword({
      current_password: passwordForm.current_password,
      password: passwordForm.password,
      password_confirmation: passwordForm.password_confirmation,
    })

    if (response.success) {
      resetPasswordForm()
      notification.success(response.message || 'Password updated successfully')
      return
    }

    applyValidationErrors(passwordErrors, response.errors)
    if (!response.errors?.current_password && response.message) {
      passwordErrors.current_password = response.message
    }
    notification.error(response.message || 'Failed to update password')
  } finally {
    changingPassword.value = false
  }
}

const revokePreviewUrl = () => {
  if (photoPreviewUrl.value) {
    URL.revokeObjectURL(photoPreviewUrl.value)
    photoPreviewUrl.value = null
  }
}

const handlePhotoSelected = async (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]

  photoError.value = ''

  if (!file) {
    return
  }

  if (file.size > 5 * 1024 * 1024) {
    photoError.value = 'Photo must be 5MB or smaller.'
    input.value = ''
    return
  }

  revokePreviewUrl()
  photoPreviewUrl.value = URL.createObjectURL(file)
  uploadingPhoto.value = true

  try {
    const response = await services.user.uploadFile(file, 'profile_image', 'user', profileUser.value?.id, true)

    if (response.success && response.data) {
      applyProfile(response.data)
      await auth.fetchCurrentUser()
      notification.success(response.message || 'Profile photo updated successfully')
      return
    }

    photoError.value = response.errors?.photo?.[0] || response.message || 'Failed to upload profile photo'
    revokePreviewUrl()
  } finally {
    uploadingPhoto.value = false
    input.value = ''
  }
}

const formatDisplayDate = (value?: string) => {
  if (!value) {
    return 'N/A'
  }

  return new Date(value).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

onMounted(async () => {
  await Promise.all([fetchMeta(), fetchProfile()])
})

onBeforeUnmount(() => {
  revokePreviewUrl()
})
</script>