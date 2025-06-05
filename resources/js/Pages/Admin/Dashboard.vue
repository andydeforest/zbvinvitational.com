<template>
  <Head title="Dashboard" />
  <AdminLayout grid-section-layout>
    <AdminDashboardLatestOrdersSection />
    <AdminDashboardContactMessagesSection />
    <AdminDashboardStatisticsSection />
    <AdminDashboardSettingsSection />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
</script>
