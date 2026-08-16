<template>
  <div class="page-transition-frame">
    <slot />
    <div class="page-transition-overlay" :class="{ 'is-active': isPageTransitioning }" aria-hidden="true" />
  </div>
</template>

<script lang="ts">
  import { defineComponent, ref } from 'vue';
  import { router } from '@inertiajs/vue3';

  const isPageTransitioning = ref(false);
  let transitionTimer: ReturnType<typeof window.setTimeout>;
  let isListening = false;

  const registerPageTransitionListeners = () => {
    if (isListening || typeof window === 'undefined') return;

    isListening = true;

    router.on('start', () => {
      window.clearTimeout(transitionTimer);
      isPageTransitioning.value = true;
    });

    router.on('finish', () => {
      transitionTimer = window.setTimeout(() => {
        isPageTransitioning.value = false;
      }, 120);
    });
  };

  export default defineComponent({
    name: 'BasePageTransition',
    setup() {
      registerPageTransitionListeners();

      return {
        isPageTransitioning
      };
    }
  });
</script>
