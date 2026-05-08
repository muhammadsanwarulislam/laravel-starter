<template>
  <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ t('profile.avatar') }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ t('profile.photo_upload_info') }}</p>
      </div>

      <div class="flex items-center gap-4">
        <!-- Avatar with hover overlay -->
        <div
          class="relative group flex h-20 w-20 cursor-pointer items-center justify-center overflow-hidden rounded-full bg-indigo-600 text-xl font-semibold text-white"
          @click="photoInput?.click()"
        >
          <img
            v-if="displayAvatarUrl"
            :src="displayAvatarUrl"
            alt="Profile photo"
            class="h-full w-full object-cover"
          />
          <span v-else>{{ initials }}</span>
          <div
            class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 transition-opacity group-hover:opacity-100"
          >
            <UIIconsCamera class="h-6 w-6 text-white" />
          </div>
        </div>

        <div class="space-y-2">
          <input
            ref="photoInput"
            type="file"
            accept="image/png,image/jpeg,image/jpg,image/webp"
            class="hidden"
            @change="handlePhotoSelected"
          />
          <button
            type="button"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            @click="photoInput?.click()"
          >
            {{ t('profile.choose_photo') }}
          </button>
          <button
            v-if="displayAvatarUrl"
            type="button"
            class="inline-flex items-center rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50"
            @click="removePhoto"
          >
            {{ t('profile.remove_photo') }}
          </button>
          <p class="text-xs text-gray-500">PNG, JPG, or WebP up to 5MB.</p>
        </div>
      </div>
    </div>

    <p v-if="photoError" class="mt-4 text-sm text-red-600">{{ photoError }}</p>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { services } from '~/services'
import { useAuth } from '~/composables/auth/useAuth'
import { notification } from '~/utils/notification'

const props = defineProps<{
  user: any
}>()

const emit = defineEmits<{
  (e: 'updated', user: any): void
}>()

const { t } = useLocalization()
const auth = useAuth()
const photoInput = ref<HTMLInputElement | null>(null)
const photoError = ref('')
const uploading = ref(false)
const photoPreviewUrl = ref<string | null>(null)

const initials = computed(() => {
  const name = props.user?.name || 'User'
  return name
    .split(' ')
    .map((part: string) => part[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const displayAvatarUrl = computed(
  () => photoPreviewUrl.value || props.user?.avatar_url || null
)

const revokePreviewUrl = () => {
  if (photoPreviewUrl.value) {
    URL.revokeObjectURL(photoPreviewUrl.value)
    photoPreviewUrl.value = null
  }
}

const handlePhotoSelected = async (event: Event) => {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  photoError.value = ''

  if (!file) return
  if (file.size > 5 * 1024 * 1024) {
    photoError.value = 'Photo must be 5MB or smaller.'
    input.value = ''
    return
  }

  revokePreviewUrl()
  photoPreviewUrl.value = URL.createObjectURL(file)
  uploading.value = true

  try {
    const response = await services.user.uploadFile(
      file,
      'profile_image',
      'user',
      props.user?.id,
      true
    )
    if (response.success && response.data) {
      emit('updated', response.data.user)
      await auth.fetchCurrentUser()
      notification.success(response.message || 'Profile photo updated')
      revokePreviewUrl()
    } else {
      photoError.value = response.errors?.photo?.[0] || response.message || 'Upload failed'
      revokePreviewUrl()
    }
  } finally {
    uploading.value = false
    input.value = ''
  }
}

const removePhoto = async () => {
  // Implement delete avatar endpoint if available
  // For now, just remove preview
  revokePreviewUrl()
  photoError.value = ''
}
</script>