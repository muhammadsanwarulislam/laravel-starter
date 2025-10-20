<template>
  <div class="max-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Language Settings</h1>
        <p class="text-gray-600 mt-2">Manage your application languages and preferences</p>
      </div>
      <div class="flex items-center space-x-4 mt-4 sm:mt-0">
        <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border">
          Total: {{ pagination.total || 0 }} languages
        </span>
        <button
          @click="openCreateModal"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
          Add Language
        </button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <SummaryCard
        title="Total Languages"
        :value="pagination.total || 0"
        icon="🌐"
        color="blue"
      />
      <SummaryCard
        title="Active Languages"
        :value="activeLanguagesCount"
        icon="✅"
        color="green"
      />
      <SummaryCard
        title="LTR Languages"
        :value="ltrLanguagesCount"
        icon="⬅️"
        color="orange"
      />
      <SummaryCard
        title="RTL Languages"
        :value="rtlLanguagesCount"
        icon="➡️"
        color="purple"
      />
    </div>

    <!-- Search and Filters -->
    <SearchFilter
      :search-query="searchQuery"
      :filters="languageFilters"
      :filter-values="filters"
      :items-per-page="itemsPerPage"
      :search-placeholder="'Search languages by code, name, native name...'"
      @search-change="handleSearch"
      @filter-change="handleFilterChange"
      @items-per-page-change="handleItemsPerPageChange"
    />

    <!-- Loading State -->
    <div v-if="isLoading" class="bg-white rounded-xl shadow-sm p-8 text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="text-gray-600 mt-4">Loading languages...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="!isSuccess" class="bg-white rounded-xl shadow-sm p-8 text-center">
      <div class="text-red-500 text-6xl mb-4">⚠️</div>
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to load languages</h3>
      <p class="text-gray-600 mb-4">Please try again later</p>
      <button @click="loadData"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        Retry
      </button>
    </div>

    <!-- Data Table -->
    <DataTable
      v-else
      :data="data"
      :columns="languageColumns"
      :loading="isLoading"
      empty-message="No languages found. Try adjusting your search or filters."
    >
      <!-- Language Info Column -->
      <template #column-language="{ item }">
        <div class="flex items-center">
          <div
            class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-sm">
              {{ item.code.toUpperCase() }}
            </span>
          </div>
          <div class="ml-4">
            <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
            <div class="text-sm text-gray-500">ID: {{ item.id }}</div>
          </div>
        </div>
      </template>

      <!-- Native Name Column -->
      <template #column-native_name="{ item, value }">
        <div class="text-sm text-gray-900 font-medium"
          :class="{ 'text-right': item.direction === 'rtl' }"
          :dir="item.direction">
          {{ value }}
        </div>
      </template>

      <!-- Direction Column -->
      <template #column-direction="{ value }">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
          :class="{
            'bg-blue-100 text-blue-800': value === 'ltr',
            'bg-orange-100 text-orange-800': value === 'rtl'
          }">
          {{ value.toUpperCase() }}
        </span>
      </template>

      <!-- Status Column -->
      <template #column-is_active="{ value }">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
          :class="{
            'bg-green-100 text-green-800': value,
            'bg-red-100 text-red-800': !value
          }">
          <span class="w-2 h-2 rounded-full mr-1" :class="{
            'bg-green-500': value,
            'bg-red-500': !value
          }"></span>
          {{ value ? 'Active' : 'Inactive' }}
        </span>
      </template>

      <!-- Default Column -->
      <template #column-is_default="{ value, item }">
        <span v-if="value"
          class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          Default
        </span>
        <span v-else class="text-sm text-gray-500">—</span>
      </template>

      <!-- Actions Column -->
      <template #actions="{ item }">
        <div class="flex items-center space-x-2">
          <button
            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm transition-colors"
            @click="openEditModal(item)">
            Edit
          </button>
          <button
            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            @click="openDeleteModal(item)" 
            :disabled="item.is_default"
            :title="item.is_default ? 'Cannot delete default language' : 'Delete language'">
            Delete
          </button>
        </div>
      </template>
    </DataTable>

    <!-- Pagination -->
    <Pagination
      v-if="pagination && data.length > 0"
      :pagination="pagination"
      :items-per-page="itemsPerPage"
      @prev="prevPage"
      @next="nextPage"
      @page-change="goToPage"
      @items-per-page-change="handleItemsPerPageChange"
    />

    <!-- Create/Edit Modal -->
    <BaseModal
      :show="showModal"
      :title="editingLanguage ? 'Edit Language' : 'Add New Language'"
      size="md"
      @close="closeModal"
    >
      <LanguageForm
        :language="editingLanguage"
        :loading="isSubmitting"
        :total-languages="pagination.total || 0"
        @submit="submitForm"
        @cancel="closeModal"
      />
    </BaseModal>

    <!-- Delete Confirmation Modal -->
    <BaseModal
      :show="showDeleteModal"
      title="Confirm Delete"
      size="sm"
      @close="closeDeleteModal"
    >
      <div class="text-center">
        <div class="text-red-500 text-6xl mb-4">🗑️</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Language</h3>
        <p class="text-gray-600 mb-4">
          Are you sure you want to delete the language "{{ languageToDelete?.name }}"?
          This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-3">
          <button
            @click="closeDeleteModal"
            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            :disabled="isDeleting"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors"
          >
            {{ isDeleting ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
// Components
import BaseModal from '~/components/common/BaseModal.vue';
import DataTable from '~/components/common/DataTable.vue';
import Pagination from '~/components/common/Pagination.vue';
import SearchFilter from '~/components/common/SearchFilter.vue';
import SummaryCard from '~/components/common/SummaryCard.vue';
import LanguageForm from '~/components/forms/LanguageForm.vue';

// Composables
import { useCrudOperations } from '~/composables/useCrudOperations';
import { useNotification } from '~/composables/useNotification';

definePageMeta({
  middleware: 'auth'
});

const { add: notify } = useNotification();

// Use CRUD composable for languages
const {
  data: languages,
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
  endpoint: 'languages',
  defaultItemsPerPage: 5
});

// Table columns configuration
const languageColumns = [
  { key: 'language', label: 'Language' },
  { key: 'native_name', label: 'Native Name' },
  { key: 'direction', label: 'Direction' },
  { key: 'is_active', label: 'Status', type: 'status' },
  { key: 'is_default', label: 'Default' },
  { key: 'sort_order', label: 'Sort Order', type: 'number' }
];

// Filters configuration
const languageFilters = [
  {
    key: 'status',
    label: 'Status',
    options: [
      { value: 'all', label: 'All Status' },
      { value: 'active', label: 'Active' },
      { value: 'inactive', label: 'Inactive' }
    ]
  },
  {
    key: 'direction',
    label: 'Direction',
    options: [
      { value: 'all', label: 'All Directions' },
      { value: 'ltr', label: 'LTR' },
      { value: 'rtl', label: 'RTL' }
    ]
  }
];

// Computed properties for summary cards
const activeLanguagesCount = computed(() =>
  languages.value.filter(lang => lang.is_active).length
);

const ltrLanguagesCount = computed(() =>
  languages.value.filter(lang => lang.direction === 'ltr').length
);

const rtlLanguagesCount = computed(() =>
  languages.value.filter(lang => lang.direction === 'rtl').length
);

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingLanguage = ref<any>(null);
const languageToDelete = ref<any>(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);

// Modal methods
const openCreateModal = () => {
  editingLanguage.value = null;
  showModal.value = true;
};

const openEditModal = (language: any) => {
  editingLanguage.value = language;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingLanguage.value = null;
};

const openDeleteModal = (language: any) => {
  languageToDelete.value = language;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  languageToDelete.value = null;
};

// Form submission
const submitForm = async (formData: any) => {
  isSubmitting.value = true;
  
  try {
    if (editingLanguage.value) {
      await updateItem(editingLanguage.value.id, formData);
      notify('Language updated successfully', 'success');
    } else {
      await createItem(formData);
      notify('Language created successfully', 'success');
    }
    closeModal();
  } catch (error: any) {
    console.error('Error saving language:', error);
    notify(error.message || 'Error saving language', 'error');
  } finally {
    isSubmitting.value = false;
  }
};

// Delete confirmation
const confirmDelete = async () => {
  if (!languageToDelete.value) return;
  
  // Prevent deletion of default language
  if (languageToDelete.value.is_default) {
    notify('Cannot delete the default language', 'error');
    closeDeleteModal();
    return;
  }
  
  isDeleting.value = true;
  
  try {
    await deleteItem(languageToDelete.value.id);
    notify('Language deleted successfully', 'success');
    closeDeleteModal();
  } catch (error: any) {
    console.error('Error deleting language:', error);
    notify(error.message || 'Error deleting language', 'error');
  } finally {
    isDeleting.value = false;
  }
};

// Load data on mount
onMounted(() => {
  loadData();
});
</script>