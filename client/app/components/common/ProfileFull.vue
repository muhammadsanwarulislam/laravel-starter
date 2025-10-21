<template>
  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
    <div v-if="showProfileFull" @mouseleave="handleMouseLeave()" @mouseenter="handleMouseEnter()"
      aria-label="ProfileFull"
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
        <div v-if="!isEditing" class="space-y-6">
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

          <!-- Social Media Display -->
          <div v-if="user.facebook || user.twitter || user.linkedin" class="flex items-center justify-center space-x-6">
            <a v-if="user.facebook" :href="`https://facebook.com/${user.facebook}`" target="_blank"
              class="text-blue-800 hover:text-blue-900 bg-gray-100 dark:bg-gray-800 p-2 rounded-md transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="#1877F2" width="16" height="16">
                <path d="M19 6h5V0h-5c-5 0-8 3-8 8v4H6v6h5v14h6V18h5l1-6h-6v-4c0-1.1.9-2 2-2z" />
              </svg>
            </a>
            <a v-if="user.twitter" :href="`https://twitter.com/${user.twitter}`" target="_blank"
              class="text-blue-400 hover:text-blue-600 bg-gray-100 dark:bg-gray-800 p-2 rounded-md transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="black" width="16" height="16">
                <path d="M20 6h6L18 14l8 12h-6l-6-8-6 8H4l8-10-8-12h6l6 8 6-8z" />
              </svg>
            </a>
            <a v-if="user.linkedin" :href="`https://linkedin.com/in/${user.linkedin}`" target="_blank"
              class="text-blue-600 hover:text-blue-800 bg-gray-100 dark:bg-gray-800 p-2 rounded-md transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="#0A66C2" width="16" height="16">
                <path
                  d="M6 12h4v14H6V12zm2-6a2 2 0 110 4 2 2 0 010-4zm6 6h4v2h.1c.6-1 2-2 4-2 4 0 5 3 5 6v8h-4v-7c0-2-1-3-2.5-3S20 15 20 17v9h-4V12z" />
              </svg>
            </a>
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
              <li v-if="user.website"
                class="flex items-center space-x-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-700 p-3 rounded-lg">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-2 rounded-md">
                  <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20" />
                  </svg>
                </div>
                <div>
                  <div class="text-xs">Website</div>
                  <a :href="user.website" target="_blank"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    {{ user.website }}
                  </a>
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
          <CommonButton @click="isEditing = true" type="button" :button-text="'Edit Profile'"
            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 font-semibold" />
        </div>

        <!-- Edit Mode -->
        <form v-else @submit.prevent="saveProfile" class="space-y-6">
          <!-- Basic User Information -->
          <div class="space-y-4">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Basic Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                <input v-model="form.name" type="text" required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Full Name" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                <input v-model="form.email" type="email" required
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="user@example.com" />
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
              <input v-model="form.phone" type="tel"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                placeholder="+1 (555) 123-4567" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
              <textarea v-model="form.bio" rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                placeholder="Tell us about yourself..."></textarea>
            </div>
          </div>

          <!-- Profile Information -->
          <div class="space-y-4">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Profile Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                <select v-model="form.gender"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                <select v-model="form.type"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white">
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">National ID</label>
                <input v-model="form.nid" type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="National ID Number" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                <input v-model="form.location" type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="City, Country" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
              <textarea v-model="form.address" rows="2"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                placeholder="Full address"></textarea>
            </div>
          </div>

          <!-- Social Media -->
          <div class="space-y-4">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Social Media</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Facebook</label>
                <input v-model="form.facebook" type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Username" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Twitter</label>
                <input v-model="form.twitter" type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Username" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">LinkedIn</label>
                <input v-model="form.linkedin" type="text"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Username" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
              <input v-model="form.website" type="url"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                placeholder="https://example.com" />
            </div>
          </div>

          <!-- Avatar Upload -->
          <div class="space-y-2">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Avatar</h4>
            <div class="flex items-center space-x-4">
              <div class="relative inline-block cursor-pointer" @click="handleAvatarClick">
                <img v-if="form.avatarPreview" :src="form.avatarPreview" alt="Avatar Preview"
                  class="h-20 w-20 rounded-full shadow-lg object-cover" />
                <div v-else class="h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                  <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                  </svg>
                </div>
                <div v-if="avatarUploaded" class="absolute bottom-0 right-0 bg-green-500 text-white p-1 rounded-full">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Click to upload new avatar</p>
                <p class="text-xs text-gray-500 dark:text-gray-500">Recommended: Square image, max 2MB</p>
              </div>
            </div>
            <input ref="fileInput" class="hidden" type="file" accept="image/*" @change="handleAvatarUpload" />
          </div>

          <!-- Password Change (Optional) -->
          <div class="space-y-4">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Password Change</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                <input v-model="form.password" type="password"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Leave blank to keep current" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Confirm new password" />
              </div>
            </div>
          </div>

          <!-- Save / Cancel -->
          <div class="flex space-x-4 pt-4">
            <CommonButton type="submit" :button-text="isSubmitting ? 'Saving...' : 'Save Changes'" :disabled="isSubmitting"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-lime-600 text-white rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 font-semibold disabled:opacity-50" />
            <CommonButton @click="cancelEdit" type="button" :button-text="'Cancel'" :disabled="isSubmitting"
              class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-semibold disabled:opacity-50" />
          </div>
        </form>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
  showProfileFull: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close']);

// State
const showMoreDetails = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const isSubmitting = ref(false);
const avatarUploaded = ref(false);

const defaultAvatar = 'https://i.pravatar.cc/150?img=6';

// User data
const user = ref({
  id: null,
  name: '',
  email: '',
  phone: '',
  bio: '',
  avatar: '',
  website: '',
  facebook: '',
  twitter: '',
  linkedin: '',
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

// Form data
const form = ref({
  name: '',
  email: '',
  phone: '',
  bio: '',
  gender: '',
  type: 'student',
  nid: '',
  address: '',
  location: '',
  website: '',
  facebook: '',
  twitter: '',
  linkedin: '',
  password: '',
  password_confirmation: '',
  avatar: null,
  avatarPreview: ''
});

// Reset isEditing every time the modal opens
watch(
  () => props.showProfileFull,
  (visible) => {
    if (visible) {
      isEditing.value = false;
      loadUserData();
    }
  },
  { immediate: true }
);

// Load user data from API
async function loadUserData() {
  try {
    loading.value = true;
    
    // Get current user ID from auth or localStorage
    const currentUserId = localStorage.getItem('user_id') || 1; // Fallback for demo
    
    // Fetch user data
    const userResponse = await $http(`users/${currentUserId}`, { method: 'GET' });
    
    if (userResponse?.data) {
      user.value = userResponse.data;
      
      // Fetch profile data
      try {
        const profileResponse = await $http(`profiles/user/${currentUserId}`, { method: 'GET' });
        if (profileResponse?.data) {
          userProfile.value = profileResponse.data;
        }
      } catch (profileError) {
        console.log('No profile found, using default values');
      }
      
      // Initialize form
      resetForm();
    }
  } catch (error) {
    console.error('Error loading user data:', error);
    // Fallback to demo data
    user.value = {
      id: 1,
      name: 'Munir Ahmed',
      email: 'munir@example.com',
      phone: '+880 1234 567890',
      bio: 'Strategic architect and design perfectionist. Passionate about vibrant dashboards and seamless UX.',
      avatar: 'https://i.pravatar.cc/150?img=3',
      website: 'https://munir.com',
      facebook: 'munir',
      twitter: 'munir',
      linkedin: 'munir-ahmed',
      location: 'Dhaka, Bangladesh',
      is_active: true,
      created_at: '2023-09-02T00:00:00.000Z',
      updated_at: '2025-09-08T00:00:00.000Z'
    };
    
    userProfile.value = {
      gender: 'male',
      type: 'admin',
      nid: '1234567890',
      address: '123 Main Street, Dhaka, Bangladesh'
    };
    
    resetForm();
  } finally {
    loading.value = false;
  }
}

function resetForm() {
  form.value = {
    name: user.value.name,
    email: user.value.email,
    phone: user.value.phone || '',
    bio: user.value.bio || '',
    gender: userProfile.value.gender || '',
    type: userProfile.value.type || 'student',
    nid: userProfile.value.nid || '',
    address: userProfile.value.address || '',
    location: user.value.location || '',
    website: user.value.website || '',
    facebook: user.value.facebook || '',
    twitter: user.value.twitter || '',
    linkedin: user.value.linkedin || '',
    password: '',
    password_confirmation: '',
    avatar: null,
    avatarPreview: user.value.avatar || defaultAvatar
  };
}

// Save profile to API
async function saveProfile() {
  try {
    isSubmitting.value = true;
    
    // Prepare form data
    const formData = new FormData();
    
    // User data
    formData.append('name', form.value.name);
    formData.append('email', form.value.email);
    formData.append('phone', form.value.phone);
    formData.append('bio', form.value.bio);
    formData.append('location', form.value.location);
    formData.append('website', form.value.website);
    formData.append('facebook', form.value.facebook);
    formData.append('twitter', form.value.twitter);
    formData.append('linkedin', form.value.linkedin);
    
    // Password if provided
    if (form.value.password) {
      if (form.value.password !== form.value.password_confirmation) {
        alert('Passwords do not match');
        return;
      }
      formData.append('password', form.value.password);
    }
    
    // Profile data
    formData.append('gender', form.value.gender);
    formData.append('type', form.value.type);
    formData.append('nid', form.value.nid);
    formData.append('address', form.value.address);
    
    // Avatar file
    if (form.value.avatar) {
      formData.append('avatar', form.value.avatar);
    }
    
    // Update user and profile
    const response = await $http(`users/${user.value.id}`, {
      method: 'PUT',
      body: formData,
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    
    if (response?.success) {
      // Reload user data
      await loadUserData();
      isEditing.value = false;
      
      // Show success message
      alert('Profile updated successfully!');
    }
    
  } catch (error) {
    console.error('Error saving profile:', error);
    alert('Failed to update profile. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
}

function cancelEdit() {
  resetForm();
  isEditing.value = false;
}

// Avatar handling
const fileInput = ref(null);

function handleAvatarClick() {
  fileInput.value?.click();
}

function handleAvatarUpload(event) {
  const file = event.target.files[0];
  if (file) {
    // Validate file type and size
    if (!file.type.startsWith('image/')) {
      alert('Please select an image file');
      return;
    }
    
    if (file.size > 2 * 1024 * 1024) { // 2MB
      alert('Image size should be less than 2MB');
      return;
    }
    
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.avatarPreview = e.target.result;
      form.value.avatar = file;
      avatarUploaded.value = true;
    };
    reader.readAsDataURL(file);
  }
}

// Utility functions
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