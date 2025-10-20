<template>
  <form @submit.prevent="$emit('submit', formData)">
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
        <input
          v-model="formData.name"
          type="text"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
        <input
          v-model="formData.email"
          type="email"
          required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <input
          v-model="formData.phone"
          type="tel"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Password {{ !user ? '*' : '' }}
        </label>
        <input
          v-model="formData.password"
          type="password"
          :required="!user"
          :placeholder="user ? 'Leave blank to keep current password' : 'Enter password'"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <p class="text-xs text-gray-500 mt-1">
          {{ user ? 'Leave blank to keep current password' : 'Password must be at least 8 characters long' }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select
          v-model="formData.is_active"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option :value="true">Active</option>
          <option :value="false">Inactive</option>
        </select>
      </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
      <button
        type="button"
        @click="$emit('cancel')"
        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
      >
        Cancel
      </button>
      <button
        type="submit"
        :disabled="loading"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? 'Saving...' : (user ? 'Update' : 'Create') }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
interface User {
  id: number;
  name: string;
  email: string;
  phone: string;
  is_active: boolean;
}

interface Props {
  user?: User | null;
  loading?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  submit: [formData: any];
  cancel: [];
}>();

const formData = reactive({
  name: props.user?.name || '',
  email: props.user?.email || '',
  phone: props.user?.phone || '',
  password: '',
  is_active: props.user?.is_active ?? true
});

// Reset form when user changes
watch(() => props.user, (newUser) => {
  if (newUser) {
    formData.name = newUser.name;
    formData.email = newUser.email;
    formData.phone = newUser.phone;
    formData.password = '';
    formData.is_active = newUser.is_active;
  } else {
    formData.name = '';
    formData.email = '';
    formData.phone = '';
    formData.password = '';
    formData.is_active = true;
  }
});
</script>