<template>
  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
    <div v-if="showLanguageSwitcher" @mouseleave="handleMouseLeave()" @mouseenter="handleMouseEnter()"
      class="absolute right-0 mt-2 w-120 bg-white dark:bg-gray-900 rounded-xl shadow-xl z-20 border border-gray-200 dark:border-gray-700 overflow-hidden">
      <!-- Header -->
      <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ t('language') }}</h3>
        <button @click="emitClose" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
          <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6 space-y-8 text-sm text-gray-700 dark:text-gray-200">
        <div class="space-y-3">
          <p class="block text-xs text-gray-500 dark:text-gray-400">Select your preferred language</p>
          <ul class="grid grid-cols-2 gap-3">
            <li @click="changeLanguage('en')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-blue-100 dark:bg-blue-800 text-gray-800 dark:text-white">
              <img :src="`https://flagcdn.com/24x18/gb.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>En</span>
            </li>
            <li @click="changeLanguage('bn')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/bd.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>বাংলা</span>
            </li>
            <li @click="changeLanguage('hi')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/in.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>हिंदी</span>
            </li>
            <li @click="changeLanguage('en')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/us.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>English</span>
            </li>
            <li @click="changeLanguage('ur')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/pk.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>اردو</span>
            </li>
            <li @click="changeLanguage('fa')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/ir.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>فارسی</span>
            </li>
            <li @click="changeLanguage('ko')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/kr.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>한국어</span>
            </li>
            <li @click="changeLanguage('zh')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/cn.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>中文</span>
            </li>
            <li @click="changeLanguage('de')" class="cursor-pointer px-4 py-2 rounded-md transition-all flex items-center gap-2 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700">
              <img :src="`https://flagcdn.com/24x18/de.png`" alt="flag" class="w-6 h-4 rounded-sm" />
              <span>Deutsch</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import { useLocale } from '~/composables/useLocale';

const { locale, languages, t, changeLocale, initialize } = useLocale();

const selectedLocale = ref(locale.value);

const props = defineProps({
  showLanguageSwitcher: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['close'])

function emitClose() {
  emit('close')
}

const closeTimer = ref(null)
function handleMouseLeave() {
  if (closeTimer.value) clearTimeout(closeTimer.value)
  closeTimer.value = setTimeout(() => {
    emitClose()
  }, 1000)
}
function handleMouseEnter() {
  if (closeTimer.value) {
    clearTimeout(closeTimer.value)
    closeTimer.value = null
  }
}

onMounted(async () => {
  await initialize();
});

const changeLanguage = (lang) => {
  changeLocale(lang);
};
</script>