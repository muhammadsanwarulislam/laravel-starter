<template>
  <commonLoading ref="loader" />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  <commonNotification />
</template>

<script setup>
const loader = ref(null);

onMounted(() => {
  loader.value?.show();
});

onNuxtReady(() => {
  loader.value?.hide();
});
</script>