<template>
  <div class="hidden md:block relative ml-4" @keydown.down.prevent="moveDown" @keydown.up.prevent="moveUp">
    <!-- Search Icon -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>

    <!-- Input Field -->
    <input v-model="query" @keydown.enter="handleEnter" type="text" placeholder="Search..."
      class="pl-10 pr-4 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-700 transition-colors duration-200 w-64" />

    <!-- Results Dropdown -->
    <ul v-if="query" role="listbox"
      class="absolute top-full mt-2 w-64 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50 border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-800">
      <li v-if="results.length === 0" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
        No matches found
      </li>
      <li v-for="(item, index) in results.slice(0, 7)" :key="item.path" role="option" @click="handleSelect(item)"
        :class="[
          'px-4 py-2 cursor-pointer text-sm text-gray-700 dark:text-gray-200',
          index === activeIndex ? 'bg-gray-100 dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700'
        ]">
        <strong>{{ item.name }}</strong><br />
        <span class="text-xs">{{ item.description }}</span>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const query = ref('')
const searchTerm = ref('')
const debounceTimer = ref(null)
const activeIndex = ref(-1)

watch(query, (val) => {
  clearTimeout(debounceTimer.value)
  debounceTimer.value = setTimeout(() => {
    searchTerm.value = val
    activeIndex.value = -1
  }, 200)
})

const menuGroup = getMenu().menuGroups;

const results = computed(() => {
  const term = searchTerm.value.toLowerCase();

  return menuGroup.value.flatMap(section => {
    return section.items.flatMap(item => {
      const merged = [];

      if (
        item.description &&
        (item.name.toLowerCase().includes(term) ||
          item.description.toLowerCase().includes(term))
      ) {
        merged.push(item);
      }

      if (Array.isArray(item.subItems)) {
        item.subItems.forEach(sub => {
          if (
            sub.description &&
            (sub.name.toLowerCase().includes(term) ||
              sub.description.toLowerCase().includes(term))
          ) {
            merged.push(sub);
          }
        });
      }

      return merged;
    });
  });
});


const handleSelect = (item) => {
  router.push(item.path)
}

const handleEnter = () => {
  if (activeIndex.value >= 0 && activeIndex.value < results.value.length) {
    handleSelect(results.value[activeIndex.value])
  } else if (results.value.length) {
    handleSelect(results.value[0])
  }
}

const moveDown = () => {
  if (results.value.length === 0) return
  activeIndex.value = (activeIndex.value + 1) % results.value.length
}

const moveUp = () => {
  if (results.value.length === 0) return
  activeIndex.value = (activeIndex.value - 1 + results.value.length) % results.value.length
}
</script>
