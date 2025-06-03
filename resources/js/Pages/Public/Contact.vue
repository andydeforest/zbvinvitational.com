<template>
    <Head title="Contact Us" />
    <DefaultLayout>
        <BasePageHero title="Contact Us" />
        <ContactFormSection />
        <ContactFaqSection />
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3'
</script>
