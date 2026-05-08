<template>
  <form @submit.prevent="$emit('submit')" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div>
      <h3 class="text-lg font-semibold text-gray-900">{{ t('profile.profile_details') }}</h3>
      <p class="mt-1 text-sm text-gray-500">{{ t('profile.profile_details_description') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <!-- Gender -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.gender') }}</label>
        <UIFormFieldsSelect
          v-model="form.gender"
          :options="genderOptions"
          placeholder="Select gender"
          :error="!!errors.gender"
        >
          <template #prefix>
            <UIIconsUser class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsSelect>
        <p v-if="errors.gender" class="mt-1 text-xs text-red-600">{{ errors.gender }}</p>
      </div>

      <!-- Profile Type -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.profile_type') }}</label>
        <UIFormFieldsSelect
          v-model="form.type"
          :options="typeOptions"
          placeholder="Select type"
          :error="!!errors.type"
        >
          <template #prefix>
            <UIIconsBadge class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsSelect>
        <p v-if="errors.type" class="mt-1 text-xs text-red-600">{{ errors.type }}</p>
      </div>

      <!-- Address -->
      <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.address') }}</label>
        <UIFormFieldsTextField
          v-model="form.address"
          name="address"
          type="text"
          placeholder="House, road, area, city"
          :error="!!errors.address"
        >
          <template #prefix>
            <UIIconsLocation class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.address" class="mt-1 text-xs text-red-600">{{ errors.address }}</p>
      </div>
    </div>

    <div class="flex justify-end border-t border-gray-200 pt-6">
      <UIButton
        type="submit"
        variant="success"
        size="md"
        :loading="saving"
        :disabled="saving"
      >
        {{ t('common.save_changes') }}
      </UIButton>
    </div>
  </form>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  form: any
  errors: Record<string, string>
  saving: boolean
}>()

const emit = defineEmits<{
  (e: 'submit'): void
}>()

const { t } = useLocalization()

const genderOptions = [
  { value: 'male', label: t('profile.male') },
  { value: 'female', label: t('profile.female') },
  { value: 'other', label: t('profile.other') },
]

const typeOptions = [
  { value: 'student', label: t('profile.student') },
  { value: 'teacher', label: t('profile.teacher') },
  { value: 'admin', label: t('profile.admin') },
]
</script>