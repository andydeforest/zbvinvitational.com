<template>
  <transition name="fade">
    <div
      v-if="visible"
      class="notification admin-base-toast p-2 m-0"
      :class="{
        'is-success': type === 'success',
        'is-danger': type === 'error',
        'is-warning': type === 'warning',
        'is-info': type === 'info'
      }"
    >
      {{ message }}
    </div>
  </transition>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue';

  const props = defineProps<{
    message: string;
    type?: 'success' | 'error' | 'warning' | 'info';
    duration?: number; // in ms
  }>();

  const visible = ref(false);

  onMounted(() => {
    visible.value = true;
    if (props.duration !== 0) {
      setTimeout(() => (visible.value = false), props.duration ?? 3000);
    }
  });
</script>

<style lang="scss">
  .admin-base-toast {
    position: fixed;
    top: calc(
      var(--admin-navbar-top-height) + var(--admin-vertical-padding) + 0.5rem
    );
    right: calc(var(--admin-horizontal-padding) + 0.5rem);
    border-radius: 0;
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>
