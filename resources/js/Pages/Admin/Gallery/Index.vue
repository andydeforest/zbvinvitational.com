<template>
  <Head title="Gallery" />
  <AdminLayout>
    <AdminGalleryUploadSection :photos="photos" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';

  interface PageProps {
    photos: GalleryItem[];
  }

  defineProps<{
    photos: PageProps[];
  }>();
</script>
