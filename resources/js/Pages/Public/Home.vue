<template>
    <Head title="Home" />
    <DefaultLayout>
        <HomeHero />
        <HomeIntroSection :random-images="randomImages" />
        <HomeMissionSection />
        <HomeLearnMoreSection />
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3'

defineProps<{
  randomImages: string[];
}>();
</script>
