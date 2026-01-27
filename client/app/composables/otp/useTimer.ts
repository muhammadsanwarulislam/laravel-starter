import { ref, onUnmounted } from 'vue'

export const useTimer = (initialSeconds: number, onExpire?: () => void) => {
  const timeLeft = ref(initialSeconds)
  const timerInterval = ref<NodeJS.Timeout | null>(null)

  const start = () => {
    if (timerInterval.value) clearInterval(timerInterval.value)
    
    timeLeft.value = initialSeconds
    timerInterval.value = setInterval(() => {
      if (timeLeft.value > 0) {
        timeLeft.value--
      } else {
        stop()
        onExpire?.()
      }
    }, 1000)
  }

  const stop = () => {
    if (timerInterval.value) {
      clearInterval(timerInterval.value)
      timerInterval.value = null
    }
  }

  const reset = (newSeconds?: number) => {
    stop()
    timeLeft.value = newSeconds || initialSeconds
    start()
  }

  const formatTime = () => {
    const mins = Math.floor(timeLeft.value / 60)
    const secs = timeLeft.value % 60
    return `${mins}:${secs.toString().padStart(2, '0')}`
  }

  const isExpired = computed(() => timeLeft.value <= 0)

  onUnmounted(() => {
    stop()
  })

  return {
    timeLeft,
    isExpired,
    start,
    stop,
    reset,
    formatTime
  }
}