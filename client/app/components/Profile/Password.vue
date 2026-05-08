<template>
  <form @submit.prevent="submit" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div>
      <h3 class="text-lg font-semibold text-gray-900">{{ t('profile.change_password') }}</h3>
      <p class="mt-1 text-sm text-gray-500">{{ t('profile.password_requirements') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.current_password') }}</label>
        <UIFormFieldsTextField
          v-model="form.current_password"
          name="current_password"
          type="password"
          placeholder="Enter current password"
          :error="!!errors.current_password"
          required
        >
          <template #prefix>
            <UIIconsLock class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.current_password" class="mt-1 text-xs text-red-600">{{ errors.current_password }}</p>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.new_password') }}</label>
        <UIFormFieldsTextField
          v-model="form.password"
          name="password"
          type="password"
          placeholder="Create new password"
          :error="!!errors.password"
          required
        >
          <template #prefix>
            <UIIconsLock class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.confirm_password') }}</label>
        <UIFormFieldsTextField
          v-model="form.password_confirmation"
          name="password_confirmation"
          type="password"
          placeholder="Confirm new password"
          :error="!!errors.password_confirmation"
          required
        >
          <template #prefix>
            <UIIconsCheckCircle class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ errors.password_confirmation }}</p>
      </div>
    </div>

    <div class="flex justify-end border-t border-gray-200 pt-6">
      <UIButton
        type="submit"
        variant="primary"
        size="md"
        :loading="changing"
        :disabled="changing"
      >
        {{ t('profile.update_password') }}
      </UIButton>
    </div>
  </form>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

const auth = useAuth()
const { t } = useLocalization()

const form = reactive({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const errors = reactive<Record<string, string>>({})
const changing = ref(false)

const submit = async () => {
  changing.value = true
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key])

  try {
    const response = await auth.changePassword(form)
    if (response.success) {
      form.current_password = ''
      form.password = ''
      form.password_confirmation = ''
      notification.success(response.message || 'Password updated')
    } else {
      if (response.errors) {
        Object.entries(response.errors).forEach(([key, msgs]) => {
          errors[key] = Array.isArray(msgs) ? msgs[0] : String(msgs)
        })
      }
      notification.error(response.message || 'Failed to update password')
    }
  } finally {
    changing.value = false
  }
}
</script>