<template>
    <Head title="Donors" />
    <DefaultLayout>
        <BasePageHero title="Donors" />
        <DonorsListSection :donors="individuals" :logos="logos.data" />
        <DonorsWhereWeDonateSection />
    </DefaultLayout>
</template>

<script setup lang="ts">
    import DefaultLayout from '@/Layouts/DefaultLayout.vue';
    import { Head } from '@inertiajs/vue3'

    defineProps<{
        individuals: Donor[];
        logos: ResourceCollection<DonorLogo>;
    }>();
</script>
