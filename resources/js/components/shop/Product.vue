<template>
  <div class="shop-product">
    <div class="shop-product__image">
      <img v-if="item.cover_image_url" :src="item.cover_image_url" :alt="`Cover image for ${item.name}`" />
      <ShoppingBasket v-else class="shop-product__image--placeholder" :size="128" color="var(--zbv-primary)" />
    </div>
    <div class="shop-product__content">
      <h2>{{ item.name }}</h2>
      <p>{{ item.description }}</p>
      <div class="shop-product__actions">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ShoppingBasket } from 'lucide-vue-next';

  defineProps<{
    item: ProductCategory | Product;
  }>();
</script>

<style lang="scss">
  .shop-product {
    --image-size: 250px;

    display: flex;
    flex-direction: column;
    gap: 1rem;

    @include mixins.tablet {
      flex-direction: row;
      gap: 3rem;
    }

    &__image {
      display: flex;
      justify-content: center;

      img {
        object-fit: contain;
        max-height: 600px;
      }

      @include mixins.tablet {
        width: var(--image-size);
        height: var(--image-size);

        img {
          object-fit: contain;
          object-position: top;
          max-height: var(--image-size);
          max-width: var(--image-size);
        }
      }

      &--placeholder {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        border: 2px solid var(--zbv-primary);
      }
    }

    &__content {
      flex: 1;
    }

    &__actions {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 1rem;

      @include mixins.desktop {
        display: flex;
        flex-direction: row;
      }

      button {
        display: flex;
        gap: 0.5rem;
        align-items: center;
      }
    }
  }
</style>
