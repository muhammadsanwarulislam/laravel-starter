<template>
  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
    <div v-if="showProfileDetails" @mouseleave="handleMouseLeave()" @mouseenter="handleMouseEnter()"
      aria-label="Profile Details"
      class="absolute right-0 mt-2 w-120 bg-white dark:bg-gray-900 rounded-xl shadow-xl z-20 border border-gray-200 dark:border-gray-700 overflow-hidden">
      <!-- Header -->
      <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">Profile</h3>
        <button @click="emitClose" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="p-8 text-center">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Loading profile...</p>
      </div>

      <div v-else class="p-6 space-y-8 text-sm text-gray-700 dark:text-gray-200">
        <!-- Display Mode -->
        <div class="space-y-6">
          <div class="flex items-center space-x-6">
            <img :src="user.avatar || defaultAvatar" alt="Avatar"
              class="h-20 w-20 rounded-full ring-4 ring-blue-500/50 dark:ring-blue-400/50 shadow-lg transform hover:scale-105 transition-transform duration-300" />
            <div>
              <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ user.name }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ user.email }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Role: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ userProfile.type || 'User' }}</span>
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Gender: <span class="font-semibold text-gray-700 dark:text-gray-300 capitalize">{{ userProfile.gender || 'Not set' }}</span>
              </p>
            </div>
          </div>
          
          <p v-if="user.bio" class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ user.bio }}</p>
          <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">No bio provided</p>

          <!-- Profile Information -->
          <div class="space-y-3">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Profile Information</h4>
            <ul class="space-y-2">
              <li v-if="userProfile.nid"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-xs">National ID</div>
                  <span class="text-sm font-medium">{{ userProfile.nid }}</span>
                </div>
              </li>
              <li v-if="userProfile.address"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-green-100 dark:bg-green-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <div class="text-xs">Address</div>
                  <span class="text-sm font-medium">{{ userProfile.address }}</span>
                </div>
              </li>
            </ul>
          </div>

          <!-- Contact & Links -->
          <div class="space-y-3">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Contact & Links</h4>
            <ul class="space-y-2">
              <li v-if="user.phone"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path
                      d="M3 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5zM3 15a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2zM15 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2V5zM15 15a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-2z" />
                  </svg>
                </div>
                <div>
                  <div class="text-xs">Mobile</div>
                  <span class="text-sm font-medium">{{ user.phone }}</span>
                </div>
              </li>
              <li v-if="user.email"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-green-100 dark:bg-green-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4h16v16H4z" />
                    <path d="M22 6l-10 7L2 6" />
                  </svg>
                </div>
                <div>
                  <div class="text-xs">Email</div>
                  <span class="text-sm font-medium">{{ user.email }}</span>
                </div>
              </li>
              <li v-if="user.location"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z" />
                    <circle cx="12" cy="9" r="2" />
                  </svg>
                </div>
                <div>
                  <div class="text-xs">Location</div>
                  <span class="text-sm font-medium">{{ user.location }}</span>
                </div>
              </li>
            </ul>
          </div>

          <!-- More Details & Edit Button -->
          <div v-if="showMoreDetails"
            class="space-y-3 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg transition-all duration-300">
            <p class="text-xs text-gray-600 dark:text-gray-400">
              <span class="font-bold">Joined:</span> 
              {{ formatDate(user.created_at) }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              <span class="font-bold">Last Updated:</span>
              {{ formatDate(user.updated_at) }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              <span class="font-bold">Status:</span> 
              <span :class="user.is_active ? 'text-green-600' : 'text-red-600'">
                {{ user.is_active ? 'Active' : 'Inactive' }}
              </span>
            </p>
          </div>
          <button @click="showMoreDetails = !showMoreDetails"
            class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium transition-colors">
            {{ showMoreDetails ? 'Hide Details' : 'Show More' }}
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
  showProfileDetails: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close']);

// State
const showMoreDetails = ref(false);
const loading = ref(false);
const defaultAvatar = 'https://i.pravatar.cc/150?img=6';

// User data
const user = ref({
  id: null,
  name: '',
  email: '',
  phone: '',
  bio: '',
  location: '',
  is_active: true,
  created_at: '',
  updated_at: ''
});

const userProfile = ref({
  gender: '',
  type: '',
  nid: '',
  address: ''
});

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

function emitClose() {
  emit('close');
}

const closeTimer = ref(null);
function handleMouseLeave() {
  if (closeTimer.value) clearTimeout(closeTimer.value);
  closeTimer.value = setTimeout(() => {
    emitClose();
  }, 1000);
}

function handleMouseEnter() {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value);
    closeTimer.value = null;
  }
}
</script>