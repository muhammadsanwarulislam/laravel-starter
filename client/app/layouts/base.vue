<template>
  <div class="h-screen flex flex-col overflow-hidden bg-white dark:bg-gray-900">
    <!-- Header Slot (fixed at top) -->
    <div v-if="$slots.header" class="shrink-0 z-40">
      <slot name="header"></slot>
    </div>

    <!-- Main Content Area (flex row, fills remaining height) -->
    <div class="flex-1 flex min-h-0 overflow-hidden">
      <!-- Sidebar Slot (sticky within its column) -->
      <aside v-if="$slots.sidebar" class="shrink-0 z-30 overflow-y-auto">
        <slot name="sidebar"></slot>
      </aside>
      
      <!-- Main Content Area (scrolls) -->
      <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Body Slot (optional) -->
        <div v-if="$slots.body" class="flex-1">
          <slot name="body"></slot>
        </div>
        
        <!-- Default Slot for main content -->
        <div v-else class="flex-1">
          <slot></slot>
        </div>
        
        <!-- Footer Slot (sticky at bottom of main) -->
        <div v-if="$slots.footer" class="shrink-0">
          <slot name="footer"></slot>
        </div>
      </main>
    </div>
  </div>
</template>