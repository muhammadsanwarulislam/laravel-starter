<template>
  <div class="p-6">
    <SharedPageHeader :title="t('users.title')" :description="t('users.description')">
      <template #actions>
        <UIButton variant="primary" @click="$router.push('/users/create')">
          <template #icon>
            <UIIconsPlus class="h-5 w-5" />
          </template>
          {{ t('common.button.create') }}
        </UIButton>
      </template>
    </SharedPageHeader>

    <GenericTable :columns="userColumns" :data="users" :loading="loading" :pagination="pagination"
      @update:sort="handleSort" @update:page="handlePageChange">
      <!-- Custom column: roles as badges -->
      <template #column-roles="{ item }">
        <div class="flex flex-wrap gap-1">
          <span v-for="role in item.roles" :key="role.id"
            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
            :class="getRoleBadgeClass(role.slug)">
            {{ role.name }}
          </span>
          <span v-if="!item.roles?.length" class="text-gray-400">—</span>
        </div>
      </template>

      <!-- Custom column: status as toggle -->
      <template #column-status="{ item }">
        <button @click="toggleUserStatus(item)"
          class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out"
          :class="item.status ? 'bg-green-600' : 'bg-gray-200'">
          <span
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
            :class="item.status ? 'translate-x-5' : 'translate-x-0'" />
        </button>
      </template>

      <!-- Actions slot -->
      <template #actions="{ item }">
        <UIButton variant="secondary" size="xs" outlined title="Edit User" @click="editUser(item.id)"
          class="hover:shadow-md mr-2">
          <template #icon>
            <UIIconsPencil class="h-4 w-4" />
          </template>
          {{ t('common.button.edit') }}
        </UIButton>

        <UIButton variant="danger" size="xs" outlined @click="openDeleteModal(item.id)" title="Delete User"
          class="hover:shadow-md">
          <template #icon>
            <UIIconsTrash class="h-4 w-4" />
          </template>
          {{ t('common.button.delete') }}
        </UIButton>
      </template>
    </GenericTable>

    <!-- Delete Confirmation Modal -->
    <ModalConfirmationDialog v-if="showDeleteModal" :title="t('common.button.delete')" :message="deleteMessage" type="delete"
      @confirm="confirmDelete" @cancel="closeDeleteModal" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"
import { useUsers } from "~/composables/user/useUser"
import { formatDate } from "~/utils/date"

definePageMeta({ middleware: ["auth"] })

const router = useRouter()
const { t } = useLocalization()
const { users, loading, pagination, fetchUsers, updateStatus, deleteUser } = useUsers()

const showDeleteModal = ref(false)
const deleteUserId = ref<number | null>(null)
const deleteMessage = ref(
  "Are you sure you want to delete this user? This action cannot be undone."
)

const userColumns: Column[] = [
  { key: "id", label: "ID", sortable: true },
  { key: "name", label: t("common.name"), sortable: true },
  { key: "email", label: t("common.email"), sortable: true },
  { key: "phone", label: t("common.phone") },
  { key: "roles", label: t("common.roles") },
  { key: "status", label: t("common.status"), sortable: true },
  // {
  //   key: "created_at",
  //   label: t("common.created_at"),
  //   sortable: true,
  //   format: (val) => formatDate(val),
  // },
]

const handleSort = (key: string, order: "asc" | "desc") => {
  fetchUsers({ sort_by: key, sort_order: order })
}

const handlePageChange = (page: number) => {
  fetchUsers({ page });
}

const getRoleBadgeClass = (slug: string) => {
  const map: Record<string, string> = {
    super_admin: "bg-purple-100 text-purple-800",
    admin: "bg-red-100 text-red-800",
    manager: "bg-blue-100 text-blue-800",
    editor: "bg-green-100 text-green-800",
    guest: "bg-gray-100 text-gray-800",
  };
  return map[slug] || "bg-gray-100 text-gray-800";
}

const editUser = (id: number) => {
  router.push(`/users/${id}/edit`);
}

const toggleUserStatus = async (user: any) => {
  await updateStatus(user.id, !user.status);
  fetchUsers();
}

const openDeleteModal = (id: number) => {
  deleteUserId.value = id;
  showDeleteModal.value = true;
}

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deleteUserId.value = null;
}

const confirmDelete = async () => {
  if (deleteUserId.value) {
    const success = await deleteUser(deleteUserId.value);
    if (success) {
      closeDeleteModal();
    }
  }
}

onMounted(() => fetchUsers())
</script>
