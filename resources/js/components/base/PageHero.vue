<template>
  <div class="base-page-hero primary-overlay" :style="backgroundStyle">
    <!-- prioritizes our background image for loading -->
    <img
      :src="backgroundImage"
      alt=""
      aria-hidden="true"
      style="display: none"
      width="1"
      height="1"
      fetchpriority="high"
    />
    <BaseSection class="base-page-hero__section">
      <h1>{{ title }}</h1>
      <BaseBreadcrumbs />
    </BaseSection>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';

  const props = defineProps<{
    backgroundImage?: string;
    title: string;
  }>();

  const defaultImage = '/images/hero-default.jpg';

  const backgroundImage = props.backgroundImage || defaultImage;

  const backgroundStyle = computed(() => ({
    backgroundImage: `url(${backgroundImage})`,
    backgroundSize: 'cover',
    backgroundPosition: 'center'
  }));
</script>

<style lang="scss">
  .base-page-hero {
    background-color: var(--zbv-primary);
    padding-top: calc(var(--header-height) + 2.5rem);
    padding-bottom: 2.5rem;

    @include mixins.desktop {
      padding: 125px 0;
    }

    &__section {
      position: relative;
      z-index: 2;
      text-align: center;

      h1 {
        color: #fff;
      }
    }
  }
</style>
