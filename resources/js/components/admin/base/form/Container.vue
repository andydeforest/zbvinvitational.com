<template>
  <AdminBaseSection :title="title" class="admin-base-form-container">
    <div class="admin-base-form-container__content">
      <slot />
    </div>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  defineProps<{ title: string }>();
</script>

<style scoped lang="scss">
  .admin-base-form-container {
    &__content {
      @include mixins.desktop {
        max-width: 600px;
      }
    }
  }
</style>
