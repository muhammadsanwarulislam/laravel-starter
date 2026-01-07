<template>
    <div class="min-h-screen flex">
        <!-- Left side  -->
        <div class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 p-12">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <div class="mt-16">
                        <h1 class="text-4xl font-bold text-white leading-tight">
                            Welcome to
                            <span class="text-yellow-300">NuxtLaravel</span>
                        </h1>
                        <p class="mt-6 text-lg text-white/90">
                            Sign in to access your dashboard and manage your account.
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div v-for="n in 6" :key="n" 
                         class="h-1 rounded-full bg-white/30"
                         :class="{'bg-white/60': n % 2 === 0}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                            Sign in
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Or
                            <NuxtLink to="/auth/register"
                                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                create a new account
                            </NuxtLink>
                        </p>
                    </div>

                    <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
                        <div class="space-y-5">
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
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                        placeholder="you@example.com" />
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" v-model="form.password" name="password" type="password"
                                        autocomplete="current-password" required
                                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                                        placeholder="••••••••" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <NuxtLink to="/auth/forgot-password"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                Forgot password?
                            </NuxtLink>
                        </div>

                        <div>
                            <button type="submit" :disabled="loading"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-linear-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <UIIconsSpinner class="animate-spin h-5 w-5 text-white" />
                                </span>
                                <span class="relative">
                                    {{ loading ? 'Signing in...' : 'Sign in' }}
                                </span>
                                <svg class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>

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
                    </form>
                </div>

                <!-- Back to home link -->
                <div class="mt-6 text-center">
                    <NuxtLink to="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to home
                    </NuxtLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ["guest"], layout: "guest" });
import { notification } from '~/utils/notification'

const auth = useAuth()
const router = useRouter()

const form = reactive({
    email: '',
    password: '',
    remember: false
})

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const handleSubmit = async () => {
    if (loading.value) return

    loading.value = true
    error.value = ''

    try {
        const result = await auth.login({
            email: form.email,
            password: form.password
        })

        if (result.success) {
            notification.success('Login successful!')
            router.push('/dashboard')
        } else {
            error.value = result.message || 'Login failed'
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