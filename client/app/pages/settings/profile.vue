<template>
  <div class="py-2 px-2 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ t('profile') }}</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm sm:text-base">
            Manage your profile information and settings
          </p>
        </div>

        <!-- Action Buttons - Desktop -->
        <div class="hidden sm:flex items-center space-x-3">
          <button v-if="!profile" @click="showCreateModal = true"
            class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Profile
          </button>

          <button v-else @click="showEditModal = true"
            class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
          </button>

          <button @click="showPasswordModal = true"
            class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Password
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="text-gray-600 dark:text-gray-400 mt-4">Loading profile...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error"
      class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6 text-center">
      <div class="text-red-600 dark:text-red-400 text-6xl mb-4">⚠️</div>
      <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">Failed to load profile</h3>
      <p class="text-red-600 dark:text-red-400 mb-4">{{ error }}</p>
      <button @click="fetchProfile"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
        Try Again
      </button>
    </div>

    <!-- Profile Data -->
    <div v-else-if="profile" class="grid grid-cols-1 xl:grid-cols-4 gap-6">
      <!-- Main Content -->
      <div class="xl:col-span-3 space-y-6">
        <!-- Profile Overview Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0 mb-4 sm:mb-0">
              <div class="relative">
                <img
                  :src="profile.user?.avatar || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'"
                  alt="Profile Avatar"
                  class="h-20 w-20 sm:h-24 sm:w-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg" />
                <div class="absolute -bottom-2 -right-2 bg-green-500 text-white p-1 rounded-full">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- User Info -->
            <div class="flex-1 min-w-0">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <div>
                  <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">
                    {{ profile.user?.name || 'Not set' }}
                  </h2>
                  <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    {{ profile.user?.email || 'Not set' }}
                  </p>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2 mt-3 sm:mt-0 sm:hidden">
                  <button @click="showEditModal = true"
                    class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    title="Edit Profile">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Status Badges -->
              <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium capitalize"
                  :class="getTypeBadgeClass(profile.type)">
                  {{ profile.type || 'Not set' }}
                </span>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium capitalize"
                  :class="getGenderBadgeClass(profile.gender)">
                  {{ profile.gender || 'Not set' }}
                </span>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                  :class="profile.user?.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                  {{ profile.user?.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Contact Information -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Contact Information
            </h3>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                <p class="text-gray-900 dark:text-white font-medium">{{ profile.user?.phone || 'Not provided' }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                <p class="text-gray-900 dark:text-white font-medium">{{ profile.user?.email || 'Not provided' }}</p>
              </div>
            </div>
          </div>

          <!-- Profile Details -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Profile Details
            </h3>
            <div class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">National ID</label>
                <p class="text-gray-900 dark:text-white font-medium">{{ profile.nid || 'Not provided' }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">User ID</label>
                <p class="text-gray-900 dark:text-white font-medium">{{ profile.user_id }}</p>
              </div>
            </div>
          </div>

          <!-- Address Information -->
          <div
            class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Address Information
            </h3>
            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Full Address</label>
              <p class="text-gray-900 dark:text-white">{{ profile.address || 'No address provided' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Stats</h3>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Member Since</label>
              <p class="text-gray-900 dark:text-white font-medium">{{ formatDate(profile.user?.created_at) }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</label>
              <p class="text-gray-900 dark:text-white font-medium">{{ formatDate(profile.updated_at) }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Profile Status</label>
              <span
                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                Complete
              </span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>

          <div class="space-y-3">
            <button @click="showEditModal = true"
              class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Profile
            </button>

            <button @click="showPasswordModal = true"
              class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors text-sm font-medium">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              Change Password
            </button>

            <button @click="refreshProfile"
              class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Refresh Data
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else
      class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="text-gray-400 text-6xl mb-4">👤</div>
      <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Profile Found</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
        You haven't set up your profile yet. Create your profile to start using all features.
      </p>

      <!-- Mobile Action Buttons for Empty State -->
      <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
        <button @click="showCreateModal = true"
          class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Create Profile
        </button>

        <button @click="fetchProfile"
          class="w-full sm:w-auto flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Refresh
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Profile',
})

definePageMeta({
  middleware: 'auth'
});

const { t } = useLocale();
const { getCurrentUserId } = useAuth();

// Modal states
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showPasswordModal = ref(false);

// Reactive data
const profile = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);

// Fetch profile data
const fetchProfile = async () => {
  try {
    loading.value = true;
    error.value = null;

    const userId = getCurrentUserId();

    if (!userId) {
      error.value = 'No user ID found. Please log in again.';
      return;
    }

    const res = await $http(`/profiles/${userId}`, { method: 'GET' });

    if (res.error) {
      if (res.error.code === 404) {
        profile.value = null;
      } else {
        error.value = res.error.message || 'Failed to fetch profile';
        console.error('Failed to fetch profile:', res.error);
      }
    } else {
      profile.value = res.data.data || null;
    }
  } catch (err: any) {
    error.value = err.message || 'An unexpected error occurred';
    console.error('Failed to fetch profile:', err);
  } finally {
    loading.value = false;
  }
};

// Utility functions
const getTypeBadgeClass = (type: string) => {
  const classes = {
    admin: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    teacher: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    student: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
  };
  return classes[type as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const getGenderBadgeClass = (gender: string) => {
  const classes = {
    male: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    female: 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
    other: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
  };
  return classes[gender as keyof typeof classes] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const formatDate = (dateString: string) => {
  if (!dateString) return 'Not available';
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// Event handlers for modals
const handleProfileCreated = () => {
  showCreateModal.value = false;
  fetchProfile(); 
};

const handleProfileUpdated = () => {
  showEditModal.value = false;
  fetchProfile();
};

const handlePasswordUpdated = () => {
  showPasswordModal.value = false;
};

const refreshProfile = () => {
  fetchProfile();
};

// Fetch profile on component mount
onMounted(() => {
  fetchProfile();
});
</script>

<style scoped>
/* Custom responsive adjustments */
@media (max-width: 640px) {
  .max-w-6xl {
    margin-left: 1rem;
    margin-right: 1rem;
  }
}
</style>