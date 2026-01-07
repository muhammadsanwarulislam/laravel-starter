<template>
  <span :class="badgeClasses">
    <slot name="icon" />
    <slot />
  </span>
</template>

<script setup lang="ts">
interface Props {
  variant?: 'primary' | 'secondary' | 'danger' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'gray'
  size?: 'xs' | 'sm' | 'md'
  outlined?: boolean
  rounded?: 'none' | 'sm' | 'full'
  iconOnly?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'sm',
  outlined: false,
  rounded: 'full',
  iconOnly: false
})

const badgeClasses = computed(() => {
  const base = 'inline-flex items-center gap-1.5 font-medium transition-colors'
  
  const sizes = {
    xs: props.iconOnly ? 'p-1 text-xs' : 'px-2 py-0.5 text-xs',
    sm: props.iconOnly ? 'p-1.5 text-xs' : 'px-2.5 py-1 text-xs',
    md: props.iconOnly ? 'p-2 text-sm' : 'px-3 py-1.5 text-sm'
  }
  
  const borderRadius = {
    none: 'rounded-none',
    sm: 'rounded',
    full: 'rounded-full'
  }
  
  // Filled variants
  const filled = {
    primary: 'bg-blue-100 text-blue-800 border border-blue-200',
    secondary: 'bg-gray-100 text-gray-800 border border-gray-200',
    danger: 'bg-red-100 text-red-800 border border-red-200',
    success: 'bg-emerald-100 text-emerald-800 border border-emerald-200',
    warning: 'bg-amber-100 text-amber-800 border border-amber-200',
    info: 'bg-cyan-100 text-cyan-800 border border-cyan-200',
    light: 'bg-gray-50 text-gray-700 border border-gray-100',
    dark: 'bg-gray-800 text-white border border-gray-700',
    gray: 'bg-gray-100 text-gray-700 border border-gray-200'
  }
  
  // Outlined variants (more subtle)
  const outlined = {
    primary: 'bg-white text-blue-700 border border-blue-300',
    secondary: 'bg-white text-gray-700 border border-gray-300',
    danger: 'bg-white text-red-700 border border-red-300',
    success: 'bg-white text-emerald-700 border border-emerald-300',
    warning: 'bg-white text-amber-700 border border-amber-300',
    info: 'bg-white text-cyan-700 border border-cyan-300',
    light: 'bg-white text-gray-600 border border-gray-200',
    dark: 'bg-white text-gray-800 border border-gray-400',
    gray: 'bg-white text-gray-600 border border-gray-300'
  }
  
  const variantClasses = props.outlined ? outlined[props.variant] : filled[props.variant]
  
  return [
    base,
    sizes[props.size],
    borderRadius[props.rounded],
    variantClasses
  ].join(' ')
})
</script>