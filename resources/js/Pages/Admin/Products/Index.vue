<template>
  <Head title="Products" />
  <AdminLayout>
    <AdminProductCategoriesListSection :categories="categories" />
    <AdminProductsListSection :products="productsSortedByType" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';

  const props = defineProps<{
    categories: Array<ProductCategory>;
    products: Array<Product>;
  }>();

  const productsSortedByType = computed(() => {
    return [...props.products].sort((a, b) => {
      return a.type.localeCompare(b.type);
    });
  });
</script>
