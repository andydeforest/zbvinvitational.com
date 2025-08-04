<template>
  <div class="admin-layout">
    <AdminBaseSidebar />
    <main>
      <AdminBaseTopbar />
      <div class="admin-content-wrapper" :data-grid-section-layout="gridSectionLayout">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
  import axios from 'axios';
  import { usePage } from '@inertiajs/vue3';
  import { useToastNotification } from '@/composables/useToastNotification';

  withDefaults(
    defineProps<{
      gridSectionLayout?: boolean;
    }>(),
    {
      gridSectionLayout: false
    }
  );

  const page = usePage<{ flash: { success?: string; error?: string } }>();

  // toast handlers
  const toast = useToastNotification();

  watch(
    () => page.props.flash.success,
    (msg) => {
      if (msg) toast.success(msg);
    },
    { immediate: true }
  );

  watch(
    () => page.props.flash.error,
    (msg) => {
      if (msg) toast.error(msg);
    },
    { immediate: true }
  );

  // session keep-alive
  setInterval(
    async () => {
      await axios.get('/api/ping').catch(() => {});
    },
    5 * 60 * 1000
  );
</script>

<style lang="scss">
  .admin-layout {
    main {
      margin-left: var(--admin-side-bar-width);
      background-color: #f3f3f3;
      min-height: 100dvh;

      .admin-content-wrapper {
        padding: var(--admin-vertical-padding) var(--admin-horizontal-padding);

        &[data-grid-section-layout='true'] {
          display: grid;
          grid-template-columns: repeat(1, 1fr);
          gap: var(--admin-vertical-padding) var(--admin-horizontal-padding);

          @include mixins.desktop {
            grid-template-columns: repeat(2, 1fr);
          }

          .section {
            margin-bottom: 0 !important;
          }
        }
      }
    }
  }
</style>
