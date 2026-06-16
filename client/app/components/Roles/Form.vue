<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <div
      v-if="loadingRole || loadingPermissions"
      class="flex justify-center py-8"
    >
      <UILoadingSpinner size="lg" />
    </div>

    <template v-else>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="col-span-full">
          <UIFormsInput
            id="role-name"
            v-model="values.name"
            :label="t('roles.form.name')"
            :placeholder="t('roles.form.name_placeholder')"
            :error="errors.name"
          />
        </div>

        <UIFormsInput
          id="role-level"
          v-model="values.level"
          type="number"
          :label="t('roles.form.level')"
          :placeholder="t('roles.form.level_placeholder', '10')"
          :error="errors.level"
          :hint="t('roles.form.level_info')"
        />

        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-4">
          <p class="text-sm font-medium text-gray-700">Role Type</p>
          <p class="mt-1 text-sm text-gray-600">
            {{
              values.is_system
                ? "System roles are protected from editing."
                : "Custom roles can be updated and deleted."
            }}
          </p>
          <div class="mt-3 flex items-center gap-2 text-xs font-medium">
            <span
              class="inline-flex items-center rounded-full px-2.5 py-1"
              :class="
                values.is_system
                  ? 'bg-amber-100 text-amber-800'
                  : 'bg-emerald-100 text-emerald-800'
              "
            >
              {{ values.is_system ? "System Role" : "Custom Role" }}
            </span>
          </div>
        </div>

        <div class="md:col-span-2">
          <UIFormsTextarea
            id="role-description"
            v-model="values.description"
            :label="t('common.description')"
            :placeholder="t('roles.form.details_placeholder')"
            :error="errors.description"
            :rows=4
          />
        </div>
      </div>

      <div class="border-t border-gray-200 pt-4">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h4 class="text-md font-medium text-gray-900">
              {{ t("common.permissions") }}
            </h4>
            <p class="text-sm text-gray-600 mt-1">
              {{ t("roles.form.permissions_info") }}
            </p>
          </div>
          <div
            class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700"
          >
            {{ values.permissions.length }} Selected
          </div>
        </div>

        <div
          v-if="Object.keys(groupedPermissions).length === 0"
          class="rounded-xl border border-dashed border-gray-300 px-4 py-10 text-center text-sm text-gray-500"
        >
          {{ t("roles.form.no_permissions") }}
        </div>

        <div v-else class="space-y-4">
          <section
            v-for="(modulePermissions, module) in groupedPermissions"
            :key="module"
            class="rounded-xl border border-gray-200 overflow-hidden"
          >
            <div
              class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3"
            >
              <div>
                <h4 class="text-sm font-semibold capitalize text-gray-900">
                  {{ module }}
                </h4>
                <p class="text-xs text-gray-500">
                  {{ modulePermissions.length }} {{ t("common.permissions") }}
                </p>
              </div>
              <button
                type="button"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                @click="toggleModulePermissions(modulePermissions)"
              >
                {{
                  areAllModulePermissionsSelected(modulePermissions)
                    ? "Clear"
                    : "Select"
                }}
              </button>
            </div>

            <div class="p-4">
              <UIFormsCheckboxGroup
                v-model="values.permissions"
                :options="mapPermissionsToOptions(modulePermissions)"
                :error="errors.permissions"
              />
            </div>
          </section>
        </div>
      </div>

      <!-- Form actions -->
      <div class="border-t border-gray-200 pt-4 flex justify-end space-x-3">
        <UIButton variant="secondary" size="sm" @click="router.push('/roles')">
          {{ t("common.button.cancel") }}
        </UIButton>
        <UIButton
          variant="primary"
          size="sm"
          type="submit"
          :loading="isSubmitting"
          :disabled="isEditMode && values.is_system"
        >
          {{
            isEditMode ? t("common.button.update") : t("common.button.create")
          }}
        </UIButton>
      </div>
    </template>
  </form>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import type { Permission, Role } from "~/api/types/api.types";
import { notification } from "~/utils/notification";
import { useFormValidation } from "~/composables/useFormValidation";

const props = defineProps<{
  roleId?: number;
}>();

const router = useRouter();
const api = useApi();
const { t } = useLocalization();

/**
 * ====================================== VALIDATION SCHEMA ======================================
 */
const validationSchema = {
  name: [
    (v: string) => (v?.trim() ? true : t("validation.required")),
    (v: string) => v?.length >= 2 || t("validation.min", { min: 2 }),
    (v: string) => v?.length <= 255 || t("validation.max", { max: 255 }),
    (v: string) =>
      /^[a-zA-Z0-9\s]+$/.test(v) || t("validation.name.alphanumeric"),
    (v: string) => !/^\d+$/.test(v) || t("validation.name.notOnlyNumbers"),
    (v: string) => !/^[-_0-9]/.test(v) || t("validation.name.noLeadingSpecial"),
    (v: string) =>
      (!v.startsWith(" ") && !v.endsWith(" ")) ||
      t("validation.name.noLeadingTrailingSpace"),
    (v: string) =>
      !v.includes("  ") || t("validation.name.noConsecutiveSpaces"),
  ],

  description: [
    (v: string) => !v || v.length <= 500 || t("validation.max", { max: 500 }),
  ],
  permissions: [
    (v: number[]) => !(isEditMode && values.is_system) || v.length >= 0,
    (v: number[]) => v.length > 0 || t("validation.permissions.required"),
  ],
};

/**
 * ====================================== FORM COMPOSABLE ======================================
 */
const { values, errors, isSubmitting, setValues, setApiErrors, handleSubmit } =
  useFormValidation({
    initialValues: {
      name: "",
      level: 0,
      description: "",
      is_system: false,
      permissions: [] as number[],
    },
    validationSchema,
    onSubmit: async (formData) => {
      const payload = {
        name: formData.name.trim(),
        level: formData.level,
        description: formData.description?.trim() || undefined,
        permissions: formData.permissions,
      };

      const response = props.roleId
        ? await api.role.updateRole(props.roleId, payload)
        : await api.role.createRole(payload);

      if (response.success) {
        notification.success(
          response.message ||
            (props.roleId
              ? t("roles.messages.updated")
              : t("roles.messages.created")),
        );
        router.push("/roles");
      } else if (response.errors) {
        setApiErrors(response.errors);
      }
      return response;
    },
  });

const isEditMode = computed(() => Number.isFinite(props.roleId));

const loadingRole = ref(false);
const loadingPermissions = ref(false);
const permissions = ref<Permission[]>([]);

/**
 * ====================================== PERMISSIONS HELPERS ======================================
 */
const groupedPermissions = computed<Record<string, Permission[]>>(() => {
  return permissions.value.reduce(
    (groups, permission) => {
      const mod = permission.module || "general";
      if (!groups[mod]) groups[mod] = [];
      groups[mod].push(permission);
      return groups;
    },
    {} as Record<string, Permission[]>,
  );
});

const mapPermissionsToOptions = (perms: Permission[]) => {
  return perms.map((p) => ({
    value: p.id,
    label: p.name,
    description: p.description || p.slug,
  }));
};

const areAllModulePermissionsSelected = (modulePermissions: Permission[]) => {
  return modulePermissions.every((p) => values.permissions.includes(p.id));
};

const toggleModulePermissions = (modulePermissions: Permission[]) => {
  const ids = modulePermissions.map((p) => p.id);
  if (areAllModulePermissionsSelected(modulePermissions)) {
    values.permissions = values.permissions.filter((id) => !ids.includes(id));
  } else {
    values.permissions = Array.from(new Set([...values.permissions, ...ids]));
  }
};

/**
 * ====================================== FETCH DATA ======================================
 */
const fetchPermissions = async () => {
  loadingPermissions.value = true;
  try {
    const response = await api.permission.getPermissions({ limit: 100 });
    if (response.success && response.data) {
      permissions.value = response.data;
    } else {
      notification.error(
        response.message || t("roles.errors.permissions_fetch"),
      );
    }
  } finally {
    loadingPermissions.value = false;
  }
};

const fetchRole = async () => {
  if (!isEditMode.value || !props.roleId) return;
  loadingRole.value = true;
  try {
    const response = await api.role.getRoleById(props.roleId);
    if (response.success && response.data) {
      const role = response.data;
      setValues({
        name: role.name,
        level: role.level,
        description: role.description || "",
        is_system: role.is_system,
        permissions: role.permissions?.map((p) => p.id) || [],
      });
    } else {
      notification.error(response.message || t("roles.errors.fetch"));
      router.push("/roles");
    }
  } finally {
    loadingRole.value = false;
  }
};

onMounted(async () => {
  await Promise.all([fetchPermissions(), fetchRole()]);
});
</script>
