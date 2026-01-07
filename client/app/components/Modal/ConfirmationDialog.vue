<template>
  <div class="fixed inset-0 z-9999 overflow-y-auto">
    <!-- Overlay with blur -->
    <div 
      class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity duration-300"
      @click="$emit('cancel')"
    ></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Modal Card with 3D effect -->
      <div 
        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 sm:my-8 sm:align-middle sm:max-w-md sm:w-full relative"
        :class="[
          'modal-card-' + type,
          showModal ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-4 scale-95'
        ]"
      >
        <!-- 3D Border effect -->
        <div class="absolute inset-0 rounded-2xl border-2 pointer-events-none"
          :class="borderClass"></div>
        
        <!-- Modal Content -->
        <div class="px-6 pt-8 pb-6 sm:p-8 relative z-10">
          <!-- Icon Container -->
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full shadow-lg mb-6"
            :class="iconContainerClass">
            <div class="relative">
              <!-- Glow effect -->
              <div class="absolute inset-0 rounded-full animate-ping opacity-30"
                :class="glowClass"></div>
              <!-- Icon -->
              <svg class="h-8 w-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                :class="iconColorClass">
                <path v-if="type === 'delete'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                <path v-else-if="type === 'warning'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.37 16.5c-.77.833.192 2.5 1.732 2.5z" />
                <path v-else-if="type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 13l4 4L19 7" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
              {{ title }}
            </h3>
            <div class="mt-3">
              <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ message }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <button
              type="button"
              @click="$emit('cancel')"
              class="w-full px-6 py-3 text-sm font-medium rounded-xl border transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-sm"
              :class="cancelButtonClass"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="$emit('confirm')"
              class="w-full px-6 py-3 text-sm font-medium rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg relative overflow-hidden group"
              :class="confirmButtonClass"
            >
              <!-- Button shine effect -->
              <span class="absolute top-0 left-0 w-full h-full opacity-0 group-hover:opacity-30 transition-opacity duration-300"
                :class="buttonShineClass"></span>
              <span class="relative z-10 flex items-center justify-center">
                {{ confirmButtonText }}
                <svg v-if="type === 'delete'" class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </span>
            </button>
          </div>

          <!-- Optional: Extra warning text for delete -->
          <div v-if="type === 'delete'" class="mt-6 p-3 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20">
            <p class="text-xs text-red-700 dark:text-red-300 flex items-start">
              <svg class="w-4 h-4 mr-2 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              This action cannot be undone. All data will be permanently removed.
            </p>
          </div>
        </div>

        <!-- Corner accent -->
        <div class="absolute top-0 left-0 w-24 h-24 -translate-x-12 -translate-y-12 opacity-10"
          :class="cornerAccentClass"></div>
        <div class="absolute bottom-0 right-0 w-24 h-24 translate-x-12 translate-y-12 opacity-10"
          :class="cornerAccentClass"></div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

interface Props {
  title: string
  message: string
  type?: 'delete' | 'warning' | 'success' | 'info'
}

const props = withDefaults(defineProps<Props>(), {
  type: 'info'
})

defineEmits<{
  confirm: []
  cancel: []
}>()

const showModal = ref(false)

// Animation on mount
onMounted(() => {
  setTimeout(() => {
    showModal.value = true
  }, 10)
})

// Computed properties for dynamic styling
const iconContainerClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'bg-gradient-to-br from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30'
    case 'warning': return 'bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30'
    case 'success': return 'bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30'
    default: return 'bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30'
  }
})

const iconColorClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'text-red-600 dark:text-red-400'
    case 'warning': return 'text-yellow-600 dark:text-yellow-400'
    case 'success': return 'text-green-600 dark:text-green-400'
    default: return 'text-blue-600 dark:text-blue-400'
  }
})

const glowClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'bg-red-400'
    case 'warning': return 'bg-yellow-400'
    case 'success': return 'bg-green-400'
    default: return 'bg-blue-400'
  }
})

const borderClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'border-red-200/50 dark:border-red-700/30'
    case 'warning': return 'border-yellow-200/50 dark:border-yellow-700/30'
    case 'success': return 'border-green-200/50 dark:border-green-700/30'
    default: return 'border-blue-200/50 dark:border-blue-700/30'
  }
})

const cancelButtonClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'
    default: return 'text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'
  }
})

const confirmButtonClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white'
    case 'warning': return 'bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white'
    case 'success': return 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white'
    default: return 'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white'
  }
})

const buttonShineClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'bg-gradient-to-r from-white to-transparent'
    case 'warning': return 'bg-gradient-to-r from-white to-transparent'
    case 'success': return 'bg-gradient-to-r from-white to-transparent'
    default: return 'bg-gradient-to-r from-white to-transparent'
  }
})

const cornerAccentClass = computed(() => {
  switch (props.type) {
    case 'delete': return 'bg-gradient-to-br from-red-500 to-pink-500'
    case 'warning': return 'bg-gradient-to-br from-yellow-500 to-amber-500'
    case 'success': return 'bg-gradient-to-br from-green-500 to-emerald-500'
    default: return 'bg-gradient-to-br from-blue-500 to-cyan-500'
  }
})

const confirmButtonText = computed(() => {
  switch (props.type) {
    case 'delete': return 'Delete'
    case 'warning': return 'Continue'
    case 'success': return 'Confirm'
    default: return 'OK'
  }
})
</script>

<style scoped>
/* 3D Card effect */
.modal-card-delete {
  transform-style: preserve-3d;
  transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
  box-shadow: 
    0 20px 60px rgba(239, 68, 68, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3),
    0 1px 0 rgba(255, 255, 255, 0.1);
}

.modal-card-warning {
  transform-style: preserve-3d;
  transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
  box-shadow: 
    0 20px 60px rgba(245, 158, 11, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3),
    0 1px 0 rgba(255, 255, 255, 0.1);
}

.modal-card-success {
  transform-style: preserve-3d;
  transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
  box-shadow: 
    0 20px 60px rgba(16, 185, 129, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3),
    0 1px 0 rgba(255, 255, 255, 0.1);
}

.modal-card-info {
  transform-style: preserve-3d;
  transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
  box-shadow: 
    0 20px 60px rgba(59, 130, 246, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.3),
    0 1px 0 rgba(255, 255, 255, 0.1);
}

/* Hover effects */
.modal-card-delete:hover {
  box-shadow: 
    0 25px 80px rgba(239, 68, 68, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.4),
    0 2px 0 rgba(255, 255, 255, 0.15);
}

.modal-card-warning:hover {
  box-shadow: 
    0 25px 80px rgba(245, 158, 11, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.4),
    0 2px 0 rgba(255, 255, 255, 0.15);
}

.modal-card-success:hover {
  box-shadow: 
    0 25px 80px rgba(16, 185, 129, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.4),
    0 2px 0 rgba(255, 255, 255, 0.15);
}

.modal-card-info:hover {
  box-shadow: 
    0 25px 80px rgba(59, 130, 246, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.4),
    0 2px 0 rgba(255, 255, 255, 0.15);
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>