<template>
  <Head title="Create Category" />
  <AdminLayout>
    <AdminBaseFormContainer :title="isEdit ? 'Edit Product Category' : 'Create New Product Category'">
      <AdminProductCategoriesForm :form="form" :on-submit="submit" />
    </AdminBaseFormContainer>
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { useResourceForm } from '@/composables/useResourceForm';
  import { Head } from '@inertiajs/vue3';

  defineProps<{ category?: ProductCategory }>();

  const initialValues: ProductCategory = {
    name: '',
    description: '',
    cover_image: ''
  };

  const { form, submit, isEdit } = useResourceForm<ProductCategory>('categories', initialValues);
</script>
