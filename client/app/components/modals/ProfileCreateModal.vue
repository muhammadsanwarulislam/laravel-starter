<!-- components/modals/ProfileCreateModal.vue -->
<template>
  <ModalsBaseModal :is-open="isOpen" @close="$emit('close')" title="Create Profile" size="lg" />
    <FormsProfileForm 
      :mode="'create'"
      @submit="handleSubmit"
      @cancel="$emit('close')"
    />
</template>

<script setup lang="ts">
interface Props {
  isOpen: boolean;
}

defineProps<Props>();
const emit = defineEmits(['close', 'saved']);

const handleSubmit = async (formData: any) => {
  try {
    // Call your API to create profile
    const response = await $http('/profiles', {
      method: 'POST',
      body: formData
    });

    if (response.success) {
      emit('saved');
    }
  } catch (error) {
    console.error('Failed to create profile:', error);
  }
};
</script>