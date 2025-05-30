<template>
  <Head title="View Order" />
  <AdminLayout>
    <AdminOrdersShowSection :order="order" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';

  defineProps<{
    order: Order;
  }>();
</script>
