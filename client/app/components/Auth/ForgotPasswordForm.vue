<template>
    <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
        <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email address
                </label>
                <div class="mt-1">
                    <input id="email" v-model="form.email" name="email" type="email" autocomplete="email" required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                        placeholder="you@example.com" />
                </div>
            </div>

            <div>
                <button type="submit" :disabled="loading || success"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
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
                    {{ loading ? 'Sending...' : success ? 'Email Sent!' : 'Send Reset Link' }}
                </button>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="rounded-md bg-red-50 dark:bg-red-900/30 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                            {{ error }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="success" class="rounded-md bg-green-50 dark:bg-green-900/30 p-4">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">
                            Check your email for a password reset link. The link will expire in 60 minutes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-sm text-center">
                <NuxtLink to="/login" class="font-medium text-blue-600 hover:text-blue-500">
                    ← Back to login
                </NuxtLink>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { notification } from '~/utils/notification'

const form = reactive({
    email: '',
})

const loading = ref(false)
const error = ref('')
const success = ref(false)

const handleSubmit = async () => {
    if (loading.value || success.value) return

    // Basic email validation
    if (!form.email || !form.email.includes('@')) {
        error.value = 'Please enter a valid email address'
        notification.error(error.value)
        return
    }

    loading.value = true
    error.value = ''

    try {
        // Call your API endpoint
        const response = await $fetch('/api/auth/forgot-password', {
            method: 'POST',
            body: { email: form.email }
        })

        if (response.success) {
            success.value = true
            notification.success('Password reset email sent successfully!')
        } else {
            error.value = response.message || 'Failed to send reset email'
            notification.error(error.value)
        }
    } catch (err: any) {
        error.value = err.data?.message || 'An error occurred while sending reset email'
        notification.error(error.value)
    } finally {
        loading.value = false
    }
}
</script>