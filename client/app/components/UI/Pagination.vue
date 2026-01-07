<template>
  <div class="flex justify-between items-center">
    <div class="text-sm text-gray-700">
      Showing {{ from }} to {{ to }} of {{ total }} results
    </div>
    <div class="flex gap-2">
      <UIButton
        variant="secondary"
        size="sm"
        @click="handlePageChange(currentPage - 1)"
        :disabled="currentPage === 1"
      >
        Previous
      </UIButton>
      
      <div class="hidden md:flex gap-1">
        <UIButton
          v-for="page in visiblePages"
          :key="page"
          :variant="currentPage === page ? 'primary' : 'secondary'"
          size="sm"
          @click="handlePageChange(page)"
        >
          {{ page }}
        </UIButton>
      </div>
      
      <UIButton
        variant="secondary"
        size="sm"
        @click="handlePageChange(currentPage + 1)"
        :disabled="currentPage === lastPage"
      >
        Next
      </UIButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  currentPage: number
  lastPage: number
  total: number
  from: number
  to: number
  maxVisible?: number
}

const props = withDefaults(defineProps<Props>(), {
  maxVisible: 5
})

const emit = defineEmits<{
  'page-change': [page: number]
}>()

const visiblePages = computed(() => {
  const pages = []
  let start = Math.max(1, props.currentPage - Math.floor(props.maxVisible / 2))
  const end = Math.min(props.lastPage, start + props.maxVisible - 1)
  
  if (end - start + 1 < props.maxVisible) {
    start = Math.max(1, end - props.maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

const handlePageChange = (page: number) => {
  if (page >= 1 && page <= props.lastPage) {
    emit('page-change', page)
  }
}
</script>