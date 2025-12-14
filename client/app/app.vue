<template>
  <CommonLoading ref="loader" />
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  <CommonNotification />
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