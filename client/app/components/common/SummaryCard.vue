<!-- components/common/SummaryCard.vue -->
<template>
  <div class="bg-white rounded-xl shadow-sm p-6 border-l-4" :class="borderColorClass">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
      </div>
      <div class="p-3 rounded-lg" :class="iconColorClass">
        <span v-if="icon" class="text-2xl">{{ icon }}</span>
        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconPath" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string;
  value: number | string;
  icon?: string;
  color?: 'blue' | 'green' | 'orange' | 'purple' | 'red';
}

withDefaults(defineProps<Props>(), {
  color: 'blue'
});

const colorClasses = {
  blue: {
    border: 'border-blue-500',
    icon: 'text-blue-500 bg-blue-50'
  },
  green: {
    border: 'border-green-500',
    icon: 'text-green-500 bg-green-50'
  },
  orange: {
    border: 'border-orange-500',
    icon: 'text-orange-500 bg-orange-50'
  },
  purple: {
    border: 'border-purple-500',
    icon: 'text-purple-500 bg-purple-50'
  },
  red: {
    border: 'border-red-500',
    icon: 'text-red-500 bg-red-50'
  }
};

const borderColorClass = computed(() => colorClasses[props.color].border);
const iconColorClass = computed(() => colorClasses[props.color].icon);
</script>