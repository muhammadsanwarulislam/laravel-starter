<template>
  <div class="min-h-screen flex">
    <!-- Left side  -->
    <div
      class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 p-12"
    >
      <div class="flex flex-col justify-between h-full">
        <div>
          <div class="mt-16">
            <h1 class="text-4xl font-bold text-white leading-tight">
              {{ t("user.login.form.welcome") }}
              <span class="text-yellow-300">NuxtLaravel</span>
            </h1>
            <p class="mt-6 text-lg text-white/90">
              {{ t("user.login.form.subtitle") }}
            </p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-8">
          <div
            v-for="n in 6"
            :key="n"
            class="h-1 rounded-full bg-white/30"
            :class="{ 'bg-white/60': n % 2 === 0 }"
          ></div>
        </div>
      </div>
    </div>

    <!-- Right side  -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div
          class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 dark:border-gray-700"
        >
          <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
              {{ t("button.sign_in") }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              {{ t("common.or") }}
              <NuxtLink
                to="/auth/register"
                class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
              >
                {{ t("user.registration.form.title") }}
              </NuxtLink>
            </p>
          </div>

          <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
            <div class="space-y-5">
              <!-- Identifier Input (Email or Phone) -->
              <div>
                <label
                  for="identifier"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                  {{ t("user.login.form.emailOrPhone") }}
                </label>
                <div class="relative">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <UIIconsEmail
                      v-if="isEmail"
                      class="h-5 w-5 text-gray-400"
                    />
                    <UIIconsPhone v-else class="h-5 w-5 text-gray-400" />
                  </div>
                  <input
                    id="identifier"
                    v-model="form.identifier"
                    name="identifier"
                    type="text"
                    autocomplete="email"
                    :placeholder="
                      isEmail ? 'you@example.com' : '+880 1XXXXXXXXX'
                    "
                    required
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                    @input="handleIdentifierInput"
                    @blur="handleIdentifierBlur"
                  />
                  <div
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                  >
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                      {{ isEmail ? t("common.email") : t("common.phone") }}
                    </span>
                  </div>
                </div>
                <p
                  v-if="form.identifier && !isEmail && !isValidPhone"
                  class="mt-1 text-xs text-red-500"
                >
                  Please enter a valid phone number (e.g., 01711111111)
                </p>
              </div>

              <!-- Password Input -->
              <div>
                <label
                  for="password"
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                >
                  {{ t("user.registration.form.password") }}
                </label>
                <div class="relative">
                  <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                  >
                    <UIIconsPassword class="h-5 w-5 text-gray-400" />
                  </div>
                  <input
                    id="password"
                    v-model="form.password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    required
                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    @click="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500"
                  >
                    <UIIconsEye v-if="showPassword" class="h-5 w-5" />
                    <UIIconsEyeOff v-else class="h-5 w-5" />
                  </button>
                </div>
              </div>

              <div>
                <UIButton
                  type="submit"
                  variant="gradient"
                  size="lg"
                  rounded="lg"
                  shadow="lg"
                  :disabled="
                    loading || (form.identifier && !isEmail && !isValidPhone)
                  "
                  class="w-full"
                >
                  <template #icon>
                    <UIIconsSpinner
                      v-if="loading"
                      class="animate-spin h-5 w-5 text-white"
                    />
                    <UIIconsArrowRight v-else class="h-5 w-5" />
                  </template>
                  {{
                    loading ? t("common.loading.signin") : t("button.sign_in")
                  }}
                </UIButton>
              </div>
            </div>
          </form>
        </div>

        <!-- Back to home link -->
        <div class="mt-6 text-center">
          <NuxtLink
            to="/"
            class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors duration-200 inline-flex items-center"
          >
            <UIIconsArrowLeft2 class="bg-transparent w-4 h-4 mr-1" />
            {{ t("common.button.backToHome") }}
          </NuxtLink>
        </div>
      </div>
    </div>

    <ModalOTPVerify
      v-if="showOTPModal"
      :is-open="showOTPModal"
      :identifier="auth.otpData.identifier || undefined"
      @update:is-open="showOTPModal = $event"
      @verified="onOTPVerified"
    />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: ["guest"], layout: "guest" });

import { useAuth } from "~/composables/auth/useAuth";
import { notification } from "~/utils/notification";

const { t } = useLocalization();
const auth = useAuth();
const router = useRouter();

// Form state
const form = reactive({
  identifier: "",
  password: "",
});

// UI state
const loading = ref(false);
const error = ref("");
const showPassword = ref(false);
const showOTPModal = ref(false);

// Validation state
const isEmail = ref(true);
const isValidPhone = ref(true);

const handleIdentifierInput = () => {
  error.value = "";

  const { isEmail: detectedIsEmail, isValid } = auth.detectIdentifierType(
    form.identifier,
  );
  isEmail.value = detectedIsEmail;
  isValidPhone.value = isValid;

  if (!detectedIsEmail && form.identifier) {
    setTimeout(() => {
      const cleaned = auth.cleanPhone(form.identifier);
      if (cleaned && cleaned !== form.identifier.replace(/\D/g, "")) {
        form.identifier = cleaned;
      }
    }, 300);
  }
};

const handleIdentifierBlur = () => {
  if (!isEmail.value && form.identifier) {
    const cleaned = auth.cleanPhone(form.identifier);
    if (cleaned) {
      form.identifier = cleaned;
    }
  }

  const { isEmail: detectedIsEmail, isValid } = auth.detectIdentifierType(
    form.identifier,
  );
  isEmail.value = detectedIsEmail;
  isValidPhone.value = isValid;
};

const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const handleSubmit = async () => {
  if (loading.value) return;

  if (!form.identifier.trim()) {
    error.value = "Please enter email or phone number";
    return;
  }

  if (!form.password) {
    error.value = "Please enter password";
    return;
  }

  if (!isEmail.value && !isValidPhone.value) {
    error.value = "Please enter a valid phone number (e.g., 01711111111)";
    return;
  }

  loading.value = true;
  error.value = "";

  try {
    const credentials: any = {
      password: form.password,
    };
    if (isEmail.value) {
      credentials.email = form.identifier.trim();
    } else {
      const phoneNumber = auth.cleanPhone(form.identifier);
      if (!phoneNumber.startsWith("01") || phoneNumber.length !== 11) {
        throw new Error(
          "Invalid phone number format. Please use format: 01XXXXXXXXX",
        );
      }
      credentials.phone = phoneNumber;
    }

    const result = await auth.login(credentials);

    if (result.success && result.otpRequired) {
      showOTPModal.value = true;
      notification.success(t("auth.otp_sent"));
    } else if (result.success) {
      notification.success(t("auth.login.success"));
      router.push("/dashboard");
    } else {
      notification.error(t("auth.login.failed"));
    }
  } catch (err: any) {
    error.value = err.message || "An error occurred";
    notification.error(error.value);
  } finally {
    loading.value = false;
  }
};

const onOTPVerified = async (result: any) => {
  if (result.success) {
    showOTPModal.value = false;
    notification.success(t("auth.otp_verified"));
    await router.push("/dashboard");
  } else {
    notification.error(t("auth.otp_verification_failed"));
  }
};

watch(
  () => form.identifier,
  () => {
    error.value = "";
  },
);

onMounted(() => {
  auth.initialize();
});
</script>

<style scoped></style>
