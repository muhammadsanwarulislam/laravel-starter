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

        <form @submit.prevent="handleSignIn">
            <div class="mb-4">
                <CommonInputField id="login" :label="usePhone ? 'Mobile Number' : 'Email'"
                    :type="usePhone ? 'tel' : 'email'"
                    :placeholder="usePhone ? 'i.e. 0177XXXXX' : 'i.e. yousuf@gmail.com'" v-model="loginInput"
                    :errorMessage="errors.login ? errors.login[0] : ''" required />
            </div>

            <div class="flex justify-end mb-2">
                <button type="button" @click="toggleLoginMethod"
                    class="text-sm text-whitecursor-pointer hover:underline">
                    {{ usePhone ? "Use Email Instead" : "Use Phone Instead" }}
                </button>
            </div>

            <div class="mb-6 relative">
                <CommonInputField id="password" label="Password" :type="showPassword ? 'text' : 'password'"
                    placeholder="Enter your password" v-model="password"
                    :errorMessage="errors.password ? errors.password[0] : ''" required />
                <button type="button" @click="togglePasswordVisibility"
                    class="absolute top-8 right-2 text-fuchsia-500 hover:text-fuchsia-800 focus:outline-none">
                    <!-- <Icon :name="showPassword ? 'heroicons:eye' : 'heroicons:eye-slash'" class="w-5 h-5" /> -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            :d="showPassword ? 'M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10S6.477 3 12 3c2.132 0 4.11.658 5.748 1.786m-1.873 3.031A3.001 3.001 0 0112 15a3.001 3.001 0 01-2.875-4.183m6.623-.908l1.415-1.414M14.25 9l1.415-1.414M3 3l18 18' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'" />
                    </svg>
                </button>
            </div>

            <CommonButton :buttonText="'Sign In'" class="cursor-pointer" :isLoading="isLoading" :isSuccess="isSuccess" type="submit" />

            <div class="mt-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">
                    Don't have an account?
                    <a @click="$emit('close'); $emit('openSignUp')"
                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'openSignUp']);

const loginInput = ref("");
const password = ref("");
const errors = ref({});
const usePhone = ref(false);
const showPassword = ref(false);

const isLoading = ref(false);
const isSuccess = ref(false);

const { signin, user, token } = useAuth();
const route = useRoute();

const toggleLoginMethod = () => {
  usePhone.value = !usePhone.value;
  loginInput.value = "";
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const handleSignIn = async () => {
  isLoading.value = true;
  isSuccess.value = false;
  errors.value = {};

  const credentials = usePhone.value
    ? { phone: loginInput.value, password: password.value }
    : { email: loginInput.value, password: password.value };

  const response = await signin(credentials);

  if (response.success || response.data?.code === 200) {
    isSuccess.value = true;
    
    setTimeout(() => {
      emit('close');
      navigateTo('/dashboard', { replace: true });
    }, 100);
  } else {
    errors.value = response.error || {};
    isLoading.value = false;
  }
  
};
</script>