<template>
  <ModalBase :is-open="isOpen" @close="closeModal" size="sm">
    <template #header>
      <div class="text-center">
        <div
          class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30"
        >
          <UIIconsCheck class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
        </div>
        <h3
          class="text-lg font-semibold leading-6 text-gray-900 dark:text-white"
        >
          {{ t("auth.verify_otp") }}
        </h3>
      </div>
    </template>

    <template #content>
      <div class="text-center mb-2">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          {{ t("auth.verify_otp_text") }}
          <span class="font-medium text-gray-700 dark:text-gray-300">
            {{ maskedIdentifier }}
          </span>
        </p>
      </div>

      <!-- OTP Input -->
      <div class="mb-6">
        <div class="flex justify-center space-x-2 sm:space-x-3">
          <input
            v-for="(digit, index) in otpDigits"
            :key="index"
            v-model="otpDigits[index]"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="1"
            @input="handleDigitInput($event, index)"
            @keydown="handleKeyDown($event, index)"
            @paste="handlePaste"
            @focus="focusedIndex = index"
            :ref="
              (el) => {
                if (el) digitInputs[index] = el;
              }
            "
            :disabled="loading"
            class="w-10 h-12 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-bold border-2 rounded-lg transition-all duration-200 outline-none select-none"
            :class="getInputClasses(index)"
          />
        </div>

        <!-- Timer -->
        <div class="mt-4 text-center">
          <p
            class="text-sm"
            :class="
              timer.isExpired
                ? 'text-red-500'
                : 'text-gray-600 dark:text-gray-400'
            "
          >
            Code expires in
            <span
              class="font-semibold"
              :class="
                timer.isExpired
                  ? 'text-red-500'
                  : 'text-indigo-600 dark:text-indigo-400'
              "
            >
              {{ timer.formatTime() }}
            </span>
          </p>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="space-y-3">
        <button
          @click="verifyOTP"
          :disabled="loading || !isOtpComplete || timer.isExpired"
          class="w-full py-3 px-4 rounded-xl text-white font-medium bg-linear-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
        >
          <UIIconsSpinner v-if="loading" class="animate-spin h-5 w-5 mr-2" />
          <span>{{
            loading ? "Verifying..." : t("common.button.verify_code")
          }}</span>
        </button>
      </div>
    </template>

    <template #footer>
      <div class="text-center">
        <button
          @click="goBackToLogin"
          class="text-sm font-medium text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200 inline-flex items-center"
        >
          <UIIconsArrowLeft class="bg-transparent w-4 h-4 mr-1" />
          {{ t("common.button.back") }}
        </button>
      </div>
    </template>
  </ModalBase>
</template>

<script setup lang="ts">
import { computed, watch, nextTick } from "vue";
import { useAuth } from "~/composables/auth/useAuth";
import { useOTP } from "~/composables/otp/useOTP";
import { useTimer } from "~/composables/otp/useTimer";
import { notification } from "~/utils/notification";

const props = defineProps<{
  isOpen: boolean;
  identifier?: string;
}>();

const emit = defineEmits<{
  "update:isOpen": [value: boolean];
  verified: [result: any];
}>();

const auth = useAuth();
const router = useRouter();
const { t } = useLocalization();

// Initialize OTP functionality
const {
  otpDigits,
  digitInputs,
  focusedIndex,
  otpCode,
  isComplete: isOtpComplete,
  handleDigitInput,
  handleKeyDown,
  handlePaste,
  resetOTP,
  setFocus,
} = useOTP(6);

// Timers
const timer = useTimer(300, () => {
  notification.warning("OTP has expired. Please request a new one.");
});

const resendTimer = useTimer(60);

// State
const loading = ref(false);
const resendLoading = ref(false);
const error = ref("");

// Computed properties
const maskedIdentifier = computed(() => {
  const identifier = props.identifier || auth.otpData.value.identifier;

  if (!identifier) return "your registered email/phone";

  // Mask email
  if (identifier.includes("@")) {
    const [name, domain] = identifier.split("@");
    const maskedName =
      name.length > 2
        ? name.substring(0, 2) + "*".repeat(name.length - 2)
        : "*".repeat(name.length);
    return `${maskedName}@${domain}`;
  }

  // Mask phone number
  if (identifier.replace(/\D/g, "").length >= 10) {
    const digits = identifier.replace(/\D/g, "");
    return `+*** ${digits.slice(-4)}`;
  }

  return identifier;
});

// Methods
const getInputClasses = (index: number) => {
  const baseClasses = [];

  if (focusedIndex.value === index) {
    baseClasses.push(
      "border-indigo-500",
      "ring-2",
      "ring-indigo-200",
      "dark:ring-indigo-500/30",
      "bg-white",
      "dark:bg-gray-700",
    );
  } else if (otpDigits.value[index]) {
    baseClasses.push(
      "border-green-500",
      "bg-green-50",
      "dark:bg-green-900/20",
      "text-green-700",
      "dark:text-green-300",
    );
  } else {
    baseClasses.push(
      "border-gray-300",
      "dark:border-gray-600",
      "bg-white",
      "dark:bg-gray-700",
      "text-gray-900",
      "dark:text-white",
    );
  }

  if (loading.value || timer.isExpired) {
    baseClasses.push("opacity-50", "cursor-not-allowed");
  }

  return baseClasses.join(" ");
};

const verifyOTP = async () => {
  if (!isOtpComplete.value || timer.isExpired.value) return;

  loading.value = true;
  error.value = "";

  try {
    const result = await auth.verifyOTP(otpCode.value);

    if (result.success) {
      notification.success("Login successful!");
      emit("verified", result);
      closeModal();
      // Navigate based on user role
      const user = auth.user.value;
      const redirectPath = user?.roles?.some((r: any) => r.slug === "admin")
        ? "/admin/dashboard"
        : "/dashboard";
      await router.push(redirectPath);
    } else {
      error.value = result.message || "OTP verification failed";
      notification.error(error.value);
      // Clear OTP and shake animation
      resetOTP();
      await nextTick(() => {
        digitInputs.value[0]?.focus();
        // Add shake animation class
        digitInputs.value.forEach((input) => {
          input?.classList.add("animate-shake");
          setTimeout(() => input?.classList.remove("animate-shake"), 500);
        });
      });
    }
  } catch (err: any) {
    error.value = err.message || "An error occurred";
    notification.error(error.value);
  } finally {
    loading.value = false;
  }
};

const resendOTP = async () => {
  if (!resendTimer.isExpired.value) return;

  resendLoading.value = true;

  try {
    const result = await auth.resendOTP();

    if (!result.success) {
      notification.error(
        result.message || "Failed to resend OTP. Please try again.",
      );
      return;
    }

    // Reset OTP timer and start resend cooldown
    timer.reset();
    resendTimer.reset();

    // Clear current OTP
    resetOTP();
    await nextTick(() => digitInputs.value[0]?.focus());

    notification.success(result.message || "New OTP sent successfully!");
  } catch (err: any) {
    notification.error("Failed to resend OTP. Please try again.");
  } finally {
    resendLoading.value = false;
  }
};

const goBackToLogin = () => {
  closeModal();
  router.push("/auth/login");
};

const closeModal = () => {
  emit("update:isOpen", false);
  timer.stop();
  resendTimer.stop();
};

const focusNextInput = () => {
  const nextIndex = focusedIndex.value < 5 ? focusedIndex.value + 1 : 5;
  setFocus(nextIndex);
  nextTick(() => digitInputs.value[nextIndex]?.focus());
};

// Watch for OTP completion
watch(otpCode, (newCode) => {
  if (newCode.length === 6 && !loading.value && !timer.isExpired.value) {
    // Auto-verify after short delay
    setTimeout(() => verifyOTP(), 300);
  }
});

// Watch for modal open/close
watch(
  () => props.isOpen,
  (isOpen) => {
    if (isOpen) {
      // Reset state
      resetOTP();
      error.value = "";
      loading.value = false;
      resendLoading.value = false;

      // Start timers
      timer.start();
      resendTimer.start();

      // Focus first input
      nextTick(() => digitInputs.value[0]?.focus());
    } else {
      // Clean up
      timer.stop();
      resendTimer.stop();
    }
  },
  { immediate: true },
);

// Auto-focus when focusedIndex changes
watch(focusedIndex, (newIndex) => {
  nextTick(() => digitInputs.value[newIndex]?.focus());
});
</script>

<style scoped>
/* Add shake animation */
@keyframes shake {
  0%,
  100% {
    transform: translateX(0);
  }

  25% {
    transform: translateX(-5px);
  }

  75% {
    transform: translateX(5px);
  }
}

.animate-shake {
  animation: shake 0.3s ease-in-out;
}

/* Smooth transitions for OTP inputs */
input {
  transition: all 0.2s ease;
}

input:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
