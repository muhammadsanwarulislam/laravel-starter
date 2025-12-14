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
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome Back</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Sign in to your account to continue</p>
        </div>

        <!-- General Error Display -->
        <div v-if="errors.general" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-red-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-red-600">{{ errors.general }}</p>
            </div>
        </div>

        <form @submit.prevent="handleSignIn">
            <!-- Login Input (Email/Phone) -->
            <div class="mb-4">
                <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ usePhone ? 'Mobile Number *' : 'Email *' }}
                </label>
                <input :type="usePhone ? 'tel' : 'email'" id="login" v-model="form.login"
                    :placeholder="usePhone ? 'i.e. 0123456789' : 'i.e. mohammad@gmail.com'" :class="[
                        'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-colors',
                        errors.login ? 'border-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500'
                    ]" required @input="clearError('login')" />
                <div v-if="errors.login" class="mt-1 flex items-center space-x-1">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.login }}</p>
                </div>
                <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ usePhone ? 'Enter your 11-digit mobile number' : 'Enter your registered email address' }}
                </p>
            </div>

            <!-- Toggle between Email and Phone -->
            <div class="flex justify-end mb-4">
                <button type="button" @click="toggleLoginMethod"
                    class="text-sm text-indigo-600 hover:text-indigo-800 cursor-pointer font-medium">
                    {{ usePhone ? "Use Email Instead" : "Use Phone Instead" }}
                </button>
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password *
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password"
                        placeholder="Enter your password" :class="[
                            'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-colors pr-10',
                            errors.password ? 'border-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500'
                        ]" required @input="clearError('password')" />
                    <button type="button" @click="togglePasswordVisibility"
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700"
                        :title="showPassword ? 'Hide password' : 'Show password'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                        </svg>
                    </button>
                </div>
                <div v-if="errors.password" class="mt-1 flex items-center space-x-1">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.password }}</p>
                </div>
                <div v-else class="mt-1">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Enter your account password
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isLoading"
                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center shadow-md">
                <svg v-if="isLoading" class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span>{{ isLoading ? 'Signing In...' : 'Sign In' }}</span>
            </button>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300">
                    Don't have an account?
                    <button @click="$emit('close'); $emit('openSignUp')"
                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer font-medium ml-1">
                        Sign up
                    </button>
                </p>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const emit = defineEmits(['close', 'openSignUp']);

// Reactive form object
const form = reactive({
    login: "",
    password: ""
});

const usePhone = ref(false);
const showPassword = ref(false);
const isLoading = ref(false);

// Initialize errors as an empty object
const errors = ref({
    login: null,
    password: null,
    general: null
});

const { signin, token } = useAuth();
const router = useRouter();

const toggleLoginMethod = () => {
    usePhone.value = !usePhone.value;
    form.login = "";
    clearError('login');
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const clearError = (fieldName) => {
    if (errors.value[fieldName]) {
        errors.value[fieldName] = null;
    }
};

// Function to reset all errors
const resetErrors = () => {
    errors.value = {
        login: null,
        password: null,
        general: null
    };
};

// Frontend validation
const validateForm = () => {
    let hasErrors = false;
    resetErrors();

    // Validate login field
    if (!form.login.trim()) {
        errors.value.login = usePhone.value ? 'Mobile number is required' : 'Email is required';
        hasErrors = true;
    } else if (usePhone.value) {
        // Validate phone format for Bangladesh
        if (!/^01[3-9]\d{8}$/.test(form.login)) {
            errors.value.login = 'Please enter a valid 11-digit Bangladeshi mobile number';
            hasErrors = true;
        }
    } else {
        // Validate email format
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.login)) {
            errors.value.login = 'Please enter a valid email address';
            hasErrors = true;
        }
    }

    // Validate password
    if (!form.password) {
        errors.value.password = 'Password is required';
        hasErrors = true;
    } else if (form.password.length < 6) {
        errors.value.password = 'Password must be at least 6 characters';
        hasErrors = true;
    }

    return !hasErrors;
};

const handleSignIn = async () => {
    resetErrors();

    if (!validateForm()) {
        return;
    }

    isLoading.value = true;

    const credentials = usePhone.value
        ? { phone: form.login.trim(), password: form.password }
        : { email: form.login.trim().toLowerCase(), password: form.password };

    try {
        const response = await signin(credentials);

        if (response.success) {
            if (token.value) {
                await new Promise(resolve => setTimeout(resolve, 100));
                emit('close');
                await navigateTo('/dashboard');
            } else {
                errors.value.general = 'Login failed. No authentication token received.';
            }
        } else {
            if (response.error) {
                // Check for Laravel validation errors
                if (response.error.errors) {
                    Object.keys(response.error.errors).forEach(field => {
                        const messages = response.error.errors[field];
                        if (Array.isArray(messages) && messages.length > 0) {
                            // Map API field names to form field names
                            if (field === 'email' || field === 'phone') {
                                errors.value.login = messages[0];
                            } else if (field === 'password') {
                                errors.value.password = messages[0];
                            } else {
                                errors.value.general = messages[0];
                            }
                        }
                    });
                }
                // Check for custom error details
                else if (response.error.details) {
                    Object.keys(response.error.details).forEach(field => {
                        const messages = response.error.details[field];
                        if (Array.isArray(messages) && messages.length > 0) {
                            if (field === 'email' || field === 'phone') {
                                errors.value.login = messages[0];
                            } else if (field === 'password') {
                                errors.value.password = messages[0];
                            } else {
                                errors.value.general = messages[0];
                            }
                        }
                    });
                }
                // Check for message property
                else if (response.error.message) {
                    // Parse common error messages
                    const errorMessage = response.error.message.toLowerCase();

                    if (errorMessage.includes('invalid credentials') ||
                        errorMessage.includes('incorrect password') ||
                        errorMessage.includes('wrong password')) {
                        errors.value.password = 'Incorrect password';
                    } else if (errorMessage.includes('user not found') ||
                        errorMessage.includes('email not found') ||
                        errorMessage.includes('phone not found')) {
                        errors.value.login = usePhone.value
                            ? 'Mobile number not registered'
                            : 'Email not registered';
                    } else if (errorMessage.includes('email') && errorMessage.includes('taken')) {
                        errors.value.login = 'Email already registered';
                    } else if (errorMessage.includes('phone') && errorMessage.includes('taken')) {
                        errors.value.login = 'Phone number already registered';
                    } else {
                        errors.value.general = response.error.message;
                    }
                }
                // Fallback
                else {
                    errors.value.general = 'Invalid credentials. Please try again.';
                }
            } else {
                errors.value.general = 'Login failed. Please try again.';
            }
        }
    } catch (error) {
        console.error('Sign in error:', error);
        errors.value.general = 'An error occurred. Please try again.';
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
/* Add some smooth transitions */
input,
button {
    transition: all 0.2s ease-in-out;
}

/* Custom focus styles */
input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
</style>