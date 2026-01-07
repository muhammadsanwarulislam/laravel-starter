<template>
  <!-- Backdrop -->
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Overlay -->
      <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="$emit('close')"></div>

      <!-- Modal -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center">
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
              <span class="text-blue-600 font-semibold text-lg">{{ getInitials(user.name) }}</span>
            </div>
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ user.name }}
              </h3>
              <p class="text-sm text-gray-500">{{ user.email }}</p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-500 focus:outline-none"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- User Details -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Basic Information -->
          <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h4>
            <div class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ user.email }}</dd>
                <div class="mt-1">
                  <span v-if="user.email_verified_at" class="inline-flex items-center text-green-600">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Verified
                  </span>
                  <span v-else class="inline-flex items-center text-red-600">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Not Verified
                  </span>
                </div>
              </div>
              <div v-if="user.phone">
                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ user.phone }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                  <span :class="user.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ user.status ? 'Active' : 'Inactive' }}
                  </span>
                </dd>
              </div>
            </div>
          </div>

          <!-- Account Information -->
          <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Account Information</h4>
            <div class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">User ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ user.id }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Created</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.created_at) }}</dd>
              </div>
              <div v-if="user.updated_at">
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.updated_at) }}</dd>
              </div>
              <div v-if="user.email_verified_at">
                <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.email_verified_at) }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                <dd class="mt-1">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ user.is_admin ? 'Administrator' : 'Regular User' }}
                  </span>
                </dd>
              </div>
            </div>
          </div>

          <!-- Roles & Permissions -->
          <div class="md:col-span-2 bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Roles & Permissions</h4>
            
            <!-- Roles -->
            <div class="mb-6">
              <h5 class="text-sm font-medium text-gray-700 mb-3">Assigned Roles</h5>
              <div v-if="user.roles && user.roles.length > 0" class="flex flex-wrap gap-2">
                <span
                  v-for="role in user.roles"
                  :key="role.id"
                  :class="getRoleColor(role.slug)"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                >
                  {{ role.name }}
                  <span v-if="role.description" class="ml-1 text-xs opacity-75">- {{ role.description }}</span>
                </span>
              </div>
              <p v-else class="text-sm text-gray-500">No roles assigned</p>
            </div>

            <!-- Permissions -->
            <div>
              <h5 class="text-sm font-medium text-gray-700 mb-3">Permissions</h5>
              <div v-if="permissions.length > 0" class="bg-white rounded border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Module
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permission
                      </th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="permission in permissions" :key="permission.id">
                      <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ permission.module }}
                      </td>
                      <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        {{ permission.name }}
                      </td>
                      <td class="px-4 py-3 text-sm text-gray-500">
                        {{ permission.description || 'No description' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p v-else class="text-sm text-gray-500">No permissions assigned</p>
            </div>
          </div>

          <!-- Recent Activity -->
          <div v-if="activity.length > 0" class="md:col-span-2 bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h4>
            <div class="space-y-4">
              <div
                v-for="item in activity"
                :key="item.id"
                class="flex items-start border-l-4 border-blue-500 pl-4 py-2"
              >
                <div class="flex-1">
                  <div class="flex justify-between">
                    <p class="text-sm font-medium text-gray-900">{{ item.description }}</p>
                    <p class="text-xs text-gray-500">{{ formatTime(item.timestamp) }}</p>
                  </div>
                  <div class="mt-1 flex items-center text-xs text-gray-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    {{ item.ip }}
                    <span class="mx-2">•</span>
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ item.userAgent }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const api = useApi()

interface Props {
  user: any
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
}>()

// State
const isLoading = ref(false)
const activity = ref<any[]>([])
const permissions = ref<any[]>([])

// Methods
const getInitials = (name: string) => {
  return name
    .split(' ')
    .map(part => part[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const getRoleColor = (slug: string) => {
  const colors: Record<string, string> = {
    'admin': 'bg-purple-100 text-purple-800',
    'super-admin': 'bg-red-100 text-red-800',
    'user': 'bg-blue-100 text-blue-800',
    'moderator': 'bg-green-100 text-green-800',
    'editor': 'bg-yellow-100 text-yellow-800'
  }
  return colors[slug] || 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60)

  if (diffInHours < 1) {
    return 'Just now'
  } else if (diffInHours < 24) {
    return `${Math.floor(diffInHours)} hours ago`
  } else if (diffInHours < 168) { // 7 days
    return `${Math.floor(diffInHours / 24)} days ago`
  } else {
    return date.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric'
    })
  }
}

const fetchUserDetails = async () => {
  try {
    isLoading.value = true
    
    // Fetch user activity
    const activityResponse = await api.user.getUserActivity(props.user.id)
    if (activityResponse.success && activityResponse.data) {
      activity.value = activityResponse.data
    }

    // Extract and organize permissions from roles
    if (props.user.roles) {
      const allPermissions: any[] = []
      props.user.roles.forEach((role: any) => {
        if (role.permissions) {
          role.permissions.forEach((permission: any) => {
            // Avoid duplicates
            if (!allPermissions.some(p => p.id === permission.id)) {
              allPermissions.push(permission)
            }
          })
        }
      })
      permissions.value = allPermissions
    }
  } catch (error) {
    console.error('Error fetching user details:', error)
  } finally {
    isLoading.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchUserDetails()
})
</script>