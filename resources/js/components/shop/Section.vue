<template>
  <BaseSection class="shop-section">
    <div v-if="!categories.length && !uncategorized.length" style="min-height: 25dvh">
      <h2>Our Shop is Currently Empty!</h2>
      <p class="mt-3 is-size-4">
        Looks like our registration green is still under prep—just like the perfect round! Don’t worry, we’ll be teeing
        up sign-ups for golf, dinner, and sponsorship any minute now. Stay tuned and join us for a day of fun!
      </p>
    </div>
    <ShopProduct v-for="(category, x) in categories" :key="`category-${x}`" :item="category">
      <template #actions>
        <button
          v-for="(option, y) in category.products"
          :key="`product-${x}-item-${y}-action`"
          @click="addToCart(option)"
          class="button is-primary"
        >
          <ShoppingCart />
          {{ option.name }}
        </button>
      </template>
    </ShopProduct>
    <ShopProduct v-for="(item, y) in uncategorized" :key="`uncategorized-product-${y}`" :item="item">
      <template #actions>
        <button @click="addToCart(item)" class="button is-primary">
          <ShoppingCart />
          ${{ usePriceDisplay(item.price_dollars) }}
        </button>
      </template>
    </ShopProduct>
    <ShopCustomPriceModal
      :show="showCustomPriceModal"
      @close="showCustomPriceModal = false"
      @submit="handleCustomPriceSubmit"
    />
  </BaseSection>
</template>

<script setup lang="ts">
  import { ShoppingCart } from 'lucide-vue-next';
  import { usePriceDisplay } from '@/composables/usePriceDisplay';
  import { useCartStore } from '@/stores/cart';

  const showCustomPriceModal = ref(false);
  const selectedProduct = ref<Product | null>(null);

  defineProps<{
    categories: ProductCategory[];
    uncategorized: Product[];
  }>();

  const cart = useCartStore();

  function addToCart(product: Product) {
    if (product.allow_custom_price) {
      selectedProduct.value = product;
      showCustomPriceModal.value = true;
    } else {
      cart.addItem(product);
    }
  }

  function handleCustomPriceSubmit(amount: number) {
    if (selectedProduct.value) {
      const productCopy = { ...selectedProduct.value, price: amount * 100, price_dollars: amount };
      cart.addItem(productCopy);
    }

    showCustomPriceModal.value = false;
  }
</script>

<style lang="scss">
  .shop-section {
    display: flex;
    flex-direction: column;
    gap: 5rem;
  }
</style>
