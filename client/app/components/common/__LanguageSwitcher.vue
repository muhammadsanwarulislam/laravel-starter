<template>
  <div>
    <!-- Language Switcher -->
    <select v-model="selectedLocale" @change="changeLanguage">
      <option v-for="lang in languages" :key="lang.code" :value="lang.code">
        {{ lang.name }}
      </option>
    </select>

    <!-- Using translations -->
    <h1>{{ t('welcome') }}</h1>
    <button>{{ t('submit') }}</button>
  </div>
</template>

<script setup>
const { locale, languages, t, changeLocale, initialize } = useLocale();
const selectedLocale = ref(locale.value);

// Initialize on component mount
onMounted(async () => {
  await initialize();
});

const changeLanguage = async () => {
  await changeLocale(selectedLocale.value);
};
</script>