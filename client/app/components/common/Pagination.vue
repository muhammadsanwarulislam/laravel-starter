<template>
  <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
    <div class="flex flex-col sm:flex-row items-center justify-between w-full gap-4">
      <!-- Pagination Info -->
      <div class="text-sm text-gray-700">
        Showing 
        <span class="font-medium">{{ pagination.from || 0 }}</span> to 
        <span class="font-medium">{{ pagination.to || 0 }}</span> of 
        <span class="font-medium">{{ pagination.total || 0 }}</span> results
      </div>

      <!-- Pagination Controls -->
      <div class="flex items-center space-x-2">
        <!-- Previous Button -->
        <button
          @click="$emit('prev')"
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
            @click="$emit('page-change', page)"
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
          @click="$emit('next')"
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

      <!-- Items Per Page -->
      <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-700">Show:</span>
        <select
          :value="itemsPerPage"
          @change="$emit('items-per-page-change', parseInt($event.target.value))"
          class="px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
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

interface Props {
  pagination: Pagination;
  itemsPerPage: number;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  'prev': [];
  'next': [];
  'page-change': [page: number];
  'items-per-page-change': [items: number];
}>();

const currentPage = computed(() => props.pagination.current_page || 1);

const visiblePages = computed(() => {
  const current = currentPage.value;
  const lastPage = props.pagination.last_page || 1;
  const delta = 2;
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
  
  return [...new Set(range)].filter(page => 
    page === '...' || (typeof page === 'number' && page >= 1 && page <= lastPage)
  );
});

const showEllipsis = computed(() => {
  return props.pagination.last_page > 7;
});
</script>