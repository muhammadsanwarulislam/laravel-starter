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

      <div class="p-6 space-y-8 text-sm text-gray-700 dark:text-gray-200">
        <!-- Display Mode -->
        <div v-if="!isEditing" class="space-y-6">
          <div class="flex items-center space-x-6">
            <img :src="user.avatar || defaultAvatar" alt="Avatar"
              class="h-20 w-20 rounded-full ring-4 ring-blue-500/50 dark:ring-blue-400/50 shadow-lg transform hover:scale-105 transition-transform duration-300" />
            <div>
              <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ user.name }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ user.email }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Role: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ user.role }}</span>
              </p>
            </div>
          </div>
          <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ user.bio }}</p>

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
                  <div class="text-xs">Mobile</div>
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
              {{ user.joinDate }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              <span class="font-bold">Last Login:</span>
              {{ user.lastLogin }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400">
              <span class="font-bold">Status:</span> 
              {{ user.status }}
            </p>
          </div>
          <button @click="showMoreDetails = !showMoreDetails"
            class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium transition-colors">
            {{ showMoreDetails ? 'Hide Details' : 'Show More' }}
          </button>
          <CommonButton @click="isEditing = true"
            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 font-semibold" />
        </div>

        <!-- Edit Mode -->
        <form v-else @submit.prevent="saveProfile" class="space-y-6">
          <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Basic Info</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input v-model="form.name" label="Name" type="text" placeholder="Full Name" />
            <input v-model="form.email" label="Email" type="email" placeholder="user@example.com" />
          </div>

          <input v-model="form.bio" label="Bio" type="textarea" placeholder="Tell us about yourself..." rows="3" />

          <!-- Contact & Location -->
          <div class="space-y-4">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Contact & Location
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <input v-model="form.phone" label="Phone" type="tel" icon="phone" placeholder="+1 (555) 123-4567" />
              <input v-model="form.location" label="Location" type="text" icon="map-pin" placeholder="City, Country" />
            </div>
            <input v-model="form.website" label="Website" type="url" icon="globe" placeholder="https://example.com" />
          </div>

          <!-- Avatar Upload -->
          <div class="space-y-2">
            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">Avatar</h4>
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
            <input ref="fileInput" class="hidden" type="file" accept="image/*" @change="handleAvatarUpload" />
          </div>

          <!-- Save / Cancel -->
          <div class="flex space-x-4">
            <CommonButton type="submit" :button-text="'Save Changes'"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-lime-600 text-white rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 font-semibold" />
            <CommonButton @click="cancelEdit" type="button" :button-text="'Cancel'"
              class="flex-1 px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-semibold" />
          </div>
        </form>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  showProfileFull: {
    type: Boolean,
    required: true
  }
})

const showMoreDetails = ref(false);
const isEditing = ref(false)

// Reset isEditing every time the modal opens
watch(
  () => props.showProfileFull,
  (visible) => {
    if (visible) {
      isEditing.value = false
    }
  },
  { immediate: true }
)

const defaultAvatar = 'https://i.pravatar.cc/150?img=6';

const user = ref({
  name: 'Munir',
  email: 'munir@example.com',
  role: 'Admin',
  bio: 'Strategic architect and design perfectionist. Passionate about vibrant dashboards and seamless UX.',
  avatar: 'https://i.pravatar.cc/150?img=3',
  joinDate: 'September 2, 2023',
  lastLogin: 'September 8, 2025',
  status: 'Online',
  // New fields
  phone: '+880 1234 567890',
  location: 'Dhaka, Bangladesh',
  website: 'https://munir.com',
  facebook: 'munir',
  twitter: 'munir',
  linkedin: 'munir-ahmed'
});

const form = ref({ ...user.value, avatarPreview: user.value.avatar });

function saveProfile() {
  user.value = { ...form.value };
  isEditing.value = false;
}

function cancelEdit() {
  form.value = { ...user.value, avatarPreview: user.value.avatar };
  isEditing.value = false;
}

function handleAvatarUpload(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.avatarPreview = e.target.result;
      form.value.avatar = file;
    };
    reader.readAsDataURL(file);
  }
}


const emit = defineEmits(['close']);

function emitClose() {
  emit('close');
}

const closeTimer = ref(null)
function handleMouseLeave() {
  if (closeTimer.value) clearTimeout(closeTimer.value)
  closeTimer.value = setTimeout(() => {
    emitClose()
  }, 1000)
}
function handleMouseEnter() {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value)
    closeTimer.value = null
  }
}
</script>