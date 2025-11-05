<template>
  <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <div class="shrink-0 flex items-center">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
              </svg>
            </div>
            <span class="ml-3 text-xl font-bold bg-linear-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent dark:from-indigo-400 dark:to-purple-400">NuxtLaravel</span>
          </div>
          <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
            <a v-for="link in navLinks" :key="link.name" :href="link.href" class="relative text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-all duration-200 group">
              {{ link.name }}
              <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 dark:bg-indigo-400 transition-all duration-300 group-hover:w-full"></span>
            </a>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <button @click="$emit('openSignIn')" class="relative px-4 py-2 text-indigo-600 dark:text-indigo-400 font-medium rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-800 transition-all duration-200 group">
            Sign In
            <span class="absolute inset-0 rounded-lg bg-indigo-600/0 group-hover:bg-indigo-600/5 transition-all duration-200"></span>
          </button>
          <button @click="$emit('openSignUp')" class="relative px-6 py-2 bg-linear-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden">
            <span class="relative z-10">Get Started</span>
            <span class="absolute inset-0 bg-linear-to-r from-indigo-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200"></span>
          </button>
          <!-- Mobile menu button -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none">
            <svg v-if="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-show="mobileMenuOpen" class="sm:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
      <div class="pt-2 pb-3 space-y-1">
        <a v-for="link in navLinks" :key="link.name" :href="link.href" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800">
          {{ link.name }}
        </a>
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 pb-3">
            
          <div class="flex items-center px-4">
            <button @click="$emit('openSignIn'); mobileMenuOpen = false" class="w-full block px-4 py-2 text-center text-indigo-600 dark:text-indigo-400 font-medium rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-800 transition-all duration-200">
              Sign In
            </button>
          </div>
          <div class="mt-3 flex items-center px-4">
            <button @click="$emit('openSignUp'); mobileMenuOpen = false" class="w-full block px-4 py-2 text-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
              Get Started
            </button>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';

defineEmits(['openSignIn', 'openSignUp']);

const mobileMenuOpen = ref(false);

const navLinks = [
  { name: 'Home', href: '#' },
  { name: 'Features', href: '#features' },
  { name: 'Tech Stack', href: '#tech-stack' },
  { name: 'Documentation', href: '#' },
];
</script>