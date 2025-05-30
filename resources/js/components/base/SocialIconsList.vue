<template>
  <ul>
    <li v-for="(social, x) in social" :key="`social-${x}`">
      <a :href="social.href" target="_blank" :title="social.title ?? undefined">
        <component :is="social.icon" :size="iconSize ?? 18" />
      </a>
    </li>
  </ul>
</template>

<script setup lang="ts">
  const navStore = useNavStore();
  const social: SocialItem[] = navStore.social;

  const props = defineProps<{
    iconSize?: number;
  }>();
</script>
