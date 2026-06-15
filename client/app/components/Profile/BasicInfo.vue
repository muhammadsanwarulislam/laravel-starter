<template>
  <form
    @submit.prevent="$emit('submit')"
    class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
  >
    <div>
      <h3 class="text-lg font-semibold text-gray-900">
        {{ t("profile.basic_info") }}
      </h3>
      <p class="mt-1 text-sm text-gray-500">
        {{ t("profile.basic_info_description") }}
      </p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <!-- Full name -->
      <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("common.full_name")
        }}</label>
        <input
          id="profile-name"
          v-model="form.name"
          type="text"
          :class="inputClass(errors.name)"
          class="pl-10"
          placeholder="John Doe"
        />
        <p v-if="errors.name" class="mt-1 text-xs text-red-600">
          {{ errors.name }}
        </p>
      </div>

      <!-- Email -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("common.email")
        }}</label>
        <input
          id="profile-email"
          v-model="form.email"
          type="email"
          :class="inputClass(errors.email)"
          class="pl-10"
          placeholder="johndoe@example.com"
        />
        <p v-if="errors.email" class="mt-1 text-xs text-red-600">
          {{ errors.email }}
        </p>
      </div>

      <!-- Language -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("profile.preferred_language")
        }}</label>
        <select
          id="profile-locale"
          v-model="form.ui_locale"
          :class="inputClass(errors.ui_locale)"
        >
          <option value="">System Default</option>
          <option
            v-for="language in languages"
            :key="language.code"
            :value="language.code"
          >
            {{ language.name }}
          </option>
        </select>
        <p v-if="errors.ui_locale" class="mt-1 text-xs text-red-600">
          {{ errors.ui_locale }}
        </p>
      </div>

      <!-- Country Code -->
      <div>
        <label
          for="profile-country-code"
          class="mb-2 block text-sm font-medium text-gray-700"
          >Country Code</label
        >
        <div class="relative">
          <div
            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
          >
            <UIIconsPhone class="h-5 w-5 text-gray-400" />
          </div>
          <select
            id="profile-country-code"
            v-model.number="form.country_code_id"
            :class="inputClass(errors.country_code_id)"
            class="appearance-none pl-10"
          >
            <option :value="null">Select country code</option>
            <option
              v-for="country in countries"
              :key="country.id"
              :value="country.id"
            >
              {{ country.dial_code }} {{ country.name }}
            </option>
          </select>
          <div
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
          >
            <UIIconsChevronDown class="h-5 w-5 text-gray-400" />
          </div>
        </div>
        <p v-if="errors.country_code_id" class="mt-2 text-xs text-red-600">
          {{ errors.country_code_id }}
        </p>
      </div>

      <!-- Phone -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("common.phone")
        }}</label>
        <input
          id="profile-phone"
          v-model="form.phone"
          type="text"
          :class="inputClass(errors.phone)"
          class="pl-10"
          placeholder="01XXXXXXXXX"
        />
        <p v-if="errors.phone" class="mt-1 text-xs text-red-600">
          {{ errors.phone }}
        </p>
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
        {{ t("common.button.update") }}
      </UIButton>
    </div>
  </form>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
  form: any;
  errors: Record<string, string>;
  saving: boolean;
  countries: any[];
  languages: any[];
}>();

const emit = defineEmits<{
  (e: "submit"): void;
}>();

const { t } = useLocalization();

const inputClass = (error: string) => [
  "block w-full rounded-xl border px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-indigo-500",
  error ? "border-red-300 bg-red-50" : "border-gray-300 bg-white",
];

const countryOptions = computed(() => [
  ...props.countries.map((c: any) => ({
    value: c.id,
    label: `${c.dial_code} ${c.name}`,
  })),
]);

const languageOptions = computed(() =>
  Object.values(props.languages).map((lang: any) => ({
    value: lang.code,
    label: lang.name,
  })),
);
</script>
