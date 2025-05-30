<template>
  <nav class="breadcrumb base-breadcrumbs is-medium is-centered">
    <ol v-if="breadcrumbs.length">
      <li v-for="page in breadcrumbs" :class="{ 'is-active': page.current }">
        <Link :href="!page.current ? page.url : '#'" :aria-current="page.current ? 'page' : undefined">
          {{ page.title }}
        </Link>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { usePage, Link } from '@inertiajs/vue3';
  import { PageProps as InertiaPageProps } from '@inertiajs/core';

  interface Breadcrumb {
    title: string;
    label: string;
    url: string;
    current?: boolean;
  }

  interface PageProps extends InertiaPageProps {
    breadcrumbs?: Breadcrumb[];
  }

  const page = usePage<PageProps>();

  const breadcrumbs = computed(() => page.props.breadcrumbs ?? []);
</script>

<style lang="scss">
  .base-breadcrumbs {
    li {
      color: #fff;

      &.is-active {
        a {
          color: #fff;
        }
      }

      a {
        font-family: var(--heading-font-family);
        color: #fff;
      }

      &:last-of-type {
        a {
          padding-right: 0;
        }
      }
    }
  }
</style>
