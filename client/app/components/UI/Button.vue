<template>
    <button :type="type" :disabled="disabled" :class="buttonClasses" :aria-disabled="disabled" :title="props.title"
        @click="$emit('click', $event)">
        <slot name="icon" />
        <slot />
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    variant?: 'primary' | 'secondary' | 'danger' | 'success' | 'warning' | 'info' | 'light' | 'dark' | 'gradient'
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl'
    type?: 'button' | 'submit' | 'reset'
    disabled?: boolean
    outlined?: boolean
    rounded?: 'none' | 'sm' | 'md' | 'lg' | 'full'
    shadow?: 'none' | 'sm' | 'md' | 'lg'
    title?: string
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    type: 'button',
    disabled: false,
    outlined: false,
    rounded: 'md',
    shadow: 'sm',
    title: ''
})

const buttonClasses = computed(() => {
    const sizes = {
        xs: 'px-2.5 py-1.5 text-xs',
        sm: 'px-3 py-2 text-sm',
        md: 'px-4 py-2.5 text-sm',
        lg: 'px-5 py-3 text-base',
        xl: 'px-6 py-3.5 text-base'
    }

    const borderRadius = {
        none: 'rounded-none',
        sm: 'rounded',
        md: 'rounded-md',
        lg: 'rounded-lg',
        full: 'rounded-full'
    }

    const shadows = {
        none: 'shadow-none',
        sm: 'shadow-sm',
        md: 'shadow',
        lg: 'shadow-lg'
    }

    const filledVariants = {
        primary: 'border-transparent text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
        secondary: 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500',
        danger: 'border-transparent text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
        success: 'border-transparent text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
        warning: 'border-transparent text-white bg-amber-500 hover:bg-amber-600 focus:ring-amber-500',
        info: 'border-transparent text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-cyan-500',
        light: 'border-gray-200 text-gray-700 bg-gray-50 hover:bg-gray-100 focus:ring-gray-500',
        dark: 'border-transparent text-white bg-gray-800 hover:bg-gray-900 focus:ring-gray-700',
        gradient: 'border-transparent text-white bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:ring-indigo-500'
    }

    const outlinedVariants = {
        primary: 'border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500 bg-transparent',
        secondary: 'border-gray-400 text-gray-700 hover:bg-gray-50 focus:ring-gray-500 bg-transparent',
        danger: 'border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500 bg-transparent',
        success: 'border-emerald-600 text-emerald-600 hover:bg-emerald-50 focus:ring-emerald-500 bg-transparent',
        warning: 'border-amber-500 text-amber-600 hover:bg-amber-50 focus:ring-amber-500 bg-transparent',
        info: 'border-cyan-600 text-cyan-600 hover:bg-cyan-50 focus:ring-cyan-500 bg-transparent',
        light: 'border-gray-300 text-gray-600 hover:bg-gray-50 focus:ring-gray-400 bg-transparent',
        dark: 'border-gray-800 text-gray-800 hover:bg-gray-100 focus:ring-gray-700 bg-transparent'
    }

    const variantClasses = props.outlined ? outlinedVariants[props.variant] : filledVariants[props.variant]

    return [
        'inline-flex items-center justify-center gap-2 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 border',
        sizes[props.size],
        borderRadius[props.rounded],
        shadows[props.shadow],
        variantClasses,
        props.disabled ? 'opacity-50 cursor-not-allowed' : ''
    ].filter(Boolean).join(' ')
})
</script>