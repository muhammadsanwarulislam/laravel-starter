<template>
    <div class="min-h-screen flex">
        <!-- Left side - Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <UIIconsLogo class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">NuxtLaravel</span>
                </div>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div
                    class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 dark:border-gray-700">
                    <!-- Progress Steps -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-600 dark:text-gray-400">Request</span>
                            </div>
                            <div class="w-16 h-1 bg-linear-to-r from-indigo-500 to-purple-600"></div>
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-600 dark:text-gray-400">Email</span>
                            </div>
                            <div class="w-16 h-1 bg-linear-to-r from-indigo-500 to-purple-600"></div>
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-10 h-10 rounded-full bg-linear-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    3
                                </div>
                                <span class="mt-2 text-xs font-medium text-gray-600 dark:text-gray-400">Reset</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <div
                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-linear-to-r from-green-100 to-green-50 dark:from-green-900/20 dark:to-green-800/10">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                            Reset Password
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Enter your new password below.
                        </p>
                    </div>

                    <!-- Success Message -->
                    <div v-if="success"
                        class="rounded-xl bg-green-50 dark:bg-green-900/20 p-4 mb-6 border border-green-200 dark:border-green-800">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    Password reset successfully!
                                </p>
                                <p class="mt-1 text-xs text-green-700 dark:text-green-300">
                                    You can now sign in with your new password.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form v-else @submit.prevent="handleSubmit" class="mt-8 space-y-6">
                        <!-- Token (hidden) -->
                        <input type="hidden" v-model="form.token" />

                        <!-- Email -->
                        <div v-if="!emailFromQuery">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email address
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
                                    required
                                    :class="['block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                        errors.email ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                    placeholder="you@example.com" />
                            </div>
                            <p v-if="errors.email"
                                class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ errors.email }}
                            </p>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password
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
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ errors.password }}
                            </p>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                                <div :class="form.password.length >= 8 ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex items-center">
                                    <svg class="w-3 h-3 mr-1"
                                        :class="form.password.length >= 8 ? 'text-green-500' : 'text-gray-400'"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    8+ characters
                                </div>
                                <div :class="/[A-Z]/.test(form.password) ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex items-center">
                                    <svg class="w-3 h-3 mr-1"
                                        :class="/[A-Z]/.test(form.password) ? 'text-green-500' : 'text-gray-400'"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Uppercase
                                </div>
                                <div :class="/\d/.test(form.password) ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex items-center">
                                    <svg class="w-3 h-3 mr-1"
                                        :class="/\d/.test(form.password) ? 'text-green-500' : 'text-gray-400'"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Number
                                </div>
                                <div :class="/\W/.test(form.password) ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                                    class="flex items-center">
                                    <svg class="w-3 h-3 mr-1"
                                        :class="/\W/.test(form.password) ? 'text-green-500' : 'text-gray-400'"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Symbol
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password
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
                                    autocomplete="new-password" required
                                    :class="['block w-full pl-10 pr-10 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200',
                                        errors.password_confirmation ? 'border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600']"
                                    placeholder="••••••••" />
                            </div>
                            <p v-if="errors.password_confirmation"
                                class="mt-2 text-xs text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ errors.password_confirmation }}
                            </p>
                        </div>

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
                                    {{ loading ? 'Resetting Password...' : 'Reset Password' }}
                                </span>
                                <svg class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
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
                                    Remember your password?
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
                                Back to Sign In
                            </NuxtLink>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right side - Illustration -->
        <div class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-teal-500 via-cyan-500 to-blue-500 p-12">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">New Start</span>
                    </div>
                    <div class="mt-16">
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Fresh Start<br>
                            <span class="text-yellow-300">Secure Future</span>
                        </h1>
                        <ul class="mt-8 space-y-4">
                            <li v-for="tip in passwordTips" :key="tip" class="flex items-start text-lg text-white/90">
                                <svg class="w-6 h-6 mr-3 text-cyan-300 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ tip }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-8">
                    <div class="flex space-x-2">
                        <div v-for="n in 6" :key="n" class="w-8 h-2 rounded-full bg-white/30"
                            :class="{ 'bg-white/60': n % 2 === 0 }"></div>
                    </div>
                    <div class="text-white/80 text-sm">
                        🛡️ Password Strength
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: ["guest"],
    layout: "guest"
});

import { notification } from '~/utils/notification'

const auth = useAuth()
const router = useRouter()
const route = useRoute()

interface FormErrors {
    email?: string
    password?: string
    password_confirmation?: string
    token?: string
}

interface ResetPasswordForm {
    email: string
    password: string
    password_confirmation: string
    token: string
}

const form = reactive<ResetPasswordForm>({
    email: route.query.email as string || '',
    password: '',
    password_confirmation: '',
    token: route.query.token as string || ''
})

const loading = ref(false)
const error = ref('')
const success = ref(false)
const showPassword = ref(false)
const errors = reactive<FormErrors>({})

const emailFromQuery = computed(() => route.query.email)

const passwordTips = [
    'Use at least 8 characters',
    'Include uppercase letters',
    'Add numbers for strength',
    'Special characters recommended',
    'Avoid common passwords',
    'Consider using a passphrase'
]

const validateForm = (): boolean => {
    Object.keys(errors).forEach(key => delete errors[key as keyof FormErrors])
    let isValid = true

    if (!form.email.trim()) {
        errors.email = 'Email is required'
        isValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
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

    if (!form.token) {
        errors.token = 'Reset token is invalid or expired'
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
        const result = await auth.resetPassword({
            email: form.email.trim(),
            password: form.password,
            password_confirmation: form.password_confirmation,
            token: form.token
        })

        if (result.success) {
            success.value = true
            notification.success('Password reset successfully!')

            setTimeout(() => {
                router.push('/auth/login')
            }, 3000)
        } else {
            error.value = result.message || 'Failed to reset password'
            notification.error(error.value)
        }
    } catch (err: any) {
        error.value = err.message || 'An error occurred while resetting password'
        notification.error(error.value)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    if (route.query.email) {
        form.email = route.query.email as string
    }
    if (route.query.token) {
        form.token = route.query.token as string
    }
})
</script>