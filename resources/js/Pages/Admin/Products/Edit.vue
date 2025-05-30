<template>
  <Head title="Edit Product" />
  <AdminLayout>
    <AdminBaseFormContainer :title="isEdit ? 'Edit Product' : 'Create New Product'">
      <AdminProductsForm :form="form" :categories="categories" :types="types" :on-submit="submit" />
    </AdminBaseFormContainer>
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { useResourceForm } from '@/composables/useResourceForm';
  import { Head } from '@inertiajs/vue3';

  const props = defineProps<{
    product: Product;
    categories: ProductCategory[];
    types: string[];
  }>();

  const initialValues: Product = {
    ...props.product,
    cover_image: props.product.cover_image_url ?? null
  };

  const { form, submit, isEdit } = useResourceForm<Product>('products', initialValues);
</script>
