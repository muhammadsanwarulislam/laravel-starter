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

        <form @submit.prevent="handleSignUp">
            <!-- Name Field -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Name *
                </label>
                <input id="name" type="text" v-model="form.name" placeholder="i.e. Mohammad Yousuf" :class="[
                    'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-colors',
                    errors.name ? 'border-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500'
                ]" required @input="clearError('name')" />
                <div v-if="errors.name" class="mt-1 flex items-center space-x-1">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.name }}</p>
                </div>
                <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Enter your full name
                </p>
            </div>

            <!-- Phone Field -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Mobile Number *
                </label>
                <input id="phone" type="tel" v-model="form.phone" placeholder="i.e. 0177XXXXXX" :class="[
                    'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-colors',
                    errors.phone ? 'border-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500'
                ]" required @input="clearError('phone')" />
                <div v-if="errors.phone" class="mt-1 flex items-center space-x-1">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.phone }}</p>
                </div>
                <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Enter your 11-digit mobile number
                </p>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Email (Optional)
                </label>
                <input id="email" type="email" v-model="form.email" placeholder="i.e. mohammad.yousuf@gmail.com" :class="[
                    'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-colors',
                    errors.email ? 'border-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 dark:border-gray-600 focus:border-indigo-500'
                ]" @input="clearError('email')" />
                <div v-if="errors.email" class="mt-1 flex items-center space-x-1">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.email }}</p>
                </div>
                <p v-else class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Enter a valid email address (optional)
                </p>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password *
                </label>
                <div class="relative">
                    <input id="password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
                        placeholder="••••••••" :class="[
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
                        <span :class="form.password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                            • At least 8 characters
                        </span>
                    </p>
                </div>
            </div>

            <!-- Terms Checkbox -->
            <div class="mb-6">
                <div class="flex items-start">
                    <input id="terms" type="checkbox" v-model="form.acceptedTerms" :class="[
                        'h-4 w-4 mt-1 focus:ring-indigo-500 border rounded',
                        errors.terms ? 'border-red-500 text-red-600' : 'border-gray-300 text-indigo-600'
                    ]" required @change="clearError('terms')" />
                    <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        I agree to the <a href="#"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Terms</a>
                        and <a href="#"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Privacy
                            Policy</a> *
                    </label>
                </div>
                <div v-if="errors.terms" class="mt-1 flex items-center space-x-1 ml-6">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ errors.terms }}</p>
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
                <span>{{ isLoading ? 'Creating Account...' : 'Create Account' }}</span>
            </button>

            <!-- Sign In Link -->
            <div class="mt-6 text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300">
                    Already have an account?
                    <button @click="$emit('close'); $emit('openSignIn')"
                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer font-medium ml-1">
                        Sign In
                    </button>
                </p>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const emit = defineEmits(['close', 'openSignIn']);

// Reactive form object
const form = reactive({
    name: "",
    email: "",
    phone: "",
    password: "",
    acceptedTerms: false
});

const showPassword = ref(false);
const isLoading = ref(false);

// Initialize errors as an empty object
const errors = ref({
    name: null,
    email: null,
    phone: null,
    password: null,
    terms: null,
    general: null
});

const { signup } = useAuth();

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
        name: null,
        email: null,
        phone: null,
        password: null,
        terms: null,
        general: null
    };
};

const handleSignUp = async () => {
    isLoading.value = true;

    // Reset all errors
    resetErrors();

    // Frontend validation
    let hasErrors = false;

    if (!form.name.trim()) {
        errors.value.name = 'Name is required';
        hasErrors = true;
    } else if (form.name.length < 5) {
        errors.value.name = 'Name must be at least 5 characters';
        hasErrors = true;
    }

    if (!form.phone.trim()) {
        errors.value.phone = 'Mobile number is required';
        hasErrors = true;
    } else if (!/^01[3-9]\d{8}$/.test(form.phone)) {
        errors.value.phone = 'Please enter a valid 11-digit Bangladeshi mobile number';
        hasErrors = true;
    }

    if (form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        errors.value.email = 'Please enter a valid email address';
        hasErrors = true;
    }

    if (!form.password) {
        errors.value.password = 'Password is required';
        hasErrors = true;
    } else if (form.password.length < 8) {
        errors.value.password = 'Password must be at least 8 characters';
        hasErrors = true;
    }

    if (!form.acceptedTerms) {
        errors.value.terms = 'You must accept the terms and conditions';
        hasErrors = true;
    }

    if (hasErrors) {
        isLoading.value = false;
        return;
    }

    // Prepare API payload
    const credentials = {
        name: form.name.trim(),
        email: form.email.trim() || undefined, 
        phone: form.phone.trim(),
        password: form.password
    };


    try {
        const response = await signup(credentials);

        if (response.success) {
            const { notify } = useNotification();
            notify('Account created successfully! Please sign in.', 'success');

            // Clear form
            form.name = "";
            form.email = "";
            form.phone = "";
            form.password = "";
            form.acceptedTerms = false;

            emit('close');
            emit('openSignIn');
        } else if (response.error) {
            if (response.error.errors) {
                Object.keys(response.error.errors).forEach(field => {
                    const messages = response.error.errors[field];
                    if (Array.isArray(messages) && messages.length > 0) {
                        let fieldName = field;

                        const fieldMap = {
                            'phone': 'phone',
                            'email': 'email',
                            'name': 'name',
                            'password': 'password'
                        };

                        if (fieldMap[field]) {
                            errors.value[fieldMap[field]] = messages[0];
                        } else {
                            errors.value.general = messages[0];
                        }
                    }
                });
            }
            else if (response.error.details) {
                Object.keys(response.error.details).forEach(field => {
                    const messages = response.error.details[field];
                    if (Array.isArray(messages) && messages.length > 0) {
                        errors.value[field] = messages[0];
                    }
                });
            }
            else if (response.error.message) {
                if (response.error.message.includes('email') && response.error.message.includes('taken')) {
                    errors.value.email = 'This email is already registered';
                } else if (response.error.message.includes('phone') && response.error.message.includes('taken')) {
                    errors.value.phone = 'This phone number is already registered';
                } else {
                    errors.value.general = response.error.message;
                }
            }
            else {
                errors.value.general = 'Signup failed. Please try again.';
            }
        } else {
            errors.value.general = 'Unexpected response from server';
        }
    } catch (error) {
        console.error('Signup error:', error);
        errors.value.general = 'An error occurred. Please try again.';
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
input,
button {
    transition: all 0.2s ease-in-out;
}

input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
</style>