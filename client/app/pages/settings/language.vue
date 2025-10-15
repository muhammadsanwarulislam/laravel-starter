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

        <!-- Loading State -->
        <div v-if="isLoading" class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-gray-600 mt-4">Loading languages...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="!isSuccess && !isLoading" class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="text-red-500 text-6xl mb-4">⚠️</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to load languages</h3>
            <p class="text-gray-600 mb-4">Please try again later</p>
            <button @click="loadLanguages"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Retry
            </button>
        </div>

        <!-- Success State with Summary Cards -->
        <div v-else-if="isSuccess">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Languages</p>
                            <p class="text-2xl font-bold text-gray-900">{{ pagination.total || 0 }}</p>
                        </div>
                        <div class="text-blue-500 bg-blue-50 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Languages</p>
                            <p class="text-2xl font-bold text-gray-900">{{ activeLanguagesCount }}</p>
                        </div>
                        <div class="text-green-500 bg-green-50 p-3 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">LTR Languages</p>
                            <p class="text-2xl font-bold text-gray-900">{{ ltrLanguagesCount }}</p>
                        </div>
                        <div class="text-orange-500 bg-orange-50 p-3 rounded-lg">
                            <span class="text-sm font-bold">LTR</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">RTL Languages</p>
                            <p class="text-2xl font-bold text-gray-900">{{ rtlLanguagesCount }}</p>
                        </div>
                        <div class="text-purple-500 bg-purple-50 p-3 rounded-lg">
                            <span class="text-sm font-bold">RTL</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input
                            v-model="searchQuery"
                            @input="handleSearch"
                            type="text"
                            placeholder="Search languages by code, name, native name..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    <div class="flex gap-2">
                        <select
                            v-model="statusFilter"
                            @change="handleFilterChange"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select
                            v-model="directionFilter"
                            @change="handleFilterChange"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="all">All Directions</option>
                            <option value="ltr">LTR</option>
                            <option value="rtl">RTL</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Languages Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Language
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Native Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Direction
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Default
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sort Order
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="language in languages" :key="language.id"
                                class="hover:bg-gray-50 transition-colors" :class="{
                                    'bg-blue-50 hover:bg-blue-100': language.is_default
                                }">
                                <!-- Language Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">
                                                {{ language.code.toUpperCase() }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ language.name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ language.id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Native Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium"
                                        :class="{ 'text-right': language.direction === 'rtl' }"
                                        :dir="language.direction">
                                        {{ language.native_name }}
                                    </div>
                                </td>

                                <!-- Direction -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-blue-100 text-blue-800': language.direction === 'ltr',
                                            'bg-orange-100 text-orange-800': language.direction === 'rtl'
                                        }">
                                        {{ language.direction.toUpperCase() }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800': language.is_active,
                                            'bg-red-100 text-red-800': !language.is_active
                                        }">
                                        <span class="w-2 h-2 rounded-full mr-1" :class="{
                                            'bg-green-500': language.is_active,
                                            'bg-red-500': !language.is_active
                                        }"></span>
                                        {{ language.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <!-- Default -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="language.is_default"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Default
                                    </span>
                                    <span v-else class="text-sm text-gray-500">—</span>
                                </td>

                                <!-- Sort Order -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ language.sort_order }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm transition-colors"
                                            @click="editLanguage(language)">
                                            Edit
                                        </button>
                                        <button
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            @click="deleteLanguage(language.id)" :disabled="language.is_default"
                                            :title="language.is_default ? 'Cannot delete default language' : 'Delete language'">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="languages.length === 0 && !isLoading" class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">🌐</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No languages found</h3>
                    <p class="text-gray-600">Try adjusting your search or filters</p>
                </div>

                <!-- Pagination -->
                <div v-if="languages.length > 0" class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                        <div class="text-sm text-gray-700">
                            Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="previousPage"
                                :disabled="currentPage === 1"
                                class="px-3 py-1 rounded border border-gray-300 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                                Previous
                            </button>
                            <div class="flex space-x-1">
                                <button
                                    v-for="page in visiblePages"
                                    :key="page"
                                    @click="goToPage(page)"
                                    :class="[
                                        'px-3 py-1 rounded text-sm',
                                        currentPage === page 
                                            ? 'bg-blue-600 text-white' 
                                            : 'border border-gray-300 text-gray-700 hover:bg-gray-50'
                                    ]">
                                    {{ page }}
                                </button>
                            </div>
                            <button
                                @click="nextPage"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1 rounded border border-gray-300 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50">
                                Next
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700">Show:</span>
                            <select
                                v-model="itemsPerPage"
                                @change="handleItemsPerPageChange"
                                class="px-2 py-1 border border-gray-300 rounded text-sm">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        {{ isEditing ? 'Edit Language' : 'Add New Language' }}
                    </h2>
                    
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
                            <input
                                v-model="form.code"
                                type="text"
                                required
                                maxlength="5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="en, fr, es, etc."
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="English, French, Spanish, etc."
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Native Name *</label>
                            <input
                                v-model="form.native_name"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="English, Français, Español, etc."
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Direction *</label>
                            <select
                                v-model="form.direction"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="ltr">Left to Right (LTR)</option>
                                <option value="rtl">Right to Left (RTL)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input
                                v-model="form.sort_order"
                                type="number"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                id="is_active"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input
                                v-model="form.is_default"
                                type="checkbox"
                                id="is_default"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="isEditing && currentLanguage?.is_default"
                            >
                            <label for="is_default" class="text-sm font-medium text-gray-700">
                                Set as default language
                                <span v-if="isEditing && currentLanguage?.is_default" class="text-orange-600 text-xs ml-1">
                                    (Current default)
                                </span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isSubmitting ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useCommonOperations } from '~/composables/useCommonOperations';
import { useNotification } from '~/composables/useNotification';

definePageMeta({
    middleware: 'auth'
});

const languages = ref([]);
const { isLoading, isSuccess, deleteOperation, createItem, updateItem } = useCommonOperations();

const { add: notify } = useNotification()

// Pagination and search
const currentPage = ref(1);
const itemsPerPage = ref(5);
const searchQuery = ref('');
const statusFilter = ref('all');
const directionFilter = ref('all');
const searchTimeout = ref(null);

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const isSubmitting = ref(false);
const editingId = ref(null);
const currentLanguage = ref(null);

// Pagination data
const pagination = ref({
    total: 0,
    per_page: 5,
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0
});

// Form data
const form = ref({
    code: '',
    name: '',
    native_name: '',
    direction: 'ltr',
    is_active: true,
    is_default: false,
    sort_order: 1
});

// Computed properties
const activeLanguagesCount = computed(() =>
    languages.value.filter(lang => lang.is_active).length
);

const ltrLanguagesCount = computed(() =>
    languages.value.filter(lang => lang.direction === 'ltr').length
);

const rtlLanguagesCount = computed(() =>
    languages.value.filter(lang => lang.direction === 'rtl').length
);

const totalPages = computed(() => pagination.value.last_page || 1);

const visiblePages = computed(() => {
    const pages = [];
    const current = pagination.value.current_page || 1;
    const last = pagination.value.last_page || 1;
    
    let start = Math.max(1, current - 2);
    let end = Math.min(last, start + 4);
    
    if (end - start < 4) {
        start = Math.max(1, end - 4);
    }
    
    for (let i = start; i <= end; i++) {
        pages.push(i);
    }
    return pages;
});

// Methods
const buildApiParams = () => {
    const params: any = {
        limit: itemsPerPage.value,
        offset: currentPage.value,
        option: 'list'
    };

    // Handle search
    if (searchQuery.value.trim() !== '') {
        params.option = 'search';
        params.searchData = searchQuery.value;
        params.searchFields = 'code,name,native_name';
    } 
    // Handle filters
    else if (statusFilter.value !== 'all' || directionFilter.value !== 'all') {
        params.option = 'search';
        
        if (statusFilter.value !== 'all' && directionFilter.value !== 'all') {
            params.searchData = `${statusFilter.value === 'active' ? 'true' : 'false'},${directionFilter.value}`;
            params.searchFields = 'is_active,direction';
        } else if (statusFilter.value !== 'all') {
            params.searchData = statusFilter.value === 'active' ? 'true' : 'false';
            params.searchFields = 'is_active';
        } else if (directionFilter.value !== 'all') {
            params.searchData = directionFilter.value;
            params.searchFields = 'direction';
        }
    }

    return params;
};

const loadLanguages = async () => {
    const params = buildApiParams();
    
    const queryString = new URLSearchParams();
    Object.keys(params).forEach(key => {
        if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
            queryString.append(key, params[key]);
        }
    });

    const url = `languages?${queryString.toString()}`;

    try {
        const response: any = await $http(url, { method: 'GET' });
        
        if (response?.data) {
            languages.value = response.data.data.languages;
            pagination.value = response.data.data.pagination;
            currentPage.value = response.data.data.pagination.current_page;
        } else {
            languages.value = response;
        }
        isSuccess.value = true;
    } catch (error) {
        console.error('Error loading languages:', error);
        isSuccess.value = false;
    } finally {
        isLoading.value = false;
    }
};

const handleSearch = () => {
    clearTimeout(searchTimeout.value);
    searchTimeout.value = setTimeout(() => {
        currentPage.value = 1;
        loadLanguages();
    }, 500);
};

const handleFilterChange = () => {
    currentPage.value = 1;
    loadLanguages();
};

const handleItemsPerPageChange = () => {
    currentPage.value = 1;
    loadLanguages();
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
        loadLanguages();
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
        loadLanguages();
    }
};

const goToPage = (page) => {
    currentPage.value = page;
    loadLanguages();
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    currentLanguage.value = null;
    resetForm();
    showModal.value = true;
};

const editLanguage = (language) => {
    isEditing.value = true;
    editingId.value = language.id;
    currentLanguage.value = language;
    form.value = { ...language };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = {
        code: '',
        name: '',
        native_name: '',
        direction: 'ltr',
        is_active: true,
        is_default: false,
        sort_order: (pagination.value.total || 0) + 1
    };
};

const submitForm = async () => {
    isSubmitting.value = true;
    
    try {
        if (isEditing.value) {
            await updateItem(editingId.value, form.value, languages, 'languages', 'language');
        } else {
            await createItem(form.value, languages, 'languages', 'language');
        }
        notify(`Language ${isEditing.value ? 'updated' : 'created'} successfully`, 'success');
        
        closeModal();
        await loadLanguages();
    } catch (error) {
        console.error('Error saving language:', error);
        alert(error.message || 'Error saving language');
    } finally {
        isSubmitting.value = false;
    }
};

const deleteLanguage = async (id) => {
    const language = languages.value.find(lang => lang.id === id);
    if (language?.is_default) {
        alert('Cannot delete the default language');
        return;
    }

    if (confirm('Are you sure you want to delete this language?')) {
        try {
            await deleteOperation('languages', id);
            await loadLanguages();
            notify('Language deleted successfully', 'success');
        } catch (error) {
            alert(error.message || 'Error deleting language');
        }
    }
};

// Watch for filter changes
watch([statusFilter, directionFilter], () => {
    currentPage.value = 1;
    loadLanguages();
});

onMounted(async () => {
    await loadLanguages();
});
</script>