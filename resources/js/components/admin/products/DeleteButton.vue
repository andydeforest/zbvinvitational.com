<template>
  <button class="button is-danger" v-bind="$attrs" @click="isActive = true">
    Delete
  </button>
  <div class="modal" :class="{ 'is-active': isActive }">
    <div class="modal-background" />
    <div class="modal-card">
      <header class="modal-card-head p-3">
        <p class="modal-card-title m-0">Delete {{ product.name }}?</p>
        <button
          @click="isActive = false"
          class="delete"
          aria-label="close"
        ></button>
      </header>
      <section class="modal-card-body p-3">
        <p class="m-0">
          Are you sure you'd like to delete
          <strong>{{ product.name }}</strong> from the shop?
        </p>
      </section>
      <footer class="modal-card-foot p-3">
        <div class="buttons">
          <button
            @click="confirmDelete"
            :disabled="form.processing"
            :class="{ 'is-loading': form.processing }"
            class="button is-danger"
          >
            Yes, delete product
          </button>
          <button class="button">Cancel</button>
        </div>
      </footer>
    </div>
    <button
      @click="isActive = false"
      class="modal-close is-large"
      aria-label="close"
    ></button>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';

  // prevent attrs from being applied to root node
  defineOptions({ inheritAttrs: false });

  const props = defineProps<{
    product: Product;
  }>();

  const form = useForm<Product>({ ...props.product });

  const isActive = ref(false);

  function confirmDelete() {
    form.delete(`/admin/products/${props.product.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        isActive.value = false;
      }
    });
  }
</script>
