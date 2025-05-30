<template>
  <Link :href="`/admin/orders/${params.value}`">
    {{ params.value }}
  </Link>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';

  defineProps<{
    params: { value: string | number };
  }>();
</script>
