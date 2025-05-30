<template>
  <Head title="Create Product" />
  <AdminLayout>
    <AdminBaseFormContainer :title="isEdit ? 'Edit Product' : 'Create New Product'">
      <AdminProductsForm :form="form" :on-submit="submit" :categories="categories" :types="types" />
    </AdminBaseFormContainer>
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { useResourceForm } from '@/composables/useResourceForm';
  import { Head } from '@inertiajs/vue3';

  const props = defineProps<{
    product?: Product;
    categories: ProductCategory[];
    types: string[];
  }>();

  const initialValues: Product = {
    name: '',
    short_name: '',
    description: '',
    type: props.types.length ? props.types[0] : '',
    allow_custom_price: false,
    price: 0,
    is_active: true,
    product_category_id: props.categories.length ? props.categories[0].id : '',
    cover_image: '',
    metadata: {
      fields: []
    }
  };

  const { form, submit, isEdit } = useResourceForm<Product>('products', initialValues);
</script>
