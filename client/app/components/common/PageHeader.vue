<template>
  <div v-if="route.path && route.path!='/'" class="text-text-light dark:text-text-dark transition-colors duration-300 mb-4">
    <header class="_pb-4 _border-b _border-gray-200 _dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div>
            <h1 class="text-2xl font-semibold leading-tight dark:text-gray-300">{{ item.name }}</h1>
            <p v-if="item.description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              {{ item.description }}
            </p>
          </div>
        </div>
      </div>
    </header>
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const menuGroup = getMenu().menuGroups
const item = ref({ name: 'Dashboard', description: 'Overview of key metrics', icon: '' })

const findMatchingItem = (menu, path) => {
  for (const section of menu) {
    for (const entry of section.items) {
      if (entry.path === path) return entry
      if (Array.isArray(entry.subItems)) {
        const match = entry.subItems.find(sub => sub.path === path)
        if (match) return match
      }
    }
  }
  return null
}

watchEffect(() => {
  const matched = findMatchingItem(menuGroup.value, route.path)
  if (matched) item.value = matched
})
</script>
