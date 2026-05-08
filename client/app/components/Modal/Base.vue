<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black bg-opacity-40" @click="handleBackdropClick" />
      
      <!-- Modal container -->
      <div class="flex min-h-full items-center justify-center p-4">
        <!-- Modal content -->
        <div 
          class="relative w-full transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-2xl transition-all border border-gray-200 dark:border-gray-700"
          :class="modalSize"
        >
          <!-- Close button -->
          <button 
            v-if="showCloseButton" 
            @click="$emit('close')"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
          >
            <UIIconsCross class="h-6 w-6" />
          </button>

          <!-- Header slot -->
          <div v-if="$slots.header" class="mb-4">
            <slot name="header" />
          </div>

          <!-- Content slot -->
          <div v-if="$slots.content" class="mb-6">
            <slot name="content" />
          </div>

          <!-- Footer slot -->
          <div v-if="$slots.footer" class="mt-6">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  isOpen: boolean
  size?: 'sm' | 'md' | 'lg' | 'xl'
  closeOnBackdrop?: boolean
  showCloseButton?: boolean
}>()

const emit = defineEmits<{
  close: []
}>()

const modalSize = computed(() => {
  const sizes = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl'
  }
  return sizes[props.size || 'md']
})

const handleBackdropClick = () => {
  if (props.closeOnBackdrop !== false) {
    emit('close')
  }
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>