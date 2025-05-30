<template>
    <Head title="Receipt" />
    <DefaultLayout>
        <BasePageHero title="Order Confirmation" />
        <ShopCheckoutConfirmationSection :order="order" />
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3'

defineProps<{
  order: Order;
}>();
</script>
