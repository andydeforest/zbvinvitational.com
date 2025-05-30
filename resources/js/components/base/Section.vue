<template>
  <section class="base-section" :class="{ section: !noContainer }">
    <slot />
  </section>
</template>

<script setup lang="ts">
  const props = withDefaults(
    defineProps<{
      noContainer?: boolean;
    }>(),
    {
      noContainer: false
    }
  );
</script>

<style lang="scss">
  .section {
    max-width: var(--max-width);
    margin: 0 auto;
  }

  .base-section:not(:last-of-type) {
    @include mixins.mobile {
      padding-bottom: 0;
    }
  }
</style>
