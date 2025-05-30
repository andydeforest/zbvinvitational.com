<template>
  <div class="shop-checkout-order-summary">
    <div class="mb-3" v-for="(item, i) in cart.items" :key="i">
      <div class="is-flex is-justify-content-space-between">
        <div class="mr-1">
          <strong>
            {{ item.product.display_name }}
            <span v-if="item.quantity > 1">&nbsp;x {{ item.quantity }}</span>
          </strong>
        </div>
        <div class="has-text-weight-bold">${{ ((item.product.price * item.quantity) / 100).toFixed(2) }}</div>
      </div>
      <hr class="my-2" />
    </div>
    <div class="is-flex is-justify-content-space-between mb-4">
      <span class="has-text-weight-semibold">Total</span>
      <span class="has-text-weight-bold">${{ cart.totalPriceDollars.toFixed(2) }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useCartStore } from '@/stores/cart';

  const cart = useCartStore();
</script>
