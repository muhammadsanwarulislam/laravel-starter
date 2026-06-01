<template>
  <form @submit.prevent="$emit('submit')" class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div>
      <h3 class="text-lg font-semibold text-gray-900">{{ t('profile.basic_info') }}</h3>
      <p class="mt-1 text-sm text-gray-500">{{ t('profile.basic_info_description') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <!-- Full name -->
      <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('common.full_name') }}</label>
        <UIFormFieldsTextField
          v-model="form.name"
          name="name"
          type="text"
          :placeholder="t('user.registration.form.name_placeholder')"
          :error="!!errors.name"
          required
        >
          <template #prefix>
            <UIIconsUser class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
      </div>

      <!-- Email -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('common.email') }}</label>
        <UIFormFieldsTextField
          v-model="form.email"
          name="email"
          type="email"
          placeholder="you@example.com"
          :error="!!errors.email"
        >
          <template #prefix>
            <UIIconsEnvelope class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
      </div>

      <!-- Language -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('profile.preferred_language') }}</label>
        <UIFormFieldsSelect
          v-model="form.ui_locale"
          :options="languageOptions"
          placeholder="System Default"
          :error="!!errors.ui_locale"
        >
          <template #prefix>
            <UIIconsLanguage class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsSelect>
        <p v-if="errors.ui_locale" class="mt-1 text-xs text-red-600">{{ errors.ui_locale }}</p>
      </div>

      <!-- Country Code -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('common.country_code') }}</label>
        <UIFormFieldsSelect
          v-model="form.country_code_id"
          :options="countryOptions"
          placeholder="Select country code"
          :error="!!errors.country_code_id"
        >
          <template #prefix>
            <UIIconsPhone class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsSelect>
        <p v-if="errors.country_code_id" class="mt-1 text-xs text-red-600">{{ errors.country_code_id }}</p>
      </div>

      <!-- Phone -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{ t('common.phone') }}</label>
        <UIFormFieldsTextField
          v-model="form.phone"
          name="phone"
          type="tel"
          placeholder="01XXXXXXXXX"
          :error="!!errors.phone"
        >
          <template #prefix>
            <UIIconsPhone class="h-5 w-5 text-gray-400" />
          </template>
        </UIFormFieldsTextField>
        <p v-if="errors.phone" class="mt-1 text-xs text-red-600">{{ errors.phone }}</p>
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
        {{ t('common.button.update') }}
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
  countries: any[]
  languages: any[]
}>()

const emit = defineEmits<{
  (e: 'submit'): void
}>()

const { t } = useLocalization()

const countryOptions = computed(() => [
  ...props.countries.map((c: any) => ({
    value: c.id,
    label: `${c.dial_code} ${c.name}`,
  })),
])

const languageOptions = computed(() =>
  Object.values(props.languages).map((lang: any) => ({
    value: lang.code,
    label: lang.name,
  }))
)
</script>