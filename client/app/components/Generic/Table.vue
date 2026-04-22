<template>
  <div class="overflow-x-auto bg-white rounded-lg shadow">
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center py-10">
      <UILoadingSpinner />
    </div>

    <!-- Table -->
    <table v-else class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th v-for="col in columns" :key="col.key"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            :class="{ 'cursor-pointer hover:bg-gray-100': col.sortable }" @click="col.sortable && handleSort(col.key)">
            <div class="flex items-center gap-1">
              {{ col.label }}
              <span v-if="col.sortable && sortKey === col.key" class="text-gray-400">
                {{ sortOrder === "asc" ? "↑" : "↓" }}
              </span>
            </div>
          </th>
          <th v-if="hasActions" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider items-center">
            {{ t("common.actions") }}
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="(item, idx) in displayedData" :key="item.id || idx">
          <td v-for="col in columns" :key="col.key" class="px-6 py-4 whitespace-nowrap text-sm"
            :class="col.cellClass || 'text-gray-900'">
            <!-- Custom slot for column -->
            <slot :name="`column-${col.key}`" :item="item" :value="item[col.key]">
              {{ formatValue(item[col.key], col) }}
            </slot>
          </td>
          <td v-if="hasActions" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <slot name="actions" :item="item" />
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Empty state -->
    <div v-if="!loading && displayedData.length === 0" class="text-center py-10 text-gray-500">
      <!-- <slot name="empty"> No data available. </slot> -->
      <UILoadingSpinner />
    </div>

    <!-- Pagination -->
    <div v-if="pagination && totalPages > 1" class="px-6 py-3 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Showing {{ from }} to {{ to }} of {{ total }} results
        </div>
        <div class="flex gap-2">
          <button @click="emitPage(currentPage - 1)" :disabled="currentPage === 1"
            class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed">
            {{ t("common.previous") }}
          </button>
          <span class="px-3 py-1">Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="emitPage(currentPage + 1)" :disabled="currentPage === totalPages"
            class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed">
            {{ t("common.next") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";

export interface Column {
  key: string;
  label: string;
  sortable?: boolean;
  cellClass?: string;
  format?: (value: any) => string;
}

const props = defineProps<{
  columns: Column[];
  data: any[];
  loading?: boolean;
  pagination?: {
    currentPage: number;
    lastPage: number;
    total: number;
    from: number;
    to: number;
    perPage: number;
  };
}>();

const { t } = useLocalization();

const emit = defineEmits<{
  (e: "update:sort", key: string, order: "asc" | "desc"): void;
  (e: "update:page", page: number): void;
}>();

const hasActions = computed(() => !!useSlots().actions);

const sortKey = ref<string | null>(null);
const sortOrder = ref<"asc" | "desc">("asc");

const handleSort = (key: string) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
  } else {
    sortKey.value = key;
    sortOrder.value = "asc";
  }
  emit("update:sort", key, sortOrder.value);
};

const displayedData = computed(() => {
  if (!sortKey.value) return props.data;
  return [...props.data].sort((a, b) => {
    const aVal = a[sortKey.value!];
    const bVal = b[sortKey.value!];
    if (aVal === bVal) return 0;
    const result = aVal > bVal ? 1 : -1;
    return sortOrder.value === "asc" ? result : -result;
  });
});

const formatValue = (value: any, col: Column) => {
  if (col.format) return col.format(value);
  if (value === null || value === undefined) return "—";
  if (typeof value === "boolean") return value ? "Yes" : "No";
  return value;
};

// Pagination computed
const currentPage = computed(() => props.pagination?.currentPage || 1);
const totalPages = computed(() => props.pagination?.lastPage || 1);
const total = computed(() => props.pagination?.total || 0);
const from = computed(() => props.pagination?.from || 0);
const to = computed(() => props.pagination?.to || 0);

const emitPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    emit("update:page", page);
  }
};
</script>