<template>
  <div v-if="!shouldHide">
    <slot />
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';

  const props = defineProps<{
    pages?: string[]; // full paths ('/shop')
    names?: string[]; // route name ('shop.checkout')
    startsWith?: string[]; // prefix ('/shop', '/shop/checkout')
  }>();

  const page = usePage();

  const currentPath = computed(() => page.url); // '/shop/checkout'
  const currentName = computed(() => page.component); // 'Shop/Checkout'

  const shouldHide = computed(() => {
    return (
      props.pages?.includes(currentPath.value) ||
      props.names?.includes(currentName.value) ||
      props.startsWith?.some((prefix) => currentPath.value.startsWith(prefix))
    );
  });
</script>
