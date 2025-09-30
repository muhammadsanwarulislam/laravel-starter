<template>
    <div class="google-border">
        <slot />
    </div>
</template>
<style scoped>
.google-border {
  position: relative;
  border: 2px solid transparent;
  background: 
      linear-gradient(white, white) padding-box,
      linear-gradient(
          var(--angle),
          #EA4335 25%,
          #FBBC05 25% 50%,
          #4285F4 50% 75%,
          #34A853 75%
      ) border-box;
  animation: 4s rotate linear infinite;
}

/* Define the angle property for animation */
@property --angle {
  syntax: "<angle>";
  initial-value: 0deg;
  inherits: false;
}

/* Rotation animation */
@keyframes rotate {
  to {
    --angle: 360deg;
  }
}
</style>