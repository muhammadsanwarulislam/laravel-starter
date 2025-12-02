<template>
  <div class="min-h-screen bg-linear-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
      <!-- Background decoration -->
      <div class="absolute inset-0 bg-grid-slate-100 dark:bg-grid-gray-800 mask-[linear-gradient(0deg,white,rgba(255,255,255,0.6))]"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 lg:py-32">
        <div class="flex flex-col lg:flex-row items-center justify-between">
          <!-- Content -->
          <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-12">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-sm font-medium mb-6">
              🚀 Modern Full-Stack Starter Kit
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
              Build with
              <span class="bg-linear-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Nuxt.js & Laravel</span>
            </h1>

            <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
              A production-ready starter kit combining the power of Nuxt.js frontend with Laravel API backend. 
              Explore modern features, best practices, and clean architecture patterns in a cohesive project.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mb-8">
              <button @click="$emit('openSignUp')" class="group relative px-8 py-4 bg-linear-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                <span class="relative z-10 flex items-center justify-center">
                  Get Started
                  <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </span>
              </button>

              <button @click="$emit('openSignIn')" class="group px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:border-indigo-300 dark:hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200">
                <span class="flex items-center justify-center">
                  View Demo
                  <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </span>
              </button>
            </div>

            <div class="flex items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
              <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span>Free & Open Source</span>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <span>Production Ready</span>
              </div>
            </div>
          </div>

          <!-- Tech Stack Preview -->
          <div class="lg:w-1/2 relative">
            <div class="relative max-w-lg mx-auto">
              <!-- Floating elements -->
              <div class="absolute -top-4 -left-4 w-24 h-24 bg-purple-200 dark:bg-purple-900 rounded-full blur-xl opacity-50"></div>
              <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-indigo-200 dark:bg-indigo-900 rounded-full blur-xl opacity-50"></div>

              <!-- Tech stack card -->
              <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <!-- Tech stack header -->
                <div class="bg-linear-to-r from-gray-900 to-gray-800 px-6 py-4 flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="flex space-x-1">
                      <div class="w-3 h-3 rounded-full bg-red-400"></div>
                      <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                      <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <span class="text-white font-medium text-sm">Tech Stack</span>
                  </div>
                  <div class="text-gray-400 text-xs">v1.0</div>
                </div>

                <!-- Tech stack content -->
                <div class="p-6">
                  <!-- Frameworks -->
                  <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-linear-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-xl p-4 flex items-center">
                      <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">Laravel</div>
                        <div class="text-xs text-red-600 dark:text-red-400 font-medium">API Backend</div>
                      </div>
                    </div>
                    <div class="bg-linear-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 flex items-center">
                      <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">Nuxt.js</div>
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">Frontend</div>
                      </div>
                    </div>
                  </div>

                  <!-- Features -->
                  <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                    <div class="flex justify-between items-center mb-3">
                      <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Key Features</span>
                      <span class="text-xs text-green-500 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">5 Included</span>
                    </div>
                    <div class="space-y-2">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Secure Authentication</span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Profile Management</span>
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Multi-language Support</span>
                      </div>
                    </div>
                  </div>

                  <!-- Architecture -->
                  <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                      </div>
                      <div class="flex-1">
                        <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-3/4"></div>
                        <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mt-2"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="relative py-20 lg:py-28">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-sm font-medium mb-4">
            ✨ Key Features
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Everything You Need to
            <span class="bg-linear-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Build Modern Apps</span>
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            Our starter kit includes all the essential features to jumpstart your development process with best practices and clean architecture.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(feature, index) in features" :key="index" class="group relative bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:border-indigo-200 dark:hover:border-indigo-500 transition-all duration-300 hover:shadow-xl">
            <div class="absolute inset-0 bg-linear-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
              <div class="w-14 h-14 bg-linear-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <component :is="feature.icon" class="w-6 h-6 text-white" />
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ feature.title }}</h3>
              <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="relative py-20 lg:py-28 bg-white/50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 text-sm font-medium mb-4">
            💻 Modern Tech Stack
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Built with
            <span class="bg-linear-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Latest Technologies</span>
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
            Our starter kit leverages the latest versions of industry-leading frameworks to ensure your project is modern, scalable, and maintainable.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-lg">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-red-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel API</h3>
                <p class="text-indigo-600 dark:text-indigo-400">Backend Framework</p>
              </div>
            </div>
            <ul class="space-y-3">
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">RESTful API development</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Repository-Service pattern</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Event-driven architecture</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">API resource transformation</span>
              </li>
            </ul>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-lg">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Nuxt.js</h3>
                <p class="text-green-600 dark:text-green-400">Frontend Framework</p>
              </div>
            </div>
            <ul class="space-y-3">
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Vue 3 Composition API</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Server-side rendering (SSR)</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Internationalization (i18n)</span>
              </li>
              <li class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">Component-based architecture</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 lg:py-28">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-linear-to-r from-indigo-600 to-purple-600 rounded-3xl p-12 lg:p-16 relative overflow-hidden">
          <div class="absolute inset-0 bg-black/10"></div>
          <div class="relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
              Ready to Start Building?
            </h2>
            <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto">
              Get started with our full-stack starter kit and accelerate your development process with modern best practices.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <button @click="$emit('openSignUp')" class="px-8 py-4 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200 transform hover:-translate-y-1 shadow-lg">
                Get Started Now
              </button>
              <button @click="$emit('openSignIn')" class="px-8 py-4 bg-transparent text-white border-2 border-white font-semibold rounded-xl hover:bg-white hover:text-indigo-600 transition-all duration-200">
                View Documentation
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
definePageMeta({ middleware: ["guest"], layout: "guest" });

defineEmits(['openSignIn', 'openSignUp']);

// Features data
const features = [
  {
    icon: 'ShieldCheckIcon',
    title: 'Secure Authentication',
    description: 'Complete user authentication system with registration, login, and password management using Laravel Sanctum.'
  },
  {
    icon: 'UserIcon',
    title: 'Profile Management',
    description: 'Comprehensive user profile management with CRUD operations and customizable fields.'
  },
  {
    icon: 'GlobeAltIcon',
    title: 'Multi-language Support',
    description: 'Built-in internationalization (i18n) support to serve your app in multiple languages.'
  },
  {
    icon: 'CodeBracketIcon',
    title: 'Clean Architecture',
    description: 'Repository-Service pattern implementation for maintainable and scalable code structure.'
  },
  {
    icon: 'LightBulbIcon',
    title: 'Best Practices',
    description: 'Implements modern Laravel API best practices including resources, events, and proper error handling.'
  },
  {
    icon: 'DatabaseIcon',
    title: 'Database Seeding',
    description: 'Pre-configured seeders for dynamic content to quickly populate your application with test data.'
  }
];

// Icon components
const ShieldCheckIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`
};

const UserIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`
};

const GlobeAltIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9a9 9 0 01-9-9m9 9c0 5-4 9-9 9s-9-4-9-9m9-9a9 9 0 00-9 9"/></svg>`
};

const CodeBracketIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>`
};

const LightBulbIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>`
};

const DatabaseIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>`
};
</script>

<style scoped>
.bg-grid-slate-100 {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(241 245 249 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}

.bg-grid-gray-800 {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(31 41 55 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}

.dark .bg-grid-slate-100 {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(31 41 55 / 0.5)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
}
</style>