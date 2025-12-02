<template>
  <div class="min-h-screen bg-white dark:bg-gray-900 flex flex-col">
    <!-- Header Slot -->
    <div v-if="$slots.header" class="flex-shrink-0 z-40">
      <slot name="header"></slot>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex">
      <!-- Sidebar Slot (only when provided) -->
      <aside v-if="$slots.sidebar" class="flex-shrink-0 z-30">
        <slot name="sidebar"></slot>
      </aside>
      
      <!-- Main Content Area -->
      <main class="flex-1 flex flex-col min-w-0">
        <!-- Body Slot (optional) -->
        <div v-if="$slots.body" class="flex-1">
          <slot name="body"></slot>
        </div>
        
        <!-- Default Slot for main content -->
        <div v-else class="flex-1">
          <slot></slot>
        </div>
        
        <!-- Footer Slot -->
        <div v-if="$slots.footer" class="flex-shrink-0 mt-auto">
          <slot name="footer"></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
// No script needed for basic layout component
</script>