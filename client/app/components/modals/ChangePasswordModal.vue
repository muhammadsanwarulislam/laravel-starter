<!-- components/modals/ChangePasswordModal.vue -->
<template>
  <ModalsBaseModal :is-open="isOpen" @close="$emit('close')" title="Change Password" size="md">
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Current Password
        </label>
        <input 
          type="password" 
          v-model="form.currentPassword"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          placeholder="Enter current password"
        />
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          New Password
        </label>
        <input 
          type="password" 
          v-model="form.newPassword"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          placeholder="Enter new password"
        />
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Confirm New Password
        </label>
        <input 
          type="password" 
          v-model="form.confirmPassword"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          placeholder="Confirm new password"
        />
      </div>

      <div class="flex justify-end space-x-3 pt-4">
        <button 
          @click="$emit('close')"
          class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors"
        >
          Cancel
        </button>
        <button 
          @click="handleSubmit"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
        >
          Update Password
        </button>
      </div>
    </div>
  </ModalsBaseModal>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  isOpen: boolean;
}

defineProps<Props>();
const emit = defineEmits(['close', 'updated']);

const form = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
});

const handleSubmit = async () => {
  // Add password validation and API call here
  emit('updated');
};
</script>