<!-- components/common/FormActions.vue -->
<template>
  <div class="flex justify-end space-x-3 mt-6">
    <button
      type="button"
      @click="$emit('cancel')"
      class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
    >
      Cancel
    </button>
    <button
      type="submit"
      :disabled="loading"
      class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
    >
      {{ loading ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
    </button>
  </div>
</template>

<script setup lang="ts">
interface Props {
  loading?: boolean;
  isEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  isEdit: false
});

const emit = defineEmits<{
  cancel: [];
}>();
</script>

<style scoped>
button {
  transition: all 0.2s ease-in-out;
}

button:disabled {
  cursor: not-allowed;
}
</style>