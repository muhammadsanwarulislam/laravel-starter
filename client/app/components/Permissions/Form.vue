<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div v-if="loadingPermission" class="flex justify-center py-8">
      <UILoadingSpinner size="lg" />
    </div>

    <template v-else>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="col-span-full">
          <label
            for="permission-name"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            {{ t("permissions.form.name") }}
          </label>
          <div class="relative">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <UIIconsRolePermissions class="h-5 w-5 text-gray-400" />
            </div>
            <input
              id="permission-name"
              v-model="values.name"
              type="text"
              :class="[
                'block w-full pl-10 pr-3 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
                errors.name ? 'border-red-300 bg-red-50' : 'border-gray-300',
              ]"
              :placeholder="t('permissions.form.name_placeholder')"
            />
          </div>
          <p
            v-if="errors.name"
            class="mt-2 text-xs text-red-600 flex items-center"
          >
            <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
            {{ errors.name }}
          </p>
          <p
            v-else-if="values.name.trim()"
            class="mt-2 text-xs text-green-600 flex items-center"
          >
            <UIIconsCheck class="h-4 w-4 mr-1 text-green-600" />
            {{ t("permissions.form.name_valid") }}
          </p>
        </div>

        <div>
          <label
            for="permission-module"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            {{ t("permissions.form.module") }}
          </label>
          <input
            id="permission-module"
            v-model="values.module"
            type="text"
            :class="[
              'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
              errors.module ? 'border-red-300 bg-red-50' : 'border-gray-300',
            ]"
            placeholder="reports"
          />
          <p
            v-if="errors.module"
            class="mt-2 text-xs text-red-600 flex items-center"
          >
            <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
            {{ errors.module }}
          </p>
          <p v-else class="mt-2 text-xs text-gray-500">
            {{ t("permissions.form.module_info") }}
          </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-4">
          <p class="text-sm font-medium text-gray-700">
            {{ t("permissions.slug_generated") }}
          </p>
          <p class="mt-1 text-sm font-semibold text-gray-900">
            {{ previewSlug || "name-module" }}
          </p>
          <p class="mt-2 text-xs text-gray-500">
            The API generates the slug from the permission name and module.
          </p>
        </div>

        <div class="col-span-full">
          <label
            for="permission-description"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            {{ t("common.description") }}
          </label>
          <textarea
            id="permission-description"
            v-model="values.description"
            rows="4"
            :class="[
              'block w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-gray-900 placeholder-gray-500 transition-all duration-200',
              errors.description
                ? 'border-red-300 bg-red-50'
                : 'border-gray-300',
            ]"
            placeholder="Allows team members to view reporting dashboards and exports."
          />
          <p
            v-if="errors.description"
            class="mt-2 text-xs text-red-600 flex items-center"
          >
            <UIIconsExclamation2 class="h-4 w-4 mr-1 text-red-600" />
            {{ errors.description }}
          </p>
        </div>
      </div>

      <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <h4 class="text-sm font-semibold text-gray-900">Naming Guide</h4>
        <p class="mt-1 text-sm text-gray-600">
          Keep permission names action-focused, like "Create User" or "Export
          Invoice", and group them with a consistent module.
        </p>
      </div>

      <div class="border-t border-gray-200 pt-4 flex justify-end space-x-3">
        <UIButton
          variant="secondary"
          size="sm"
          title="Cancel"
          @click="router.push('/permissions')"
        >
          {{ t("common.button.cancel") }}
        </UIButton>
        <UIButton
          variant="primary"
          size="sm"
          title="Save"
          type="submit"
          :loading="isSubmitting"
        >
          {{ t("common.button.save") }}
        </UIButton>
      </div>
    </template>
  </form>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { notification } from "~/utils/notification";
import { useFormValidation } from "~/composables/useFormValidation";

const props = defineProps<{
  permissionId?: number;
}>();

const router = useRouter();
const api = useApi();
const { t } = useLocalization();

/**
 * ====================================== FORM FIELDS VALIDATION SCHEMA ======================================
 */
const validationSchema = {
  name: [
    (v: string) => (v?.trim() ? true : t("validation.required")),
    (v: string) => (v?.length >= 2 || t("validation.min", { min: 2 })),
    (v: string) => (v?.length <= 255 || t("validation.max", { max: 255 })),
    (v: string) => (/^[a-zA-Z0-9\s]+$/.test(v) || t("validation.name.alphanumeric")),
    (v: string) => (!/^\d+$/.test(v) || t("validation.name.notOnlyNumbers")),
    (v: string) => (!/^[-_0-9]/.test(v) || t("validation.name.noLeadingSpecial")),
    (v: string) => (!v.startsWith(' ') && !v.endsWith(' ') || t("validation.name.noLeadingTrailingSpace")),
    (v: string) => (!v.includes('  ') || t("validation.name.noConsecutiveSpaces")),
  ],
  module: [
    (v: string) => (v?.trim() ? true : t("validation.required")),
    (v: string) => (v?.length >= 2 || t("validation.min", { min: 2 })),
    (v: string) => (v?.length <= 100 || t("validation.max", { max: 100 })),
    (v: string) => (/^[a-z0-9_-]+$/.test(v) || t("validation.module.lowercase")),
  ]
};

/**
 * 
 */
const { values, errors, isSubmitting, setValues, setApiErrors, handleSubmit } = useFormValidation({
  initialValues: {
    name: "",
    module: "",
    description: "",
  },
  validationSchema,
  onSubmit: async (formData) => {
    const payload = {
      name: formData.name.trim(),
      module: formData.module.trim(),
      description: formData.description?.trim() || undefined,
    };

    const response = props.permissionId
      ? await api.permission.updatePermission(props.permissionId, payload)
      : await api.permission.createPermission(payload);

    if (response.success) {
      const savedId = response.data?.id || props.permissionId;
      notification.success(
        response.message ||
          (props.permissionId
            ? "Permission updated successfully"
            : "Permission created successfully")
      );
      router.push('/permissions');
    } else if (response.errors) {
      setApiErrors(response.errors);
    }
    return response;
  },
});

const isEditMode = computed(() => Number.isFinite(props.permissionId));
const loadingPermission = ref(false);

/**
 * ============================================== PREVIEW SLUG ==============================
 */
const previewSlug = computed(() => {
  const slugify = (value: string) =>
    value
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, "")
      .replace(/\s+/g, "-")
      .replace(/-+/g, "-");

  const name = values.name.trim();
  const module = values.module.trim();

  if (!name && !module) return "";
  return `${slugify(name || "name")}-${slugify(module || "module")}`.replace(/^-|-$/g, "");
});

/**
 * ==============================FETCH PERMISSION BY ID FOR EDIT MODE ==============================
 */
const fetchPermission = async () => {
  if (!isEditMode.value || !props.permissionId) return;

  loadingPermission.value = true;
  try {
    const response = await api.permission.getPermissionById(props.permissionId);
    if (response.success && response.data) {
      setValues({
        name: response.data.name,
        module: response.data.module,
        description: response.data.description || "",
      });
    } else {
      notification.error(response.message || "Failed to fetch permission");
      router.push("/permissions");
    }
  } finally {
    loadingPermission.value = false;
  }
};

onMounted(fetchPermission);
</script>