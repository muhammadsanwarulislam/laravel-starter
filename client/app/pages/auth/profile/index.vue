<template>
  <div class="p-6">
    <SharedPageHeader :title="t('profile.title')" :description="t('profile.description')" />

    <div v-if="loading" class="flex justify-center py-10">
      <UILoadingSpinner :message="t('common.loading')" />
    </div>

    <div v-else class="grid gap-6 lg:grid-cols-[1fr_320px]">
      <div class="space-y-6">
        <ProfileAvatar :user="profileUser" @updated="refreshProfile" />
        <ProfileBasicInfo
          :form="basicForm"
          :errors="basicErrors"
          :saving="savingBasic"
          :countries="countries"
          :languages="languages"
          @submit="saveBasicInfo"
        />
        <ProfileDetails
          :form="detailsForm"
          :errors="detailsErrors"
          :saving="savingDetails"
          @submit="saveDetails"
        />
        <ProfilePassword />
      </div>
      <ProfileSummary :user="profileUser" :permissions="permissions" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { services } from '~/services'
import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

definePageMeta({ middleware: ['auth'] })

const { t } = useLocalization()
const auth = useAuth()

// Data
const loading = ref(true)
const profileUser = ref<any>(null)
const permissions = ref<string[]>([])
const countries = ref<any[]>([])
const languages = ref<any[]>([])

// Forms
const basicForm = reactive({
  name: '',
  email: '',
  phone: '',
  country_code_id: null as number | null,
  ui_locale: '',
})

const detailsForm = reactive({
  gender: '' as '' | 'male' | 'female' | 'other',
  type: '' as '' | 'student' | 'teacher' | 'admin',
  address: '',
})

const basicErrors = reactive<Record<string, string>>({})
const detailsErrors = reactive<Record<string, string>>({})
const savingBasic = ref(false)
const savingDetails = ref(false)

// Helpers
const clearErrors = (target: Record<string, string>) => {
  Object.keys(target).forEach(key => delete target[key])
}

const applyValidationErrors = (target: Record<string, string>, errors?: Record<string, string[]>) => {
  if (!errors) return
  Object.entries(errors).forEach(([key, messages]) => {
    target[key] = Array.isArray(messages) ? messages[0] : String(messages)
  })
}

const populateForms = (user: any) => {
  basicForm.name = user?.name || ''
  basicForm.email = user?.email || ''
  basicForm.phone = user?.phone || ''
  basicForm.country_code_id = user?.country_code_id ?? null
  basicForm.ui_locale = user?.ui_locale || ''

  detailsForm.gender = user?.profile?.gender || ''
  detailsForm.type = user?.profile?.type || ''
  detailsForm.address = user?.profile?.address || ''
}

// Fetch meta data (countries, languages)
const fetchMeta = async () => {
  const [countryRes, langRes] = await Promise.all([
    services.countryCode.getAllCountryCodes(),
    services.localization.getLanguages(),
  ])
  countries.value = countryRes.data || []
  languages.value = Object.values(langRes.data || {})
}

// Fetch profile
const fetchProfile = async () => {
  loading.value = true
  try {
    const response = await services.user.getProfile()
    if (response.success && response.data) {
      profileUser.value = response.data.user
      permissions.value = response.data.permissions || []
      populateForms(response.data.user)
    } else {
      notification.error(response.message || 'Failed to load profile')
    }
  } finally {
    loading.value = false
  }
}

// Refresh after avatar update
const refreshProfile = (updatedUser: any) => {
  profileUser.value = updatedUser
  populateForms(updatedUser)
}

// Save basic info
const saveBasicInfo = async () => {
  savingBasic.value = true
  clearErrors(basicErrors)

  try {
    const payload = {
      name: basicForm.name.trim() || undefined,
      email: basicForm.email.trim() || undefined,
      phone: basicForm.phone.trim() || null,
      country_code_id: basicForm.country_code_id,
      ui_locale: basicForm.ui_locale || null,
    }
    const response = await services.user.updateProfile(payload)
    if (response.success && response.data) {
      profileUser.value = response.data.user
      populateForms(response.data.user)
      await auth.fetchCurrentUser()
      notification.success(response.message || 'Profile updated')
    } else {
      applyValidationErrors(basicErrors, response.errors)
      notification.error(response.message || 'Update failed')
    }
  } finally {
    savingBasic.value = false
  }
}

// Save details (gender, type, address)
const saveDetails = async () => {
  savingDetails.value = true
  clearErrors(detailsErrors)

  try {
    const payload = {
      gender: detailsForm.gender || null,
      type: detailsForm.type || null,
      address: detailsForm.address.trim() || null,
    }
    const response = await services.user.updateProfile(payload)
    if (response.success && response.data) {
      profileUser.value = response.data.user
      populateForms(response.data.user)
      await auth.fetchCurrentUser()
      notification.success(response.message || 'Profile details updated')
    } else {
      applyValidationErrors(detailsErrors, response.errors)
      notification.error(response.message || 'Update failed')
    }
  } finally {
    savingDetails.value = false
  }
}

onMounted(async () => {
  await fetchMeta()
  await fetchProfile()
})
</script>