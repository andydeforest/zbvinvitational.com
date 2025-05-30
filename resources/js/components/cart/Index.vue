<template>
  <div>
    <div v-if="!cart.isEmpty" @click="open = !open" class="cart-widget" :class="{ wiggle: animate }">
      <ShoppingCart :size="16" />
      {{ `${cart.totalItems} item${cart.totalItems > 1 ? 's' : ''}` }} — ${{ (cart.totalPrice / 100).toFixed(2) }}
    </div>
    <transition name="slide">
      <div v-if="open" class="cart-drawer">
        <header class="cart-drawer__header">
          <h3>Your Cart</h3>
          <button class="button is-small cart-drawer__close" @click="open = false"><X :size="16" color="red" /></button>
        </header>
        <div class="cart-drawer__items">
          <p v-if="!cart.items.length" class="is-size-5 has-text-grey-light">
            Your cart is empty. Please visit our
            <Link href="/shop">shop</Link>
            to add some items.
          </p>
          <div v-for="item in cart.items" :key="item.product.id" class="cart-drawer__items--item">
            <div class="info">
              <strong>{{ item.product.display_name }}</strong>
              <br />
              ${{ Number.parseInt(item.product.price_dollars).toFixed(2) }} × {{ item.quantity }}
            </div>
            <div class="cart-drawer__actions buttons are-small">
              <button class="button" @click="cart.updateQuantity(item, item.quantity - 1)">
                <Minus :size="16" />
              </button>
              <button class="button" @click="cart.updateQuantity(item, item.quantity + 1)">
                <Plus :size="16" />
              </button>
              <button class="button" @click="cart.removeItem(item)">
                <Trash :size="16" />
              </button>
            </div>
          </div>
        </div>

        <footer class="cart-drawer__footer">
          <p class="is-size-5">
            <strong>Total:</strong>
            ${{ (cart.totalPrice / 100).toFixed(2) }}
          </p>
          <Link class="button is-primary is-inverted is-fullwidth" href="/shop/checkout">Go to Checkout</Link>
        </footer>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { Link } from '@inertiajs/vue3';
  import { useCartStore } from '@/stores/cart';
  import { Minus, Plus, Trash, ShoppingCart, X } from 'lucide-vue-next';

  const cart = useCartStore();
  const open = ref(false);
  const animate = ref(false);

  watch(
    () => cart.totalItems,
    (newVal, oldVal) => {
      if (newVal > oldVal) {
        animate.value = false;
        requestAnimationFrame(() => {
          animate.value = true;
        });
      }
    }
  );
</script>

<style scoped lang="scss">
  .cart-widget {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    background-color: #222;
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 9999px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    z-index: 1000;
    font-size: 0.9rem;
    transition: transform 0.2s ease;
    display: flex;
    gap: 0.5rem;
  }
  .cart-widget:hover {
    transform: scale(1.05);
  }

  .cart-drawer {
    position: fixed;
    top: 0;
    z-index: 9999;
    right: 0;
    width: 90vw;
    height: 100%;
    background: white;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;

    @include mixins.tablet {
      width: 400px;
    }

    &__header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 0.5rem;
    }

    &__items {
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      overflow-y: auto;

      .info {
        flex: 1;
      }

      &--item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        justify-content: space-between;
        margin-bottom: 1rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
      }
    }

    &__actions {
      display: flex;
      gap: 0.25rem;
    }

    &__footer {
      border-top: 1px solid #ddd;
      padding-top: 1rem;
    }
  }

  .slide-enter-active,
  .slide-leave-active {
    transition: transform 0.3s ease;
  }
  .slide-enter-from {
    transform: translateX(100%);
  }
  .slide-leave-to {
    transform: translateX(100%);
  }

  @keyframes wiggle {
    0%,
    100% {
      transform: rotate(0deg);
    }
    15% {
      transform: rotate(-5deg);
    }
    30% {
      transform: rotate(5deg);
    }
    45% {
      transform: rotate(-3deg);
    }
    60% {
      transform: rotate(3deg);
    }
    75% {
      transform: rotate(-2deg);
    }
    90% {
      transform: rotate(2deg);
    }
  }

  .wiggle {
    animation: wiggle 0.4s ease;
  }
</style>
