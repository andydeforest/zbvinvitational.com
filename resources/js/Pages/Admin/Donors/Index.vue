<template>
  <Head title="Donors" />
  <AdminLayout>
    <AdminDonorsLogoSection :logos="logos.data" />
    <AdminDonorsSection :donors="donors" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';

  defineProps<{
    donors: Array<Donor>;
    logos: ResourceCollection<DonorLogo>;
  }>();
</script>
