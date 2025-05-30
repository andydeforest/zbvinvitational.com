<template>
  <Head title="Dashboard" />
  <AdminLayout grid-section-layout>
    <AdminDashboardLatestOrdersSection />
    <AdminDashboardContactMessagesSection />
    <AdminBaseSection title="Statistics">1</AdminBaseSection>
    <AdminDashboardSettingsSection />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
</script>
