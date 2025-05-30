<template>
    <Head title="Shop" />
    <DefaultLayout>
        <BasePageHero title="Shop" />
        <ShopSection :categories="categories" :uncategorized="uncategorized" />
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3'

defineProps<{
  categories: ProductCategory[];
  uncategorized: Product[];
}>();
</script>
