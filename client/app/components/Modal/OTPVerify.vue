<template>
  <!-- Modal backdrop -->
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" @click="closeModal"></div>

    <!-- Modal container -->
    <div class="flex min-h-full items-center justify-center p-4">
      <!-- Modal content -->
      <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-2xl transition-all border border-gray-200 dark:border-gray-700">
        <!-- Close button -->
        <button @click="closeModal"
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Content -->
        <div class="text-center">
          <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30">
            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <h3 class="mt-4 text-lg font-semibold leading-6 text-gray-900 dark:text-white">
            Verify OTP
          </h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Enter the 6-digit verification code sent to your registered email/phone
            </p>
          </div>
        </div>

        <!-- OTP Input -->
        <div class="mt-6">
          <div class="flex justify-center space-x-3">
            <input v-for="(digit, index) in otpDigits" :key="index" v-model="otpDigits[index]" type="text"
              inputmode="numeric" pattern="[0-9]*" maxlength="1"
              @input="handleDigitInput($event, index)"
              @keydown="handleKeyDown($event, index)"
              @paste="handlePaste"
              :ref="(el) => { if (el) digitInputs[index] = el }"
              class="w-12 h-12 text-center text-2xl font-bold border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-500/30 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-all duration-200 outline-none"
              :class="{ 'border-indigo-500 ring-2 ring-indigo-200 dark:ring-indigo-500/30': focusedIndex === index }" />
          </div>

          <!-- Timer -->
          <div class="mt-4 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Code expires in 
              <span class="font-semibold" :class="timeLeft <= 30 ? 'text-red-500' : 'text-indigo-600 dark:text-indigo-400'">
                {{ formatTime(timeLeft) }}
              </span>
            </p>
          </div>

          <!-- Error message -->
          <div v-if="error" class="mt-4 rounded-lg bg-red-50 dark:bg-red-900/20 p-3">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                  clip-rule="evenodd" />
              </svg>
              <p class="text-sm text-red-800 dark:text-red-200">
                {{ error }}
              </p>
            </div>
          </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-6 flex flex-col space-y-3">
          <button @click="verifyOTP" :disabled="loading || isOtpIncomplete"
            class="w-full py-3 px-4 rounded-xl text-white font-medium bg-linear-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
            <div class="flex items-center justify-center">
              <span v-if="loading" class="mr-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
              </span>
              <span>{{ loading ? 'Verifying...' : 'Verify Code' }}</span>
            </div>
          </button>

          <button @click="resendOTP" :disabled="resendLoading || isResendDisabled"
            class="w-full py-2 px-4 rounded-xl font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
            <div class="flex items-center justify-center">
              <svg v-if="resendLoading" class="animate-spin h-4 w-4 mr-2 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              <span>
                {{ resendLoading ? 'Resending...' : (isResendDisabled ? `Resend in ${formatTime(resendCooldown)}` : 'Resend Code') }}
              </span>
            </div>
          </button>
        </div>

        <!-- Back to login -->
        <div class="mt-4 text-center">
          <button @click="goBackToLogin"
            class="text-sm font-medium text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to login
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { useAuth } from '~/composables/auth/useAuth';
import { notification } from '~/utils/notification'

const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits<{
  'update:isOpen': [value: boolean]
  'verified': []
}>()

const auth = useAuth()
const router = useRouter()

// OTP digits array (6 digits)
const otpDigits = ref<string[]>(Array(6).fill(''))
const digitInputs = ref<(HTMLInputElement | null)[]>([])
const focusedIndex = ref(0)

// Timer state
const timeLeft = ref(300) // 5 minutes in seconds
const timerInterval = ref<NodeJS.Timeout | null>(null)

// Resend cooldown
const resendCooldown = ref(60) // 60 seconds cooldown
const resendInterval = ref<NodeJS.Timeout | null>(null)

// State
const loading = ref(false)
const resendLoading = ref(false)
const error = ref('')

// Computed properties
const otpCode = computed(() => otpDigits.value.join(''))
const isOtpIncomplete = computed(() => otpDigits.value.some(digit => digit === ''))
const isResendDisabled = computed(() => resendCooldown.value > 0)

// Format time for display
const formatTime = (seconds: number) => {
  const mins = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${mins}:${secs.toString().padStart(2, '0')}`
}

// Handle digit input
const handleDigitInput = (event: Event, index: number) => {
  const input = event.target as HTMLInputElement
  const value = input.value
  
  // Only allow digits
  if (!/^\d*$/.test(value)) {
    otpDigits.value[index] = ''
    return
  }
  
  // Take only the last character if multiple digits pasted
  otpDigits.value[index] = value.slice(-1)
  
  // Move to next input if a digit was entered
  if (value && index < 5) {
    focusedIndex.value = index + 1
    nextTick(() => {
      digitInputs.value[index + 1]?.focus()
    })
  }
  
  // Auto-submit if all digits are filled
  if (index === 5 && value && !isOtpIncomplete.value) {
    verifyOTP()
  }
}

// Handle keydown events
const handleKeyDown = (event: KeyboardEvent, index: number) => {
  // Handle backspace
  if (event.key === 'Backspace') {
    if (otpDigits.value[index] === '' && index > 0) {
      // Move to previous input and clear it
      focusedIndex.value = index - 1
      nextTick(() => {
        otpDigits.value[index - 1] = ''
        digitInputs.value[index - 1]?.focus()
      })
    } else {
      // Clear current input
      otpDigits.value[index] = ''
    }
  }
  
  // Handle arrow keys
  else if (event.key === 'ArrowLeft' && index > 0) {
    focusedIndex.value = index - 1
    nextTick(() => digitInputs.value[index - 1]?.focus())
  } else if (event.key === 'ArrowRight' && index < 5) {
    focusedIndex.value = index + 1
    nextTick(() => digitInputs.value[index + 1]?.focus())
  }
}

// Handle paste
const handlePaste = (event: ClipboardEvent) => {
  event.preventDefault()
  const pastedData = event.clipboardData?.getData('text') || ''
  const digits = pastedData.replace(/\D/g, '').slice(0, 6)
  
  // Fill the OTP digits
  digits.split('').forEach((digit, index) => {
    if (index < 6) {
      otpDigits.value[index] = digit
    }
  })
  
  // Focus the last filled input or the next empty one
  const lastFilledIndex = Math.min(digits.length - 1, 5)
  const nextEmptyIndex = digits.length < 6 ? digits.length : 5
  focusedIndex.value = nextEmptyIndex
  nextTick(() => digitInputs.value[nextEmptyIndex]?.focus())
}

// Verify OTP
const verifyOTP = async () => {
  if (isOtpIncomplete.value) {
    error.value = 'Please enter all 6 digits'
    return
  }
  
  loading.value = true
  error.value = ''
  
  try {
    const result = await auth.verifyOTP(parseInt(otpCode.value))
    
    if (result.success) {
      notification.success('Login successful!')
      emit('verified')
      closeModal()
      router.push('/dashboard')
    } else {
      error.value = result.message || 'OTP verification failed'
      // Clear OTP on error
      otpDigits.value = Array(6).fill('')
      focusedIndex.value = 0
      nextTick(() => digitInputs.value[0]?.focus())
    }
  } catch (err: any) {
    error.value = err.message || 'An error occurred'
    notification.error(error.value)
  } finally {
    loading.value = false
  }
}

// Resend OTP
const resendOTP = async () => {
  if (isResendDisabled.value) return
  
  resendLoading.value = true
  error.value = ''
  
  try {
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Reset timer and start cooldown
    timeLeft.value = 300
    resendCooldown.value = 60
    
    notification.success('OTP resent successfully!')
  } catch (err: any) {
    error.value = err.message || 'Failed to resend OTP'
    notification.error(error.value)
  } finally {
    resendLoading.value = false
  }
}

// Start timers
const startTimers = () => {
  // Clear existing intervals
  if (timerInterval.value) clearInterval(timerInterval.value)
  if (resendInterval.value) clearInterval(resendInterval.value)
  
  // Start OTP timer
  timerInterval.value = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      if (timerInterval.value) clearInterval(timerInterval.value)
      error.value = 'OTP has expired. Please request a new one.'
    }
  }, 1000)
  
  // Start resend cooldown timer
  resendInterval.value = setInterval(() => {
    if (resendCooldown.value > 0) {
      resendCooldown.value--
    } else {
      if (resendInterval.value) clearInterval(resendInterval.value)
    }
  }, 1000)
}

// Go back to login
const goBackToLogin = () => {
  closeModal()
  router.push('/auth/login')
}

// Close modal
const closeModal = () => {
  emit('update:isOpen', false)
}

// Watch for modal open
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    // Reset state
    otpDigits.value = Array(6).fill('')
    timeLeft.value = 300
    resendCooldown.value = 60
    error.value = ''
    focusedIndex.value = 0
    
    // Start timers
    startTimers()
    
    // Focus first input
    nextTick(() => {
      digitInputs.value[0]?.focus()
    })
  } else {
    // Clear intervals when modal closes
    if (timerInterval.value) {
      clearInterval(timerInterval.value)
      timerInterval.value = null
    }
    if (resendInterval.value) {
      clearInterval(resendInterval.value)
      resendInterval.value = null
    }
  }
})

// Cleanup on unmount
onUnmounted(() => {
  if (timerInterval.value) clearInterval(timerInterval.value)
  if (resendInterval.value) clearInterval(resendInterval.value)
})
</script>

<style scoped>
/* Add any custom styles here */
</style>