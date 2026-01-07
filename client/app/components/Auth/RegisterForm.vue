<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 relative">
        <button @click="$emit('close')"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Create Account</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Join us today and get started</p>
        </div>

        <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Full Name
                    </label>
                    <input id="name" v-model="form.name" name="name" type="text" required
                        :class="['appearance-none relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm',
                            errors.name ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                        placeholder="Full name" />
                    <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Phone Number
                    </label>
                    <input id="phone" v-model="form.phone" name="phone" type="tel" required
                        :class="['appearance-none relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm',
                            errors.phone ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                        placeholder="01XXXXXXXXX" />
                    <p v-if="errors.phone" class="mt-1 text-xs text-red-600">{{ errors.phone }}</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address (Optional)
                    </label>
                    <input id="email" v-model="form.email" name="email" type="email" autocomplete="email"
                        :class="['appearance-none relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm',
                            errors.email ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                        placeholder="Email address" />
                    <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" v-model="form.password" name="password" :type="showPassword ? 'text' : 'password'"
                            autocomplete="new-password" required
                            :class="['appearance-none relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm pr-10',
                                errors.password ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                            placeholder="Password" />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
                    <p v-else class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Password Confirmation
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" v-model="form.password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'"
                            autocomplete="new-password" required
                            :class="['appearance-none relative block w-full px-3 py-2 border placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm pr-10',
                                errors.password_confirmation ? 'border-red-300 bg-red-50' : 'border-gray-300']"
                            placeholder="Password" />
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                            </svg>
                        </button>
                    </div>
                    <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ errors.password_confirmation }}</p>
                    <p v-else class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                </div>

                <div class="flex items-center">
                    <input id="accepted_terms" v-model="form.accepted_terms" name="accepted_terms" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="accepted_terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms</a>
                        and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a>
                    </label>
                </div>
                <p v-if="errors.accepted_terms" class="mt-1 text-xs text-red-600">{{ errors.accepted_terms }}</p>
            </div>

            <div>
                <button type="submit" :disabled="loading"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                    {{ loading ? 'Creating Account...' : 'Create Account' }}
                </button>
            </div>

            <div v-if="error" class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Already have an account?
                    <button @click="$emit('close'); $emit('openSignIn')"
                        class="font-medium text-blue-600 hover:text-blue-500 cursor-pointer ml-1">
                        Sign In
                    </button>
                </p>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { notification } from '~/utils/notification'

const auth = useAuth()
const router = useRouter()

interface RegisterForm {
    name: string
    email: string
    phone: string
    password: string
    password_confirmation: string
    accepted_terms: boolean
}

interface FormErrors {
    name?: string
    email?: string
    phone?: string
    password?: string
    password_confirmation?: string
    accepted_terms?: string
}

const form = reactive<RegisterForm>({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    accepted_terms: false
})

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)
const errors = reactive<FormErrors>({})

const validateForm = (): boolean => {
    // Clear previous errors
    Object.keys(errors).forEach(key => delete errors[key as keyof FormErrors])
    let isValid = true

    // Name validation
    if (!form.name.trim()) {
        errors.name = 'Name is required'
        isValid = false
    }

    // Phone validation (Bangladeshi mobile number)
    const phoneRegex = /^01[3-9]\d{8}$/
    if (!form.phone.trim()) {
        errors.phone = 'Phone number is required'
        isValid = false
    } else if (!phoneRegex.test(form.phone)) {
        errors.phone = 'Please enter a valid Bangladeshi mobile number (11 digits starting with 01)'
        isValid = false
    }

    // Email validation (optional)
    if (form.email.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please enter a valid email address'
        isValid = false
    }

    // Password validation
    if (!form.password) {
        errors.password = 'Password is required'
        isValid = false
    } else if (form.password.length < 8) {
        errors.password = 'Password must be at least 8 characters'
        isValid = false
    } else if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Passwords do not match'
        isValid = false
    }

    // Terms validation
    if (!form.accepted_terms) {
        errors.accepted_terms = 'You must accept the terms and conditions'
        isValid = false
    }

    return isValid
}

const handleSubmit = async () => {
    if (loading.value) return

    if (!validateForm()) {
        return
    }

    loading.value = true
    error.value = ''

    try {
        const result = await auth.register({
            name: form.name.trim(),
            email: form.email.trim() || undefined,
            phone: form.phone.trim(),
            password: form.password,
            password_confirmation: form.password_confirmation,
            accepted_terms: form.accepted_terms
        })

        if (result.success) {
            notification.success('Registration successful!')
            router.push('/dashboard')
        } else {
            // Handle API errors
            if (result.message) {
                error.value = result.message
                
                // Parse backend validation errors if available
                if (result.data?.errors) {
                    const backendErrors = result.data.errors
                    Object.keys(backendErrors).forEach((key: string) => {
                        const errorKey = key as keyof FormErrors
                        if (backendErrors[key] && Array.isArray(backendErrors[key])) {
                            errors[errorKey] = backendErrors[key][0]
                        }
                    })
                }
            } else {
                error.value = 'Registration failed'
            }
            notification.error(error.value)
        }
    } catch (err: any) {
        error.value = err.message || 'An error occurred'
        notification.error(error.value)
    } finally {
        loading.value = false
    }
}
</script>
