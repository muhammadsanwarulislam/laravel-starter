<template>
  <div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Users Management</h1>
      <button 
        @click="openCreateModal"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
      >
        Create User
      </button>
    </div>

    <!-- Search and Filters -->
    <CommonSearchFilter
      :search-query="searchQuery"
      :filters="userFilters"
      :filter-values="filters"
      :items-per-page="itemsPerPage"
      @search-change="handleSearch"
      @filter-change="handleFilterChange"
      @items-per-page-change="handleItemsPerPageChange"
    />

    <!-- Data Table -->
    <CommonDataTable
      :data="data"
      :columns="userColumns"
      :loading="isLoading"
      empty-message="No users found."
    >
      <!-- Custom column for name with translation -->
      <template #column-name="{ item, value }">
        <div>
          <div class="text-sm font-medium text-gray-900">{{ value }}</div>
          <div v-if="item.translations?.name?.bn" class="text-sm text-gray-500">
            {{ item.translations.name.bn }}
          </div>
        </div>
      </template>

      <!-- Custom column for status -->
      <template #column-is_active="{ value }">
        <span 
          :class="[
            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
            value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          ]"
        >
          {{ value ? 'Active' : 'Inactive' }}
        </span>
      </template>

      <!-- Actions column -->
      <template #actions="{ item }">
        <button 
          @click="openEditModal(item)"
          class="text-indigo-600 hover:text-indigo-900 mr-3"
        >
          Edit
        </button>
        <button 
          @click="openDeleteModal(item)"
          class="text-red-600 hover:text-red-900"
        >
          Delete
        </button>
      </template>
    </CommonDataTable>

    <!-- Pagination -->
    <CommonPagination
      v-if="pagination && data.length > 0"
      :pagination="pagination"
      :items-per-page="itemsPerPage"
      @prev="prevPage"
      @next="nextPage"
      @page-change="goToPage"
      @items-per-page-change="handleItemsPerPageChange"
    />

    <!-- Create/Edit Modal -->
    <CommonBaseModal
      :show="showModal"
      :title="editingUser ? 'Edit User' : 'Create User'"
      @close="closeModal"
    >
      <FormsUserForm
        :user="editingUser"
        :loading="isSubmitting"
        @submit="submitForm"
        @cancel="closeModal"
      />
    </CommonBaseModal>

    <!-- Delete Confirmation Modal -->
    <CommonBaseModal
      :show="showDeleteModal"
      title="Confirm Delete"
      size="sm"
      @close="closeDeleteModal"
    >
      <div class="text-center">
        <p class="text-gray-600 mb-4">
          Are you sure you want to delete user "{{ userToDelete?.name }}"?
        </p>
        <div class="flex justify-end space-x-3">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            :disabled="isDeleting"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
          >
            {{ isDeleting ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </CommonBaseModal>
  </div>
</template>

<script setup lang="ts">
// Composables
import { useCrudOperations } from '~/composables/useCrudOperations';

definePageMeta({
  middleware: 'auth'
});

// Use CRUD composable
const {
  data,
  isLoading,
  isSuccess,
  pagination,
  searchQuery,
  currentPage,
  itemsPerPage,
  filters,
  loadData,
  createItem,
  updateItem,
  deleteItem,
  handleSearch,
  handleFilterChange,
  handleItemsPerPageChange,
  nextPage,
  prevPage,
  goToPage
} = useCrudOperations({
  endpoint: 'users',
  defaultItemsPerPage: 10
});

// Table columns configuration
const userColumns = [
  { key: 'id', label: 'ID', class: 'w-20' },
  { key: 'name', label: 'Name' },
  { key: 'email', label: 'Email' },
  { key: 'phone', label: 'Phone' },
  { key: 'is_active', label: 'Status', type: 'status' },
  { key: 'created_at', label: 'Created At', type: 'date' }
];

// Filters configuration
const userFilters = [
  {
    key: 'status',
    label: 'Status',
    options: [
      { value: 'all', label: 'All Status' },
      { value: 'active', label: 'Active' },
      { value: 'inactive', label: 'Inactive' }
    ]
  }
];

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingUser = ref(null);
const userToDelete = ref(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);

const openCreateModal = () => {
  editingUser.value = null;
  showModal.value = true;
};

const openEditModal = (user: any) => {
  editingUser.value = user;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingUser.value = null;
};

const openDeleteModal = (user: any) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const submitForm = async (formData: any) => {
  isSubmitting.value = true;
  try {
    if (editingUser.value) {
      await updateItem(editingUser.value.id, formData);
    } else {
      await createItem(formData);
    }
    closeModal();
  } catch (error) {
    console.error('Error saving user:', error);
  } finally {
    isSubmitting.value = false;
  }
};

const confirmDelete = async () => {
  if (!userToDelete.value) return;
  
  isDeleting.value = true;
  try {
    await deleteItem(userToDelete.value.id);
    closeDeleteModal();
  } catch (error) {
    console.error('Error deleting user:', error);
  } finally {
    isDeleting.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>