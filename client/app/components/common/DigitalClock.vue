<template>
  <button v-if="showServerTime && !isMobile"
    class="font-mono font-medium text-sm text-theme-500 dark:text-theme-400 tracking-wide p-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
    <div class="glow-on-hover">
      {{ time }}
    </div>
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const {
  showServerTime, isMobile
} = useSidebar();

const time = ref('')

const updateTime = () => {
  const now = new Date()
  time.value = now.toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  })
}

let intervalId

onMounted(() => {
  updateTime()
  intervalId = setInterval(updateTime, 1000)
})

onUnmounted(() => {
  clearInterval(intervalId)
})
</script>