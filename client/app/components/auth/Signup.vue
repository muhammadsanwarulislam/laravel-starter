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

        <form @submit.prevent="handleSignUp">
            <div class="mb-4">
                <CommonInputField id="name" label="Name" type="text" placeholder="i.e. Mohammad Yousuf" v-model="name"
                    :errorMessage="errors.name ? errors.name[0] : ''" required />
            </div>

            <div class="mb-4">
                <CommonInputField id="phone" label="Mobile Number" v-model="phone" type="text" placeholder="i.e. 177XXXXX"
                    :errorMessage="errors.phone ? errors.phone[0] : ''" :isSelect="false" required />
            </div>
            <div class="mb-4">
                <CommonInputField id="email" label="Email(Optional)" type="email" placeholder="i.e. mohammad.yousuf@gmail"
                    v-model="email" :errorMessage="errors.email ? errors.email[0] : ''" />
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2" for="password">
                    Password
                </label>
                <input id="password" type="password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="••••••••" required />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Must be at least 8 characters</p>
            </div>

            <div class="mb-6">
                <div class="flex items-center">
                    <input id="terms" type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" required />
                    <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        I agree to the <a href="#"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Terms</a>
                        and <a href="#"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Privacy
                            Policy</a>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition font-medium">
                Create Account
            </button>

            <div class="mt-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">
                    Already have an account?
                    <a @click="$emit('close'); $emit('openSignIn')"
                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer">
                        Signin
                    </a>
                </p>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'openSignIn']);

const name = ref("");
const email = ref("");
const phone = ref("");
const selectedCountryCode = ref("");
const countryCodeOptions = ref([]);

const { signup } = useAuth();
const errors = ref({});

const isLoading = ref(false);
const isSuccess = ref(false);

const handleSignUp = async () => {
    showSignUp.value = false;

    isLoading.value = true;
    isSuccess.value = false;
    errors.value = {};

    const credentials = {
        name: name.value,
        email: email.value,
        phone: `${phone.value}`,
    };
    const response = await signup(credentials);

    if (response.error) {
        errors.value = response.error.details || {};
        isLoading.value = false;
        isSuccess.value = false;
        return false;
    }
};
</script>