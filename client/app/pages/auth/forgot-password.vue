<template>
    <div class="min-h-screen flex">
        <!-- Left side - Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <UIIconsLogo class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">NuxtLaravel</span>
                </div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 dark:border-gray-700">
                    <!-- Progress Steps -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-linear-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    1
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-600 dark:text-gray-400">Request</span>
                            </div>
                            <div class="w-16 h-1 bg-gray-300 dark:bg-gray-600"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold">
                                    2
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-400 dark:text-gray-500">Email</span>
                            </div>
                            <div class="w-16 h-1 bg-gray-300 dark:bg-gray-600"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold">
                                    3
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-400 dark:text-gray-500">Reset</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-linear-to-r from-yellow-100 to-yellow-50 dark:from-yellow-900/20 dark:to-yellow-800/10">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                            Forgot Password?
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>
                    </div>

                    <!-- Success Message -->
                    <div v-if="success" class="rounded-xl bg-green-50 dark:bg-green-900/20 p-4 mb-6 border border-green-200 dark:border-green-800">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ successMessage }}
                                </p>
                                <p class="mt-1 text-xs text-green-700 dark:text-green-300">
                                    Check your email for the reset link. Didn't receive it? 
                                    <button @click="resendEmail" class="font-medium underline hover:text-green-900 dark:hover:text-green-100">
                                        Resend
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form v-else @submit.prevent="handleSubmit" class="mt-8 space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input id="email" v-model="form.email" name="email" type="email" autocomplete="email" required
                                    :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                        errors.email ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                    placeholder="you@example.com" />
                            </div>
                            <p v-if="errors.email" class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ errors.email }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        What to expect
                                    </p>
                                    <ul class="mt-1 text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            A password reset link will be sent to your email
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            The link will expire in 1 hour
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Check your spam folder if you don't see it
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" :disabled="loading"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-linear-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span class="relative">
                                    {{ loading ? 'Sending Reset Link...' : 'Send Reset Link' }}
                                </span>
                                <svg class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error" class="rounded-xl bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
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
                                    Remember your password?
                                </span>
                            </div>
                        </div>

                        <!-- Sign In Link -->
                        <div class="text-center">
                            <NuxtLink to="/auth/login"
                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Back to Sign In
                            </NuxtLink>
                        </div>
                    </form>
                </div>

                <!-- Support Contact -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Need help?
                        <a href="mailto:support@nuxtlaravel.com" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                            Contact Support
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right side - Illustration (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-rose-500 via-pink-500 to-red-500 p-12">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">Security First</span>
                    </div>
                    <div class="mt-16">
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Secure Account<br>
                            <span class="text-yellow-300">Recovery</span>
                        </h1>
                        <ul class="mt-8 space-y-4">
                            <li v-for="tip in securityTips" :key="tip" class="flex items-start text-lg text-white/90">
                                <svg class="w-6 h-6 mr-3 text-pink-300 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ tip }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-8">
                    <div class="flex space-x-2">
                        <div v-for="n in 6" :key="n" 
                             class="w-2 h-8 rounded-full bg-white/30"
                             :class="{'bg-white/60': n % 2 === 0}"></div>
                    </div>
                    <div class="text-white/80 text-sm">
                        🔐 Secure Process
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ["guest"], layout: "guest" });

import { notification } from '~/utils/notification'

const auth = useAuth()

interface ForgotPasswordForm {
    email: string
}

interface FormErrors {
    email?: string
}

const form = reactive(<ForgotPasswordForm>{
    email: ''
})

const loading = ref(false)
const error = ref('')
const success = ref(false)
const errors = reactive<FormErrors>({})
const successMessage = ref('')

const securityTips = [
    'We never ask for your password',
    'Reset links expire in 1 hour',
    'Emails are sent from our secure servers',
    'Check spam folder if not received',
    'Use strong, unique passwords',
    'Enable two-factor authentication'
]

const validateForm = (): boolean => {
    delete errors.email
    let isValid = true

    if (!form.email.trim()) {
        errors.email = 'Email is required'
        isValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.email = 'Please enter a valid email address'
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
    success.value = false

    try {
        const result = await auth.forgetPassword({
            email: form.email.trim()
        })

        if (result.success) {
            successMessage.value = `Reset link has been sent to ${form.email}. Please check your inbox.`
            success.value = true
            notification.success('Password reset email sent successfully!')
        } else {
            error.value = result.message || 'Failed to send reset email'
            notification.error(error.value)
        }
    } catch (err: any) {
        error.value = err.message || 'An error occurred while sending reset email'
        notification.error(error.value)
    } finally {
        loading.value = false
    }
}

const resendEmail = () => {
    success.value = false
    handleSubmit()
}
</script>