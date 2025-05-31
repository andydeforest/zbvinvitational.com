<template>
  <Head title="Server Error" />
  <DefaultLayout>
    <BasePageHero :title="title" background-image="/images/hero-error.jpg" />
    <BaseSection class="error-section">
      <h2 class="is-size-1">Error: {{ code }}</h2>

      <p class="error-message has-text-grey is-size-3">
        {{ description ?? "We couldn't find the page you were looking for." }}
      </p>
      <div class="buttons error-section__actions is-centered">
        <Link href="/" class="button is-link is-medium">
          <span>Go Home</span>
        </Link>
        <a href="javascript:history.back()" class="button is-light is-medium">
          <span>Go Back</span>
        </a>
      </div>
    </BaseSection>
  </DefaultLayout>
</template>

<script setup lang="ts">
  import DefaultLayout from '@/Layouts/DefaultLayout.vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { computed } from 'vue';

  const props = defineProps({ status: Number });

  const code = props.status ?? 500;

  const title = computed(() => {
    return {
      503: '503: Service Unavailable',
      500: '500: Server Error',
      404: '404: Page Not Found',
      403: '403: Forbidden'
    }[code];
  });

  const description = computed(() => {
    return {
      503: 'Sorry, we are doing some maintenance. Please check back soon.',
      500: 'Whoops, something went wrong on our servers.',
      404: 'Sorry, the page you are looking for could not be found.',
      403: 'Sorry, you are forbidden from accessing this page.'
    }[code];
  });
</script>

<style lang="scss">
  .error-section {
    display: flex;
    flex-direction: column;
    text-align: center;

    @include mixins.desktop {
      gap: 3rem;
      min-height: 25vh;
    }

    &__actions {
      display: flex;
      gap: 3rem;
    }
  }
</style>
