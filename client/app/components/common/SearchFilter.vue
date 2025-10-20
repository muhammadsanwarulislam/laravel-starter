<template>
  <div class="bg-white p-4 rounded-lg shadow mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Search Input -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
        <div class="relative">
          <input
            :value="searchQuery"
            @input="handleSearchInput"
            type="text"
            :placeholder="searchPlaceholder"
            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Dynamic Filters -->
      <div v-for="filter in filters" :key="filter.key">
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ filter.label }}</label>
        <select
          :value="filterValues[filter.key]"
          @change="handleFilterChange(filter.key, $event.target.value)"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option v-for="option in filter.options" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>
      </div>

      <!-- Items Per Page -->
      <div v-if="showItemsPerPage">
        <label class="block text-sm font-medium text-gray-700 mb-1">Items per page</label>
        <select
          :value="itemsPerPage"
          @change="$emit('items-per-page-change', parseInt($event.target.value))"
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
</template>

<script setup lang="ts">
interface FilterOption {
  value: string;
  label: string;
}

interface Filter {
  key: string;
  label: string;
  options: FilterOption[];
}

interface Props {
  searchQuery: string;
  filters: Filter[];
  filterValues: Record<string, string>;
  itemsPerPage?: number;
  showItemsPerPage?: boolean;
  searchPlaceholder?: string;
}

withDefaults(defineProps<Props>(), {
  itemsPerPage: 10,
  showItemsPerPage: true,
  searchPlaceholder: 'Search...'
});

const emit = defineEmits<{
  'search-change': [query: string];
  'filter-change': [key: string, value: string];
  'items-per-page-change': [items: number];
}>();

let searchTimeout: NodeJS.Timeout;

const handleSearchInput = (event: Event) => {
  const value = (event.target as HTMLInputElement).value;
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    emit('search-change', value);
  }, 500);
};

const handleFilterChange = (key: string, value: string) => {
  emit('filter-change', key, value);
};
</script>