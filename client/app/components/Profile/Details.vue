<template>
  <form
    @submit.prevent="$emit('submit')"
    class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
  >
    <div>
      <h3 class="text-lg font-semibold text-gray-900">
        {{ t("profile.title") }}
      </h3>
      <p class="mt-1 text-sm text-gray-500">{{ t("profile.details") }}</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-1">
      <!-- Gender -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("common.label.gender")
        }}</label>
        <select
          id="profile-gender"
          v-model="form.gender"
          :class="inputClass(errors.gender)"
        >
          <option value="">Select gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
        <p v-if="errors.gender" class="mt-1 text-xs text-red-600">
          {{ errors.gender }}
        </p>
      </div>

      <!-- Address -->
      <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-medium text-gray-700">{{
          t("common.label.address")
        }}</label>
        <input
          id="profile-address"
          v-model="form.address"
          type="text"
          :class="inputClass(errors.address)"
          class="pl-10"
          placeholder="Address"
        />
        <p v-if="errors.address" class="mt-1 text-xs text-red-600">
          {{ errors.address }}
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
}>();

const emit = defineEmits<{
  (e: "submit"): void;
}>();

const { t } = useLocalization();

const inputClass = (error: string) => [
  "block w-full rounded-xl border px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-indigo-500",
  error ? "border-red-300 bg-red-50" : "border-gray-300 bg-white",
];
</script>
