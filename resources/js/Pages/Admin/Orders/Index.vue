<template>
  <Head title="Orders" />
  <AdminLayout>
    <AdminOrdersListSection :product-types="page.props.types" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import { usePage } from '@inertiajs/vue3';

  const page = usePage();
</script>
