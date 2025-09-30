<template>
  <BaseLayout>
    <template #header>
      <GuestNavBar @open-sign-in="showSignIn = true" @open-sign-up="showSignUp = true" />
    </template>

    <!-- Main content -->
    <main class="flex-1">
      <slot @open-sign-in="showSignIn = true" @open-sign-up="showSignUp = true"></slot>
    </main>

    <template #footer>
      <GuestFooter />
    </template>

    <!-- Modals -->
    <Teleport to="body">
      <!-- Sign In Modal -->
      <div v-if="showSignIn" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showSignIn = false"></div>
        <div class="relative z-10 w-full max-w-md">
          <AuthSignin @close="showSignIn = false" @openSignUp="showSignIn = false; showSignUp = true" />
        </div>
      </div>
      
      <!-- Sign Up Modal -->
      <div v-if="showSignUp" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showSignUp = false"></div>
        <div class="relative z-10 w-full max-w-md">
          <AuthSignup @close="showSignUp = false" @openSignIn="showSignUp = false; showSignIn = true" />
        </div>
      </div>
    </Teleport>
  </BaseLayout>
</template>

<script setup>
import { ref } from 'vue';
import BaseLayout from "./base.vue";

const showSignIn = ref(false);
const showSignUp = ref(false);
</script>