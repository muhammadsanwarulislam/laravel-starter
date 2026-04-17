<template>
    <form @submit.prevent="submitForm" class="space-y-6">
        <div v-if="loadingState" class="flex justify-center py-8">
            <UILoadingSpinner size="lg" />
        </div>

        <template v-else>
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    {{ isEditMode ? 'Edit User' : 'Basic Information' }}
                </h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="col-span-full">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <UIIconsUser class="h-5 w-5 text-gray-400" />
                            </div>
                            <input id="name" v-model="form.name" name="name" type="text" required
                                @input="validateNameOnInput"
                                :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                                    errors.name ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                                placeholder="John Doe" />
                        </div>
                        <p v-if="errors.name" class="mt-2 text-xs text-red-600 flex items-center">
                            <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
                            {{ errors.name }}
                        </p>
                        <p v-else-if="!isEditMode && form.name.trim() && /^[A-Za-z]/.test(form.name.trim())"
                            class="mt-2 text-xs text-green-600 flex items-center">
                            <UIIconsCheck class="h-4 w-4 mr-1 text-green-600" />
                            Valid name format
                        </p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <div class="flex gap-2">
                            <div class="relative flex-1 max-w-xs">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <UIIconsPhone class="h-5 w-5 text-gray-400" />
                                </div>
                                <select v-model="form.country_code_id"
                                    :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200 appearance-none',
                                        errors.country_code_id ? 'border-red-300 bg-red-50' : 'border-gray-300']">
                                    <option v-for="country in countries" :key="country.id" :value="country.id">
                                        {{ country.dial_code }} {{ country.name }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <UIIconsChevronDown class="h-5 w-5 text-gray-400" />
                                </div>
                            </div>

                            <div class="relative flex-1">
                                <input id="phone" v-model="form.phone" name="phone" type="tel"
                                    :required="!isEditMode"
                                    :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                                        errors.phone ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                                    placeholder="01XXXXXXXXX" />
                            </div>
                        </div>
                        <p v-if="errors.phone" class="mt-2 text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ errors.phone }}
                        </p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ isEditMode ? 'Email Address' : 'Email Address (Optional)' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" v-model="form.email" name="email" type="email" autocomplete="email"
                                :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                                    errors.email ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                                placeholder="you@example.com" />
                        </div>
                        <p v-if="errors.email" class="mt-2 text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ isEditMode ? 'Password (leave blank to keep unchanged)' : 'Password' }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" v-model="form.password" name="password"
                                :type="showPassword ? 'text' : 'password'"
                                :autocomplete="isEditMode ? 'new-password' : 'new-password'"
                                :required="!isEditMode"
                                :class="['block w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                                    errors.password ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                                placeholder="••••••••" />
                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="errors.password" class="mt-2 text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ errors.password }}
                        </p>
                        <p v-else class="mt-2 text-xs text-gray-500">
                            {{ isEditMode ? 'Minimum 8 characters if you want to change the password' : 'Must be at least 8 characters' }}
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" v-model="form.password_confirmation"
                                name="password_confirmation" :type="showPassword ? 'text' : 'password'"
                                autocomplete="new-password" :required="!isEditMode"
                                :class="['block w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                                    errors.password_confirmation ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                                placeholder="••••••••" />
                        </div>
                        <p v-if="errors.password_confirmation" class="mt-2 text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ errors.password_confirmation }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                        <div class="flex space-x-6">
                            <div class="flex items-center">
                                <input :id="`${statusIdPrefix}-active`" v-model="form.status" :value="true" type="radio"
                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <label :for="`${statusIdPrefix}-active`" class="ml-2 block text-sm text-gray-700">Active</label>
                            </div>
                            <div class="flex items-center">
                                <input :id="`${statusIdPrefix}-inactive`" v-model="form.status" :value="false" type="radio"
                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <label :for="`${statusIdPrefix}-inactive`" class="ml-2 block text-sm text-gray-700">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h4 class="text-md font-medium text-gray-900 mb-4">Roles</h4>
                <div
                    class="grid grid-cols-1 gap-3 sm:grid-cols-2 max-h-48 overflow-y-auto p-2 border border-gray-200 rounded-md">
                    <div v-for="role in roles" :key="role.id" class="flex items-start">
                        <input :id="`${roleIdPrefix}-${role.id}`" v-model="form.roles" :value="role.id" type="checkbox"
                            class="mt-1 h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500" />
                        <div class="ml-2">
                            <label :for="`${roleIdPrefix}-${role.id}`" class="text-sm font-medium text-gray-700">
                                {{ role.name }}
                            </label>
                            <p class="text-xs text-gray-500">{{ role.description }}</p>
                        </div>
                    </div>
                </div>
                <p v-if="errors.roles" class="mt-2 text-xs text-red-600">{{ errors.roles }}</p>
            </div>

            <div class="border-t border-gray-200 pt-4 flex justify-end space-x-3">
                <button type="button" @click="router.push('/users')"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    {{ t('common.cancel') }}
                </button>
                <button type="submit" :disabled="isSubmitting"
                    class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                    <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    {{ isEditMode ?  t('common.update') : t('common.create') }}
                </button>
            </div>
        </template>
    </form>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUsers } from "~/composables/user/useUser"
import { notification } from '~/utils/notification'
import { services } from '~/services'

const props = defineProps<{
    userId?: number
}>()

const { t } = useLocalization()
const router = useRouter()
const { createUser, updateUser } = useUsers()
const api = useApi()

const isEditMode = computed(() => Number.isFinite(props.userId))
const loadingUser = ref(false)
const loadingRoles = ref(false)
const loadingCountries = ref(false)
const isSubmitting = ref(false)
const roles = ref<any[]>([])
const countries = ref<any[]>([])
const showPassword = ref(false)

const loadingState = computed(() => loadingRoles.value || loadingCountries.value || (isEditMode.value && loadingUser.value))
const roleIdPrefix = computed(() => isEditMode.value ? 'edit-role' : 'create-role')
const statusIdPrefix = computed(() => isEditMode.value ? 'edit-status' : 'create-status')

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    country_code_id: 1,
    status: true,
    roles: [] as number[]
})

const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    country_code_id: '',
    roles: ''
})

const resetErrors = () => {
    Object.keys(errors).forEach(key => {
        errors[key as keyof typeof errors] = ''
    })
}

const fetchUser = async () => {
    if (!isEditMode.value || !props.userId) {
        return
    }

    loadingUser.value = true
    const response = await api.user.getUserById(props.userId)
    if (response.success && response.data) {
        const user = response.data as any
        form.name = user.name || ''
        form.email = user.email || ''
        form.phone = user.phone || ''
        form.country_code_id = user.country_code_id || 1
        form.status = user.status ?? true
        form.roles = user.roles?.map((role: any) => role.id) || []
    } else {
        notification.error('Failed to load user data')
        router.push('/users')
    }
    loadingUser.value = false
}

const fetchRoles = async () => {
    try {
        loadingRoles.value = true
        const response = await api.role.getRoles({ limit: 100 })
        if (response.success && response.data) {
            roles.value = response.data
        }
    } catch (error) {
        notification.error('Failed to fetch roles')
    } finally {
        loadingRoles.value = false
    }
}

const fetchCountries = async () => {
    try {
        loadingCountries.value = true
        const response = await services.countryCode.getAllCountryCodes()
        if (response.success && response.data) {
            countries.value = response.data
        }
    } catch (error) {
        notification.error('Failed to fetch countries')
    } finally {
        loadingCountries.value = false
    }
}

const validateForm = (): boolean => {
    let isValid = true
    resetErrors()

    if (!form.name.trim()) {
        errors.name = 'Name is required'
        isValid = false
    }

    if (!form.email.trim()) {
        errors.email = 'Email is required'
        isValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please enter a valid email address'
        isValid = false
    }

    if (isEditMode.value) {
        if (form.password) {
            if (form.password.length < 8) {
                errors.password = 'Password must be at least 8 characters'
                isValid = false
            }
            if (form.password !== form.password_confirmation) {
                errors.password_confirmation = 'Passwords do not match'
                isValid = false
            }
        }
    } else {
        if (!form.password) {
            errors.password = 'Password is required'
            isValid = false
        } else if (form.password.length < 8) {
            errors.password = 'Password must be at least 8 characters'
            isValid = false
        }

        if (form.password !== form.password_confirmation) {
            errors.password_confirmation = 'Passwords do not match'
            isValid = false
        }
    }

    if (form.roles.length === 0) {
        errors.roles = 'Please select at least one role'
        isValid = false
    }

    return isValid
}

const validateNameOnInput = () => {
    if (form.name.trim()) {
        if (!/^[A-Za-z]/.test(form.name.trim())) {
            errors.name = 'Name must start with a letter'
        } else if (/^\d+$/.test(form.name.trim())) {
            errors.name = 'Name cannot be only numbers'
        } else {
            errors.name = ''
        }
    } else {
        errors.name = ''
    }
}

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value
}

const applyServerErrors = (serverErrors: Record<string, string[] | string>) => {
    for (const field in serverErrors) {
        const message = Array.isArray(serverErrors[field]) ? serverErrors[field][0] : serverErrors[field]
        if (field in errors) {
            errors[field as keyof typeof errors] = message
        } else {
            notification.error(message || 'Validation failed')
        }
    }
}

const submitForm = async () => {
    if (!validateForm()) {
        return
    }

    isSubmitting.value = true
    resetErrors()

    const payload: any = {
        name: form.name,
        email: form.email,
        phone: form.phone || null,
        country_code_id: form.country_code_id ? Number(form.country_code_id) : null,
        status: form.status,
        roles: form.roles
    }

    if (!isEditMode.value || form.password) {
        payload.password = form.password
        payload.password_confirmation = form.password_confirmation
    }

    const response = isEditMode.value && props.userId
        ? await updateUser(props.userId, payload)
        : await createUser(payload)

    isSubmitting.value = false

    if (response && !response.success && response.errors) {
        applyServerErrors(response.errors)
    } else if (!response?.success) {
        notification.error(response?.message || `Failed to ${isEditMode.value ? 'update' : 'create'} user`)
    }
}

onMounted(async () => {
    await Promise.all([fetchRoles(), fetchCountries(), fetchUser()])
})
</script>
