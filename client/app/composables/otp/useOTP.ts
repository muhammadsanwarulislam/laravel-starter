import { ref, computed } from 'vue'

export const useOTP = (length: number = 6) => {
  const otpDigits = ref<string[]>(Array(length).fill(''))
  const digitInputs = ref<(HTMLInputElement | null)[]>([])
  const focusedIndex = ref(0)

  const otpCode = computed(() => otpDigits.value.join(''))
  const isComplete = computed(() => !otpDigits.value.some(digit => digit === ''))

  const handleDigitInput = (event: Event, index: number) => {
    const input = event.target as HTMLInputElement
    const value = input.value
    
    // Only allow digits
    if (!/^\d*$/.test(value)) {
      otpDigits.value[index] = ''
      return
    }
    
    // Take only the last character
    otpDigits.value[index] = value.slice(-1)
    
    // Move to next input if a digit was entered
    if (value && index < length - 1) {
      focusedIndex.value = index + 1
    }
  }

  const handleKeyDown = (event: KeyboardEvent, index: number) => {
    switch (event.key) {
      case 'Backspace':
        if (otpDigits.value[index] === '' && index > 0) {
          // Move to previous input
          focusedIndex.value = index - 1
          otpDigits.value[index - 1] = ''
        } else {
          // Clear current input
          otpDigits.value[index] = ''
        }
        break
        
      case 'ArrowLeft':
        if (index > 0) {
          focusedIndex.value = index - 1
        }
        break
        
      case 'ArrowRight':
        if (index < length - 1) {
          focusedIndex.value = index + 1
        }
        break
    }
  }

  const handlePaste = (event: ClipboardEvent, startIndex: number = 0) => {
    event.preventDefault()
    const pastedData = event.clipboardData?.getData('text') || ''
    const digits = pastedData.replace(/\D/g, '').slice(0, length)
    
    // Fill the OTP digits starting from current index
    for (let i = 0; i < Math.min(digits.length, length - startIndex); i++) {
      otpDigits.value[startIndex + i] = digits[i]
    }
    
    // Update focused index
    const nextEmptyIndex = Math.min(digits.length + startIndex, length - 1)
    focusedIndex.value = nextEmptyIndex
  }

  const resetOTP = () => {
    otpDigits.value = Array(length).fill('')
    focusedIndex.value = 0
  }

  const setFocus = (index: number) => {
    focusedIndex.value = index
  }

  return {
    otpDigits,
    digitInputs,
    focusedIndex,
    otpCode,
    isComplete,
    handleDigitInput,
    handleKeyDown,
    handlePaste,
    resetOTP,
    setFocus
  }
}