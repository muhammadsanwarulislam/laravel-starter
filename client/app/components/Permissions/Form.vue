<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div v-if="loadingPermission" class="flex justify-center py-8">
      <UILoadingSpinner size="lg" />
    </div>

    <template v-else>
      <div>
        <h4 class="text-md font-medium text-gray-900 mb-4">
          {{ isEditMode ? 'Edit Permission' : '' }}
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="col-span-full">
            <label for="permission-name" class="block text-sm font-medium text-gray-700 mb-2">
              {{ t('permissions.form.name') }}
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <UIIconsRolePermissions class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="permission-name"
                v-model="form.name"
                type="text"
                :class="[
                  'block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                  errors.name ? 'border-red-300 bg-red-50' : 'border-gray-300'
                ]"
                :placeholder="t('permissions.form.name_placeholder')"
              />
            </div>
            <p v-if="errors.name" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.name }}
            </p>
            <p v-else-if="form.name.trim()" class="mt-2 text-xs text-green-600 flex items-center">
              <UIIconsCheck class="h-4 w-4 mr-1 text-green-600" />
              {{ t('permissions.form.name_valid') }}
            </p>
          </div>

          <div>
            <label for="permission-module" class="block text-sm font-medium text-gray-700 mb-2">
              {{ t('permissions.form.module') }}
            </label>
            <input
              id="permission-module"
              v-model="form.module"
              type="text"
              :class="[
                'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                errors.module ? 'border-red-300 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="reports"
            />
            <p v-if="errors.module" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.module }}
            </p>
            <p v-else class="mt-2 text-xs text-gray-500">
              {{ t('permissions.form.module_info') }}
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-4">
            <p class="text-sm font-medium text-gray-700">{{ t('permissions.slug_generated') }}</p>
            <p class="mt-1 text-sm font-semibold text-gray-900">
              {{ previewSlug || 'name-module' }}
            </p>
            <p class="mt-2 text-xs text-gray-500">
              The API generates the slug from the permission name and module.
            </p>
          </div>

          <div class="col-span-full">
            <label for="permission-description" class="block text-sm font-medium text-gray-700 mb-2">
              {{ t('common.description') }}
            </label>
            <textarea
              id="permission-description"
              v-model="form.description"
              rows="4"
              :class="[
                'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                errors.description ? 'border-red-300 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="Allows team members to view reporting dashboards and exports."
            />
            <p v-if="errors.description" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.description }}
            </p>
          </div>
        </div>
      </div>

      <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <h4 class="text-sm font-semibold text-gray-900">Naming Guide</h4>
        <p class="mt-1 text-sm text-gray-600">
          Keep permission names action-focused, like "Create User" or "Export Invoice", and group them with a consistent module.
        </p>
      </div>

      <div class="border-t border-gray-200 pt-4 flex justify-end space-x-3">
        <button
          type="button"
          @click="router.push('/permissions')"
          class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          {{ t('common.button.cancel') }}
        </button>
        <button
          type="submit"
          :disabled="isSubmitting"
          class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center"
        >
          <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
          </svg>
          {{ isEditMode ? t('common.button.update') : t('common.button.create') }}
        </button>
      </div>
    </template>
  </form>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import type { Permission } from '~/api/types/api.types'
import { notification } from '~/utils/notification'

const props = defineProps<{
  permissionId?: number
}>()

const router = useRouter()
const api = useApi()
const { t } = useLocalization()

const isEditMode = computed(() => Number.isFinite(props.permissionId))
const isSubmitting = ref(false)
const loadingPermission = ref(false)

const form = reactive({
  name: '',
  module: '',
  description: '',
})

const errors = reactive({
  name: '',
  module: '',
  description: '',
})

const previewSlug = computed(() => {
  const slugify = (value: string) => value
    .toLowerCase()
    .trim()
    .replace(/[^\w\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')

  if (!form.name.trim() && !form.module.trim()) {
    return ''
  }

  return `${slugify(form.name)}-${slugify(form.module)}`.replace(/^-|-$/g, '')
})

const applyPermission = (permission: Permission) => {
  form.name = permission.name
  form.module = permission.module
  form.description = permission.description || ''
}

const clearErrors = () => {
  errors.name = ''
  errors.module = ''
  errors.description = ''
}

const validateForm = () => {
  clearErrors()

  let isValid = true

  if (!form.name.trim()) {
    errors.name = 'Permission name is required.'
    isValid = false
  }

  if (!form.module.trim()) {
    errors.module = 'Module is required.'
    isValid = false
  }

  return isValid
}

const mapApiErrors = (apiErrors?: Record<string, string[]>) => {
  if (!apiErrors) {
    return
  }

  errors.name = apiErrors.name?.[0] || ''
  errors.module = apiErrors.module?.[0] || ''
  errors.description = apiErrors.description?.[0] || ''
}

const fetchPermission = async () => {
  if (!isEditMode.value || !props.permissionId) {
    return
  }

  loadingPermission.value = true

  try {
    const response = await api.permission.getPermissionById(props.permissionId)

    if (response.success && response.data) {
      applyPermission(response.data)
      return
    }

    notification.error(response.message || 'Failed to fetch permission')
    router.push('/permissions')
  } finally {
    loadingPermission.value = false
  }
}

const submitForm = async () => {
  if (!validateForm()) {
    return
  }

  isSubmitting.value = true
  clearErrors()

  try {
    const payload = {
      name: form.name.trim(),
      module: form.module.trim(),
      description: form.description.trim() || undefined,
    }

    const response = isEditMode.value && props.permissionId
      ? await api.permission.updatePermission(props.permissionId, payload)
      : await api.permission.createPermission(payload)

    if (response.success) {
      notification.success(response.message || (isEditMode.value ? 'Permission updated successfully' : 'Permission created successfully'))
      router.push('/permissions')
      return
    }

    mapApiErrors(response.errors)
    notification.error(response.message || 'Failed to save permission')
  } finally {
    isSubmitting.value = false
  }
}

onMounted(fetchPermission)
</script>
