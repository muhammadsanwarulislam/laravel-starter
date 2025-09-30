<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 flex flex-col">
    <!-- Header Slot -->
    <div v-if="$slots.header" class="flex-shrink-0">
      <slot name="header"></slot>
    </div>

    <!-- Main Content Area - Flexible for different layouts -->
    <main class="flex-1 flex flex-col" :class="{ 'flex-row': $slots.sidebar }">
      <!-- Sidebar Slot (only when provided) -->
      <aside v-if="$slots.sidebar" class="flex-shrink-0">
        <slot name="sidebar"></slot>
      </aside>
      
      <!-- Main Content Slot -->
      <div class="flex-1" :class="{ 'flex flex-col': !$slots.sidebar }">
        <slot name="body"></slot>
        <slot></slot> <!-- Default slot for main content -->
      </div>
    </main>

    <!-- Footer Slot -->
    <div v-if="$slots.footer" class="flex-shrink-0">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>

</script>