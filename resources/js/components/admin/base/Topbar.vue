<template>
  <div class="admin-dashboard-topbar">
    <p class="m-0">
      Welcome back,
      <strong>{{ user.name }}</strong>
    </p>
  </div>
</template>

<script setup lang="ts">
  import { usePage } from '@inertiajs/vue3';

  const page = usePage<{ auth: { user: { id: number; name: string; email: string } } }>();
  const user = computed(() => page.props.auth.user);
</script>

<style lang="scss">
  .admin-dashboard-topbar {
    display: flex;
    align-items: center;
    background-color: #fff;
    height: var(--admin-navbar-top-height);
    box-shadow: 0 4px 16px 0 rgba(167, 175, 183, 0.33);
    padding: 0 var(--admin-horizontal-padding);
  }
</style>
