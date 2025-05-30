<template>
  <section class="section admin-base-section">
    <div class="container">
      <h1 v-if="title" class="title is-3">{{ title }}</h1>
      <slot />
    </div>
  </section>
</template>

<script setup lang="ts">
  defineProps<{
    title?: string;
  }>();
</script>

<style lang="scss">
  .admin-base-section {
    background-color: #fff;
    border-radius: 6px;
    width: 100%;
    max-width: 100% !important;

    .container {
      max-width: 100% !important;
    }

    &:not(:last-of-type) {
      margin-bottom: 1rem;
    }
  }
</style>
