<template>
  <div class="flex items-center gap-2">
    <span class="text-sm text-gray-600">{{ count }} selected</span>
    <UIButton variant="success" size="sm" @click="$emit('activate')" :disabled="disabled"
      title="Activate selected users">
      <UIIconsCheck class="h-5 w-5" />
    </UIButton>

    <UIButton variant="warning" size="sm" @click="$emit('deactivate')" :disabled="disabled"
      title="Deactivate selected users">
      <UIIconsBan class="h-5 w-5" />
    </UIButton>

    <UIButton variant="danger" size="sm" @click="$emit('delete')" :disabled="disabled" title="Delete selected users">
      <UIIconsTrash class="h-5 w-5" />
    </UIButton>
  </div>
</template>

<script setup lang="ts">
interface Props {
  count: number
  disabled?: boolean
}

withDefaults(defineProps<Props>(), {
  disabled: false
})

defineEmits<{
  activate: []
  deactivate: []
  delete: []
}>()
</script>