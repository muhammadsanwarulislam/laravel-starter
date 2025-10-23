<template>
  <transition enter-active-class="transition-all duration-300 ease-out" enter-from-class="opacity-0"
    enter-to-class="opacity-100" leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100"
    leave-to-class="opacity-0">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
      aria-describedby="modal-description" role="dialog" aria-modal="true">
      <!-- Backdrop with blur effect -->
      <div class="fixed inset-0 bg-white/40 backdrop-blur-sm transition-opacity" :class="backdropClass"
        aria-hidden="true" @click="handleBackdropClick"></div>

      <!-- Modal Container -->
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <!-- Modal Panel with Glass Effect -->
        <transition enter-active-class="transition-all duration-300 ease-out"
          enter-from-class="opacity-0 scale-95 translate-y-10" enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition-all duration-200 ease-in"
          leave-from-class="opacity-100 scale-100 translate-y-0" leave-to-class="opacity-0 scale-95 translate-y-10">
          <div
            class="relative transform overflow-hidden rounded-2xl text-left shadow-2xl transition-all w-full border border-white/20"
            :class="[modalClasses, sizeClasses]">
            <!-- Glass background effect -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-md"></div>
            <div class="absolute inset-0 bg-white/5"></div>

            <!-- Content Container -->
            <div class="relative">
              <!-- Header -->
              <div class="px-6 py-5 border-b" :class="headerBorderClass">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <!-- Icon -->
                    <div v-if="icon"
                      class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-lg"
                      :class="iconBackgroundClass">
                      <component :is="icon" v-if="typeof icon === 'object'" class="w-6 h-6" />
                      <span v-else class="text-xl">{{ icon }}</span>
                    </div>

                    <!-- Title & Description -->
                    <div>
                      <h2 id="modal-title"
                        class="text-2xl font-bold bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">
                        {{ title }}
                      </h2>
                      <p v-if="description" id="modal-description" class="text-gray-300 mt-1 text-sm">
                        {{ description }}
                      </p>
                    </div>
                  </div>

                  <!-- Close Button -->
                  <button @click="$emit('close')"
                    class="group rounded-xl p-2 transition-all duration-200 hover:bg-white/10 border border-transparent hover:border-white/20 focus:outline-none focus:ring-2 focus:ring-white/30"
                    aria-label="Close modal">
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-white transition-colors" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="px-6 py-6">
                <div class="modal-content overflow-y-auto">
                  <slot></slot>
                </div>
              </div>

              <!-- Footer -->
              <div v-if="$slots.footer" class="px-6 py-4 border-t" :class="footerBorderClass">
                <div class="flex justify-end space-x-3">
                  <slot name="footer"></slot>
                </div>
              </div>

              <!-- Loading Overlay -->
              <div v-if="loading"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                <div class="text-center">
                  <div class="animate-spin rounded-full h-8 w-8 border-2 border-white/30 border-t-white mx-auto"></div>
                  <p class="text-white mt-2 text-sm">{{ loadingText }}</p>
                </div>
              </div>
            </div>

            <!-- Decorative Elements -->
            <div
              class="absolute top-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-xl">
            </div>
            <div
              class="absolute bottom-0 right-0 w-40 h-40 bg-purple-500/10 rounded-full translate-x-1/2 translate-y-1/2 blur-xl">
            </div>
          </div>
        </transition>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';

interface Props {
  show: boolean;
  title: string;
  description?: string;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'full';
  variant?: 'default' | 'dark' | 'colored';
  icon?: string | object;
  iconColor?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'pink';
  loading?: boolean;
  loadingText?: string;
  closeOnBackdrop?: boolean;
  closeOnEscape?: boolean;
  blurIntensity?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  variant: 'default',
  iconColor: 'blue',
  loading: false,
  loadingText: 'Loading...',
  closeOnBackdrop: true,
  closeOnEscape: true,
  blurIntensity: 'md'
});

const emit = defineEmits<{
  close: [];
}>();

// Size classes
const sizeClasses = computed(() => ({
  'xs': 'max-w-sm',
  'sm': 'max-w-md',
  'md': 'max-w-lg',
  'lg': 'max-w-2xl',
  'xl': 'max-w-4xl',
  'full': 'max-w-full mx-4'
}[props.size]));

// Modal background classes based on variant
const modalClasses = computed(() => {
  const base = 'backdrop-blur-md bg-gradient-to-br';

  const variants = {
    default: 'from-white/10 to-white/5 border-white/10',
    dark: 'from-gray-900/80 to-black/70 border-gray-700/50',
    colored: 'from-blue-500/20 to-purple-600/20 border-blue-400/30'
  };

  return `${base} ${variants[props.variant]}`;
});

// Header border class
const headerBorderClass = computed(() => {
  const variants = {
    default: 'border-white/10',
    dark: 'border-gray-700/50',
    colored: 'border-blue-400/20'
  };
  return variants[props.variant];
});

// Footer border class
const footerBorderClass = computed(() => {
  const variants = {
    default: 'border-white/10',
    dark: 'border-gray-700/50',
    colored: 'border-blue-400/20'
  };
  return variants[props.variant];
});

// Backdrop class
const backdropClass = computed(() => {
  const variants = {
    default: 'bg-black/40',
    dark: 'bg-black/60',
    colored: 'bg-blue-900/30'
  };
  return variants[props.variant];
});

// Icon background class
const iconBackgroundClass = computed(() => {
  const base = 'bg-gradient-to-br text-white shadow-lg';

  const colors = {
    blue: 'from-blue-500/80 to-blue-600/80',
    green: 'from-green-500/80 to-green-600/80',
    red: 'from-red-500/80 to-red-600/80',
    yellow: 'from-yellow-500/80 to-yellow-600/80',
    purple: 'from-purple-500/80 to-purple-600/80',
    pink: 'from-pink-500/80 to-pink-600/80'
  };

  return `${base} ${colors[props.iconColor]}`;
});

// Handle backdrop click
const handleBackdropClick = (event: MouseEvent) => {
  if (props.closeOnBackdrop && (event.target as HTMLElement).classList.contains('fixed')) {
    emit('close');
  }
};

// Handle escape key
const handleEscapeKey = (event: KeyboardEvent) => {
  if (props.closeOnEscape && event.key === 'Escape' && props.show) {
    emit('close');
  }
};

// Prevent body scroll when modal is open
const preventBodyScroll = (prevent: boolean) => {
  if (prevent) {
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = '15px'; // Prevent layout shift
  } else {
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
  }
};

// Set up event listeners
onMounted(() => {
  document.addEventListener('keydown', handleEscapeKey);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscapeKey);
  preventBodyScroll(false);
});

// Watch for modal show/hide to manage body scroll
watch(() => props.show, (newVal) => {
  preventBodyScroll(newVal);
});
</script>

<style scoped>
/* Custom scrollbar for modal content */
.modal-content {
  max-height: calc(80vh - 160px);
  overflow-y: auto;
}

.modal-content::-webkit-scrollbar {
  width: 6px;
}

.modal-content::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}

/* Firefox scrollbar */
.modal-content {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
}
</style>