<template>
    <div class="p-6">
        <SharedPageHeader title="Roles Management" description="Manage and monitor all system roles">
            <template #actions>
                <UIButton variant="primary" @click="$router.push('/roles/create')">
                    <template #icon>
                        <UIIconsPlus class="h-5 w-5" />
                    </template>
                    Add Role
                </UIButton>
            </template>
        </SharedPageHeader>

        <GenericTable :columns="roleColumns" :data="roles" :loading="loading" :pagination="pagination"
            @update:sort="handleSort" @update:page="handlePageChange">
            <template #column-permissions="{ item }">
                <span>{{ item.permissions?.[0]?.name || 'No Permissions' }}</span>
            </template>
            <template #actions="{ item }">
                <UIButton variant="secondary" size="xs" outlined title="Edit Role" @click="editRole(item.id)"
                    class="hover:shadow-md mr-2">
                    <template #icon>
                        <UIIconsPencil class="h-4 w-4" />
                    </template>
                    Edit
                </UIButton>

                <UIButton variant="danger" size="xs" outlined @click="openDeleteModal(item.id)" title="Delete Role"
                    class="hover:shadow-md">
                    <template #icon>
                        <UIIconsTrash class="h-4 w-4" />
                    </template>
                    Delete
                </UIButton>
            </template>
        </GenericTable>

        <ModalConfirmationDialog v-if="showDeleteModal" title="Delete Role" :message="deleteMessage" type="delete"
        @confirm="confirmDelete" @cancel="closeDeleteModal" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useRolePermission } from "~/composables/rolePermission/useRolePermission";
import { formatDate } from "~/utils/date";

definePageMeta({ middleware: ["auth"] });

const router = useRouter();
const { roles, loading, pagination, fetchRoles, deleteRole } = useRolePermission();

const showDeleteModal = ref(false);
const deleteRoleId = ref<number | null>(null);
const deleteMessage = ref(
  "Are you sure you want to delete this role? This action cannot be undone."
);

const roleColumns: Column[] = [
    { key: "id", label: "ID", sortable: true },
    { key: "name", label: "Name", sortable: true },
    { key: "description", label: "Description" },
    { key: "is_system", label: "System Role" },
    { key: "level", label: "Level" },
    { key: "permissions", label: "Permissions" },
    {
        key: "created_at",
        label: "Created At",
        sortable: true,
        format: (val) => formatDate(val),
    },
];

const handleSort = (key: string, order: "asc" | "desc") => {
    fetchRoles({ sort_field: key, sort_order: order, page: pagination.value?.currentPage || 1 });
};

const handlePageChange = (page: number) => {
    fetchRoles({ page });
};

const editRole = (id: number) => {
  router.push(`/roles/${id}/edit`);
};

const openDeleteModal = (id: number) => {
  deleteRoleId.value = id;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deleteRoleId.value = null;
};

const confirmDelete = async () => {
  if (deleteRoleId.value) {
    const response = await deleteRole(deleteRoleId.value);
    if (response.success) {
      closeDeleteModal();
    }
  }
};

onMounted(() => fetchRoles());
</script>
