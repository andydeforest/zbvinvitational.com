<template>
  <Head title="Edit Category" />
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

  const props = defineProps<{ category: ProductCategory }>();

  const initialValues: ProductCategory = {
    ...props.category,
    cover_image: props.category.cover_image_url ?? null
  };

  const { form, submit, isEdit } = useResourceForm<ProductCategory>('categories', initialValues);
</script>
