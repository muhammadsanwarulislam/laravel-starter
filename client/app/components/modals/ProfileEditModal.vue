<!-- components/modals/ProfileEditModal.vue -->
<template>
  <ModalsBaseModal :is-open="isOpen" @close="$emit('close')" title="Edit Profile" size="lg" />
    <ProfileForm 
      :mode="'edit'"
      :initial-data="profileData"
      @submit="handleSubmit"
      @cancel="$emit('close')"
    />
</template>

<script setup lang="ts">
interface Props {
  isOpen: boolean;
  profileData?: any;
}

defineProps<Props>();
const emit = defineEmits(['close', 'updated']);

const handleSubmit = async (formData: any) => {
  try {
    // Call your API to update profile
    const response = await $http(`/profiles/${profileData.id}`, {
      method: 'PUT',
      body: formData
    });

    if (response.success) {
      emit('updated');
    }
  } catch (error) {
    console.error('Failed to update profile:', error);
  }
};
</script>