<template>
  <Transition name="fade">
    <div class="full-page-loader">
      <div class="loader-backdrop"></div>
      <div class="loader-content">
        <div class="flex justify-center items-center">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>
        <p class="mt-4 text-white font-medium" v-if="message">{{ message }}</p>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
interface Props {
  message?: string
}

const props = defineProps<Props>()
</script>

<style scoped>
.full-page-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loader-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
}

.loader-content {
  position: relative;
  z-index: 10000;
  text-align: center;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>