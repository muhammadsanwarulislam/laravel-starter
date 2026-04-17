<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div v-if="loadingRole || loadingPermissions" class="flex justify-center py-8">
      <UILoadingSpinner size="lg" />
    </div>

    <template v-else>
      <div>
        <h4 class="text-md font-medium text-gray-900 mb-4">Basic Information</h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="col-span-full">
            <label for="role-name" class="block text-sm font-medium text-gray-700 mb-2">
              Role Name
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <UIIconsRolePermissions class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="role-name"
                v-model="form.name"
                type="text"
                :class="[
                  'block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                  errors.name ? 'border-red-300 bg-red-50' : 'border-gray-300'
                ]"
                placeholder="Pharmacy Manager"
              />
            </div>
            <p v-if="errors.name" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.name }}
            </p>
            <p v-else-if="form.name.trim()" class="mt-2 text-xs text-green-600 flex items-center">
              <UIIconsCheck class="h-4 w-4 mr-1 text-green-600" />
              Role name looks good
            </p>
          </div>

          <div>
            <label for="role-level" class="block text-sm font-medium text-gray-700 mb-2">
              Role Level
            </label>
            <div class="relative">
              <input
                id="role-level"
                v-model.number="form.level"
                type="number"
                min="0"
                max="100"
                :class="[
                  'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                  errors.level ? 'border-red-300 bg-red-50' : 'border-gray-300'
                ]"
                placeholder="10"
              />
            </div>
            <p v-if="errors.level" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.level }}
            </p>
            <p v-else class="mt-2 text-xs text-gray-500">
              Use a higher number for more privileged roles.
            </p>
          </div>

          <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-4">
            <p class="text-sm font-medium text-gray-700">Role Type</p>
            <p class="mt-1 text-sm text-gray-600">
              {{ form.is_system ? 'System roles are protected from editing.' : 'Custom roles can be updated and deleted.' }}
            </p>
            <div class="mt-3 flex items-center gap-2 text-xs font-medium">
              <span
                class="inline-flex items-center rounded-full px-2.5 py-1"
                :class="form.is_system ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
              >
                {{ form.is_system ? 'System Role' : 'Custom Role' }}
              </span>
            </div>
          </div>

          <div class="md:col-span-2">
            <label for="role-description" class="block text-sm font-medium text-gray-700 mb-2">
              Description
            </label>
            <textarea
              id="role-description"
              v-model="form.description"
              rows="4"
              :class="[
                'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                errors.description ? 'border-red-300 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="Can manage pharmacy staff, medicines, and reports."
            />
            <p v-if="errors.description" class="mt-2 text-xs text-red-600 flex items-center">
              <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
              {{ errors.description }}
            </p>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-200 pt-4">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h4 class="text-md font-medium text-gray-900">Permissions</h4>
            <p class="text-sm text-gray-600 mt-1">Select the permissions this role should include.</p>
          </div>
          <div class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
            {{ form.permissions.length }} selected
          </div>
        </div>

        <div v-if="Object.keys(groupedPermissions).length === 0" class="rounded-xl border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500">
          No permissions available.
        </div>

        <div v-else class="space-y-4">
          <section v-for="(modulePermissions, module) in groupedPermissions" :key="module" class="rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3">
              <div>
                <h4 class="text-sm font-semibold capitalize text-gray-900">{{ module }}</h4>
                <p class="text-xs text-gray-500">{{ modulePermissions.length }} permissions</p>
              </div>
              <button
                type="button"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                @click="toggleModulePermissions(modulePermissions)"
              >
                {{ areAllModulePermissionsSelected(modulePermissions) ? 'Clear' : 'Select All' }}
              </button>
            </div>

            <div class="grid gap-3 p-4 md:grid-cols-2">
              <label
                v-for="permission in modulePermissions"
                :key="permission.id"
                class="flex items-start gap-3 rounded-xl border border-gray-200 p-3 transition hover:border-indigo-300 hover:bg-indigo-50/30"
              >
                <input
                  v-model="form.permissions"
                  :value="permission.id"
                  type="checkbox"
                  class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                />
                <span>
                  <span class="block text-sm font-medium text-gray-800">{{ permission.name }}</span>
                  <span class="block text-xs text-gray-500">{{ permission.slug }}</span>
                  <span v-if="permission.description" class="mt-1 block text-xs text-gray-500">
                    {{ permission.description }}
                  </span>
                </span>
              </label>
            </div>
          </section>
        </div>
        <p v-if="errors.permissions" class="mt-3 text-xs text-red-600">{{ errors.permissions }}</p>
      </div>

      <div class="border-t border-gray-200 pt-4 flex justify-end space-x-3">
        <button
          type="button"
          @click="router.push('/roles')"
          class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          {{ t('common.cancel') }}
        </button>
        <button
          type="submit"
          :disabled="isSubmitting || (isEditMode && form.is_system)"
          class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center"
        >
          <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
          </svg>
          {{ isEditMode ? t('common.update') : t('common.create') }}
        </button>
      </div>
    </template>
  </form>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import type { Permission, Role } from '~/api/types/api.types'
import { notification } from '~/utils/notification'

const props = defineProps<{
  roleId?: number
}>()

const router = useRouter()
const api = useApi()
const { t } = useLocalization()

const isEditMode = computed(() => Number.isFinite(props.roleId))
const isSubmitting = ref(false)
const loadingRole = ref(false)
const loadingPermissions = ref(false)
const permissions = ref<Permission[]>([])

const form = reactive({
  name: '',
  description: '',
  level: 0,
  is_system: false,
  permissions: [] as number[],
})

const errors = reactive({
  name: '',
  description: '',
  level: '',
  permissions: '',
})

const groupedPermissions = computed<Record<string, Permission[]>>(() => {
  return permissions.value.reduce((groups, permission) => {
    if (!groups[permission.module]) {
      groups[permission.module] = []
    }

    groups[permission.module].push(permission)
    return groups
  }, {} as Record<string, Permission[]>)
})

const applyRole = (role: Role) => {
  form.name = role.name
  form.description = role.description || ''
  form.level = role.level
  form.is_system = role.is_system
  form.permissions = role.permissions?.map(permission => permission.id) || []
}

const clearErrors = () => {
  errors.name = ''
  errors.description = ''
  errors.level = ''
  errors.permissions = ''
}

const validateForm = () => {
  clearErrors()

  let isValid = true

  if (!form.name.trim()) {
    errors.name = 'Role name is required.'
    isValid = false
  }

  if (!Number.isInteger(form.level) || form.level < 0 || form.level > 100) {
    errors.level = 'Level must be between 0 and 100.'
    isValid = false
  }

  return isValid
}

const mapApiErrors = (apiErrors?: Record<string, string[]>) => {
  if (!apiErrors) {
    return
  }

  errors.name = apiErrors.name?.[0] || ''
  errors.description = apiErrors.description?.[0] || ''
  errors.level = apiErrors.level?.[0] || ''
  errors.permissions = apiErrors.permissions?.[0] || apiErrors['permissions.0']?.[0] || ''
}

const fetchPermissions = async () => {
  loadingPermissions.value = true

  try {
    const response = await api.permission.getPermissions()

    if (response.success && response.data) {
      permissions.value = response.data
      return
    }

    notification.error(response.message || 'Failed to fetch permissions')
  } finally {
    loadingPermissions.value = false
  }
}

const fetchRole = async () => {
  if (!isEditMode.value || !props.roleId) {
    return
  }

  loadingRole.value = true

  try {
    const response = await api.role.getRoleById(props.roleId)

    if (response.success && response.data) {
      applyRole(response.data)
      return
    }

    notification.error(response.message || 'Failed to fetch role')
    router.push('/roles')
  } finally {
    loadingRole.value = false
  }
}

const areAllModulePermissionsSelected = (modulePermissions: Permission[]) => {
  return modulePermissions.every(permission => form.permissions.includes(permission.id))
}

const toggleModulePermissions = (modulePermissions: Permission[]) => {
  const ids = modulePermissions.map(permission => permission.id)

  if (areAllModulePermissionsSelected(modulePermissions)) {
    form.permissions = form.permissions.filter(id => !ids.includes(id))
    return
  }

  form.permissions = Array.from(new Set([...form.permissions, ...ids]))
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
      description: form.description.trim() || undefined,
      level: form.level,
      permissions: form.permissions,
    }

    const response = isEditMode.value && props.roleId
      ? await api.role.updateRole(props.roleId, payload)
      : await api.role.createRole(payload)

    if (response.success) {
      notification.success(response.message || (isEditMode.value ? 'Role updated successfully' : 'Role created successfully'))
      router.push('/roles')
      return
    }

    mapApiErrors(response.errors)
    notification.error(response.message || 'Failed to save role')
  } finally {
    isSubmitting.value = false
  }
}

onMounted(async () => {
  await Promise.all([fetchPermissions(), fetchRole()])
})
</script>
