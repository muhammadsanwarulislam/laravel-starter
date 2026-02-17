<template>
    <div class="min-h-screen flex">
        <!-- Left side -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-lg">
                <div
                    class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                            Create Account
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Join us today and get started
                        </p>
                    </div>

                    <form @submit.prevent="handleSubmit" class="mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <!-- Name -->
                            <div class="col-span-full">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UIIconsUser class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input id="name" v-model="form.name" name="name" type="text" required
                                        @input="validateNameOnInput"
                                        :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                            errors.name ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                        placeholder="John Doe" />
                                </div>
                                <p v-if="errors.name"
                                    class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <UIIconsError class="w-4 h-4 mr-1" />
                                    {{ errors.name }}
                                </p>
                                <p v-else-if="form.name.trim() && /^[A-Za-z]/.test(form.name.trim())"
                                    class="mt-2 text-xs text-green-600 dark:text-green-400 flex items-center">
                                    <UIIconsTick class="w-4 h-4 mr-1" />
                                    Valid name format
                                </p>
                            </div>

                            <!-- Phone with Country Code -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Phone Number
                                </label>
                                <div class="flex gap-2">
                                    <!-- Country Code Selector -->
                                    <div class="relative flex-1 max-w-xs">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <UIIconsPhone class="h-5 w-5 text-gray-400" />
                                        </div>
                                        <select v-model="form.country_code_id"
                                            :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200 appearance-none',
                                                errors.country_code_id ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']">
                                            <option v-for="country in countryCodes" :key="country.id" :value="country.id">
                                                {{ country.dial_code }} {{ country.name }}
                                            </option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                            <UIIconsDropDown class="h-5 w-5 text-gray-400" />
                                        </div>
                                    </div>
                                    
                                    <!-- Phone Number Input -->
                                    <div class="relative flex-1">
                                        <input id="phone" v-model="form.phone" name="phone" type="tel" required
                                            :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                                errors.phone ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                            placeholder="01XXXXXXXXX" />
                                    </div>
                                </div>
                                <p v-if="errors.phone" class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <UIIconsError class="w-4 h-4 mr-1" />
                                    {{ errors.phone }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address (Optional)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UIIconsEmail class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input id="email" v-model="form.email" name="email" type="email"
                                        autocomplete="email"
                                        :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                            errors.email ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                        placeholder="you@example.com" />
                                </div>
                                <p v-if="errors.email"
                                    class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <UIIconsError class="w-4 h-4 mr-1" />
                                    {{ errors.email }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UIIconsPassword class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input id="password" v-model="form.password" name="password"
                                        :type="showPassword ? 'text' : 'password'" autocomplete="new-password" required
                                        :class="['block w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                            errors.password ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                        placeholder="••••••••" />
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                                        </svg>
                                    </button>
                                </div>
                                <p v-if="errors.password"
                                    class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <UIIconsError class="w-4 h-4 mr-1" />
                                    {{ errors.password }}
                                </p>
                                <p v-else class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Must be at least 8 characters
                                </p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <UIIconsConfirmPassword class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input id="password_confirmation" v-model="form.password_confirmation"
                                        name="password_confirmation" :type="showPassword ? 'text' : 'password'"
                                        autocomplete="new-password" required
                                        :class="['block w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                            errors.password_confirmation ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                        placeholder="••••••••" />
                                </div>
                                <p v-if="errors.password_confirmation"
                                    class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <UIIconsError class="w-4 h-4 mr-1" />
                                    {{ errors.password_confirmation }}
                                </p>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="accepted_terms" v-model="form.accepted_terms" name="accepted_terms"
                                    type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded transition duration-200" />
                            </div>
                            <div class="ml-3">
                                <label for="accepted_terms" class="text-sm text-gray-700 dark:text-gray-300">
                                    I agree to the
                                    <a href="#"
                                        class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                        Terms
                                    </a>
                                    and
                                    <a href="#"
                                        class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                        Privacy Policy
                                    </a>
                                </label>
                                <p v-if="errors.accepted_terms"
                                    class="mt-1 text-xs text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ errors.accepted_terms }}
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" :disabled="loading"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-linear-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="relative">
                                    {{ loading ? 'Creating Account...' : 'Create Account' }}
                                </span>
                                <svg class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error"
                            class="rounded-xl bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                        {{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                    Already have an account?
                                </span>
                            </div>
                        </div>

                        <!-- Sign In Link -->
                        <div class="text-center">
                            <NuxtLink to="/auth/login"
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Sign in to your account
                            </NuxtLink>
                        </div>
                    </form>
                </div>

                <!-- Back to home link -->
                <div class="mt-6 text-center">
                    <NuxtLink to="/"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to home
                    </NuxtLink>
                </div>
            </div>
        </div>

        <!-- Right side - Illustration (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-green-500 via-emerald-500 to-teal-500 p-12">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">Join Us</span>
                    </div>
                    <div class="mt-16">
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Start Your Journey<br>
                            <span class="text-yellow-300">Today</span>
                        </h1>
                        <ul class="mt-8 space-y-4">
                            <li v-for="benefit in benefits" :key="benefit"
                                class="flex items-center text-lg text-white/90">
                                <svg class="w-6 h-6 mr-3 text-green-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ benefit }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-3 mt-8">
                    <div v-for="n in 8" :key="n" class="h-2 rounded-full bg-white/30"
                        :class="{ 'bg-white/60': n % 3 === 0 }"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ["guest"], layout: "guest" });
import { useAuth } from '~/composables/auth/useAuth';
import { notification } from '~/utils/notification'

const auth = useAuth()
const router = useRouter()

interface RegisterForm {
    name: string
    email: string
    country_code_id: number
    phone: string
    password: string
    password_confirmation: string
    accepted_terms: boolean
}

interface FormErrors {
    name?: string
    email?: string
    country_code_id?: number
    phone?: string
    password?: string
    password_confirmation?: string
    accepted_terms?: string
}

const form = reactive<RegisterForm>({
    name: '',
    email: '',
    country_code_id: 1,
    phone: '',
    password: '',
    password_confirmation: '',
    accepted_terms: false
})

const countryCodes = ref([])

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)
const errors = reactive<FormErrors>({})

const benefits = [
    'Connect with for free and flexible',
    'Explore the latest features',
    'Start your projects quickly',
    'Collaborate with others',
    'Access exclusive resources',
]

const validateForm = (): boolean => {
    Object.keys(errors).forEach(key => delete errors[key as keyof FormErrors])
    let isValid = true

    const nameRegex = /^[A-Za-z][A-Za-z0-9]*([-_ ][A-Za-z0-9]+)*$/
    
    if (!form.name.trim()) {
        errors.name = 'Name is required'
        isValid = false
    } else if (!nameRegex.test(form.name.trim())) {
        errors.name = 'Name must start with a letter and can contain letters, numbers, hyphens, underscores, or spaces'
        isValid = false
    } else if (/^\d+$/.test(form.name.trim())) {
        errors.name = 'Name cannot be only numbers'
        isValid = false
    } else if (/^[-_0-9]/.test(form.name.trim())) {
        errors.name = 'Name cannot start with numbers or special characters'
        isValid = false
    }

    if (!form.country_code_id) {
        errors.country_code_id = 'Country code is required'
        isValid = false
    }

    // Update phone validation based on selected country code
    const phoneRegex = /^01[3-9]\d{8}$/
    if (!form.phone.trim()) {
        errors.phone = 'Phone number is required'
        isValid = false
    } else if (form.country_code_id === 1) {
        if (!phoneRegex.test(form.phone)) {
            errors.phone = 'Please enter a valid Bangladeshi mobile number (11 digits starting with 01)'
            isValid = false
        }
    } else {
        // Generic validation for other countries
        if (!/^\d{6,15}$/.test(form.phone.replace(/\s+/g, ''))) {
            errors.phone = 'Please enter a valid phone number'
            isValid = false
        }
    }

    if (form.email.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please enter a valid email address'
        isValid = false
    }

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

    if (!form.accepted_terms) {
        errors.accepted_terms = 'You must accept the terms and conditions'
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
            delete errors.name
        }
    } else {
        delete errors.name
    }
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
            country_code_id: form.country_code_id,
            phone: form.phone.trim(),
            password: form.password,
            password_confirmation: form.password_confirmation,
            accepted_terms: form.accepted_terms
        })

        if (result.success) {
            notification.success('Registration successful!')
            router.push('/dashboard')
        } else {
            if (result.message) {
                error.value = result.message

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

onMounted(async () => {
    try {
        const result = await auth.fetchCountryCodes()
        if (result.success) {
            countryCodes.value = result.data
        }
    } catch (error) {
        console.error('Failed to fetch country codes:', error)
    }
})
</script>