<template>
    <div>
        <!-- Header with title and create button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Users Management</h1>
            <button 
                @click="openCreateModal"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
            >
                Create User
            </button>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input
                        v-model="searchQuery"
                        @input="handleSearch"
                        type="text"
                        placeholder="Search by name, email, phone..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select
                        v-model="statusFilter"
                        @change="loadUsers"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Items Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Items per page</label>
                    <select
                        v-model="itemsPerPage"
                        @change="handleItemsPerPageChange"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="!isSuccess" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            Failed to load users. Please try again.
            <button @click="loadUsers" class="ml-4 text-red-700 underline">Retry</button>
        </div>

        <!-- Users Table -->
        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ user.id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                <div v-if="user.translations?.name?.bn" class="text-sm text-gray-500">
                                    {{ user.translations.name.bn }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ user.phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    :class="[
                                        'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                        user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                    ]"
                                >
                                    {{ user.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ formatDate(user.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button 
                                    @click="openEditModal(user)"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="openDeleteModal(user)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="users.length === 0 && !isLoading" class="text-center py-8">
                <p class="text-gray-500">No users found.</p>
            </div>

            <!-- Enhanced Pagination -->
            <div v-if="pagination && users.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex flex-col sm:flex-row items-center justify-between w-full gap-4">
                    <!-- Pagination Info -->
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ pagination.from || 0 }}</span> to 
                        <span class="font-medium">{{ pagination.to || 0 }}</span> of 
                        <span class="font-medium">{{ pagination.total || 0 }}</span> results
                    </div>

                    <!-- Pagination Controls -->
                    <div class="flex items-center space-x-2">
                        <!-- Previous Button -->
                        <button
                            @click="prevPage"
                            :disabled="!pagination.prev_page_url"
                            :class="[
                                'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                                pagination.prev_page_url 
                                    ? 'bg-gray-200 text-gray-700 hover:bg-gray-300 hover:text-gray-900' 
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Page Numbers -->
                        <div class="flex space-x-1">
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="goToPage(page)"
                                :class="[
                                    'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                                    page === currentPage
                                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300 hover:text-gray-900'
                                ]"
                            >
                                {{ page }}
                            </button>
                            <span v-if="showEllipsis" class="px-2 py-2 text-gray-500">...</span>
                        </div>

                        <!-- Next Button -->
                        <button
                            @click="nextPage"
                            :disabled="!pagination.next_page_url"
                            :class="[
                                'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                                pagination.next_page_url 
                                    ? 'bg-gray-200 text-gray-700 hover:bg-gray-300 hover:text-gray-900' 
                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4">
                        {{ editingUser ? 'Edit User' : 'Create User' }}
                    </h2>
                    
                    <form @submit.prevent="submitForm">
                        <div class="space-y-4">
                            <!-- Name Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input
                                    v-model="formData.name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input
                                    v-model="formData.email"
                                    type="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input
                                    v-model="formData.phone"
                                    type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <!-- Password Field (only for create) -->
                            <div v-if="!editingUser">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input
                                    v-model="formData.password"
                                    type="password"
                                    required
                                    placeholder="Enter password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long</p>
                            </div>

                            <!-- Password Field (optional for edit) -->
                            <div v-else>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input
                                    v-model="formData.password"
                                    type="password"
                                    placeholder="Leave blank to keep current password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                            </div>

                            <!-- Status Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    v-model="formData.is_active"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option :value="true">Active</option>
                                    <option :value="false">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                            >
                                {{ isSubmitting ? 'Saving...' : (editingUser ? 'Update' : 'Create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h2 class="text-xl font-bold mb-2">Confirm Delete</h2>
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
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: 'auth'
});

// Types
interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    is_active: boolean;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    translations?: {
        name: {
            bn: string;
        };
    };
    current_locale: string;
}

interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

interface FormData {
    name: string;
    email: string;
    phone: string;
    password?: string;
    is_active: boolean;
}

// Reactive state
const users = ref<User[]>([]);
const isLoading = ref(true);
const isSuccess = ref(false);
const pagination = ref<Pagination | null>(null);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const searchQuery = ref('');
const statusFilter = ref('all');

// Modal states
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingUser = ref<User | null>(null);
const userToDelete = ref<User | null>(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);

// Form data
const formData = ref<FormData>({
    name: '',
    email: '',
    phone: '',
    password: '',
    is_active: true
});

// Computed properties for pagination
const visiblePages = computed(() => {
    if (!pagination.value) return [];
    
    const current = currentPage.value;
    const lastPage = pagination.value.last_page || 1;
    const delta = 2; // Number of pages to show on each side of current page
    const range = [];
    
    for (let i = Math.max(2, current - delta); i <= Math.min(lastPage - 1, current + delta); i++) {
        range.push(i);
    }
    
    if (current - delta > 2) {
        range.unshift('...');
    }
    if (current + delta < lastPage - 1) {
        range.push('...');
    }
    
    range.unshift(1);
    if (lastPage > 1) {
        range.push(lastPage);
    }
    
    // Remove duplicates and filter out invalid pages
    return [...new Set(range)].filter(page => 
        page === '...' || (typeof page === 'number' && page >= 1 && page <= lastPage)
    );
});

const showEllipsis = computed(() => {
    return pagination.value && pagination.value.last_page > 7;
});

// Debounced search
let searchTimeout: NodeJS.Timeout;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage.value = 1;
        loadUsers();
    }, 500);
};

const handleItemsPerPageChange = () => {
    currentPage.value = 1;
    loadUsers();
};

const buildApiParams = () => {
    const params: any = {
        limit: itemsPerPage.value,
        offset: (currentPage.value - 1) * itemsPerPage.value,
        option: 'list'
    };

    if (searchQuery.value.trim() !== '') {
        params.option = 'search';
        params.searchData = searchQuery.value;
        params.searchFields = 'name,email,phone';
    } else if (statusFilter.value !== 'all') {
        params.option = 'search';
        params.searchData = `${statusFilter.value === 'active' ? 'true' : 'false'}`;
        params.searchFields = 'is_active';
    }

    return params;
};

const loadUsers = async () => {
    isLoading.value = true;
    const params = buildApiParams();
    
    const queryString = new URLSearchParams();
    Object.keys(params).forEach(key => {
        if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
            queryString.append(key, params[key]);
        }
    });

    const url = `users?${queryString.toString()}`;

    try {
        const response: any = await $http(url, { method: 'GET' });
        
        if (response?.data) {
            users.value = response.data.data || response.data;
            pagination.value = response.data.pagination || {};
            if (pagination.value.current_page) {
                currentPage.value = pagination.value.current_page;
            }
        } else {
            users.value = response;
        }
        isSuccess.value = true;
    } catch (error) {
        isSuccess.value = false;
        console.error('Error loading users:', error);
    } finally {
        isLoading.value = false;
    }
};

// Pagination methods
const nextPage = () => {
    if (pagination.value?.next_page_url) {
        currentPage.value++;
        loadUsers();
    }
};

const prevPage = () => {
    if (pagination.value?.prev_page_url) {
        currentPage.value--;
        loadUsers();
    }
};

const goToPage = (page: number | string) => {
    if (typeof page === 'number' && page >= 1 && page <= (pagination.value?.last_page || 1)) {
        currentPage.value = page;
        loadUsers();
    }
};

// Modal methods
const openCreateModal = () => {
    editingUser.value = null;
    formData.value = {
        name: '',
        email: '',
        phone: '',
        password: '',
        is_active: true
    };
    showModal.value = true;
};

const openEditModal = (user: User) => {
    editingUser.value = user;
    formData.value = {
        name: user.name,
        email: user.email,
        phone: user.phone,
        password: '', // Empty password for edit
        is_active: user.is_active
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingUser.value = null;
};

const openDeleteModal = (user: User) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

// CRUD Operations
const submitForm = async () => {
    isSubmitting.value = true;
    
    try {
        // Prepare form data - remove empty password for updates
        const submitData = { ...formData.value };
        
        if (editingUser.value && !submitData.password) {
            delete submitData.password; // Remove password if empty during update
        }

        if (editingUser.value) {
            // Update user
            await $http(`users/${editingUser.value.id}`, {
                method: 'PUT',
                body: submitData
            });
        } else {
            // Create user - password is required
            if (!submitData.password) {
                alert('Password is required for new users');
                isSubmitting.value = false;
                return;
            }
            await $http('users', {
                method: 'POST',
                body: submitData
            });
        }
        
        closeModal();
        loadUsers(); // Refresh the list
    } catch (error) {
        console.error('Error saving user:', error);
        alert('Error saving user. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
};

const confirmDelete = async () => {
    if (!userToDelete.value) return;
    
    isDeleting.value = true;
    
    try {
        await $http(`users/${userToDelete.value.id}`, {
            method: 'DELETE'
        });
        
        closeDeleteModal();
        loadUsers(); // Refresh the list
    } catch (error) {
        console.error('Error deleting user:', error);
        alert('Error deleting user. Please try again.');
    } finally {
        isDeleting.value = false;
    }
};

// Utility function
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

onMounted(() => {
    loadUsers();
});
</script>