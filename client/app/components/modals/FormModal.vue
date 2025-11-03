<template>
  <ModalsBaseModal
    :show="show"
    :title="title"
    :variant="variant"
    :icon="icon"
    :icon-color="iconColor"
    :size="size"
    @close="$emit('close')"
  >
    <FormsBuilder
      :fields="fields"
      :title="formTitle"
      :initial-data="initialData"
      :loading="loading"
      :is-edit="isEdit"
      :languages="languages"
      @submit="$emit('submit', $event)"
      @cancel="$emit('close')"
    />
  </ModalsBaseModal>
</template>

<script setup lang="ts">
import { FormField, Language } from '~/components/forms/Builder.vue';

interface Props {
  show: boolean;
  title: string;
  formTitle: string;
  fields: FormField[];
  initialData?: Record<string, any>;
  loading?: boolean;
  isEdit?: boolean;
  languages?: Language[];
  variant?: 'default' | 'dark' | 'colored';
  icon?: string | object;
  iconColor?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'pink';
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'full';
}

const props = withDefaults(defineProps<Props>(), {
  initialData: () => ({}),
  loading: false,
  isEdit: false,
  variant: 'default',
  iconColor: 'blue',
  size: 'md'
});

const emit = defineEmits<{
  close: [];
  submit: [formData: any];
}>();

// Debug function to log initial data
watch(() => props.initialData, (newData) => {
}, { immediate: true, deep: true });
</script>