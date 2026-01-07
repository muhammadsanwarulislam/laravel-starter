<template>
  <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4">
      <div class="flex flex-col md:flex-row items-center justify-between">
        <!-- Left section: Copyright and version -->
        <div class="flex items-center space-x-4 mb-4 md:mb-0">
          <div class="text-sm text-gray-600 dark:text-gray-300">
            &copy; {{ currentYear }} Admin Panel. All rights reserved.
          </div>
          <span class="hidden md:inline text-gray-400 dark:text-gray-500">•</span>
          <div class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
            v{{ appVersion }}
          </div>
        </div>

        <!-- Middle section: Status indicators -->
        <div class="flex items-center space-x-6 mb-4 md:mb-0">
          <div class="flex items-center space-x-2">
            <div class="h-2 w-2 rounded-full" :class="apiStatus ? 'bg-green-500' : 'bg-red-500'"></div>
            <span class="text-xs text-gray-600 dark:text-gray-300">API</span>
          </div>
          <div class="flex items-center space-x-2">
            <div class="h-2 w-2 rounded-full" :class="databaseStatus ? 'bg-green-500' : 'bg-yellow-500'"></div>
            <span class="text-xs text-gray-600 dark:text-gray-300">Database</span>
          </div>
          <div class="flex items-center space-x-2">
            <div class="h-2 w-2 rounded-full" :class="cacheStatus ? 'bg-green-500' : 'bg-gray-400'"></div>
            <span class="text-xs text-gray-600 dark:text-gray-300">Cache</span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const currentYear = ref(new Date().getFullYear())
const appVersion = ref('1.0.0')

const apiStatus = ref(true)
const databaseStatus = ref(true)
const cacheStatus = ref(true)

// Simulate status updates
onMounted(() => {
  setInterval(() => {
    apiStatus.value = Math.random() > 0.1
    databaseStatus.value = Math.random() > 0.05
    cacheStatus.value = Math.random() > 0.2
  }, 10000)
})
</script>

<style scoped>
footer {
  backdrop-filter: blur(10px);
  background-color: rgba(255, 255, 255, 0.95);
}

.dark footer {
  background-color: rgba(31, 41, 55, 0.95);
}
</style>
