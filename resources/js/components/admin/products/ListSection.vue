<template>
  <AdminBaseSection class="admin-products-list-section" title="Products">
    <div class="mb-4">
      <Link href="/admin/products/create" class="button is-primary">Create New Product</Link>
    </div>

    <table class="table is-fullwidth is-striped admin-products-list-section__table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Price</th>
          <th>Active</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="products.length">
          <tr v-for="product in products" :key="product.id">
            <td>{{ product.name }}</td>
            <td class="admin-products-list-section__table--type">
              {{ product.type }}
            </td>
            <td>${{ product.price_dollars }}</td>
            <td>
              <span :class="['tag', product.is_active ? 'is-success' : 'is-warning']">
                {{ product.is_active ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="admin-products-list-section__table--actions">
              <a :href="`/admin/products/${product.id}/edit`" class="button is-small is-link">Edit</a>
              <AdminBaseConfirmDelete
                class="is-small"
                resource="products"
                :id="product.id"
                :item-name="product.name"
                :modal-title="`Delete ${product.name}?`"
              />
            </td>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="5">No products to display.</td>
          </tr>
        </template>
      </tbody>
    </table>
  </AdminBaseSection>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';

  const props = defineProps({
    products: Array
  });
</script>

<style lang="scss">
  .admin-products-list-section {
    &__table {
      &--actions {
        display: flex;
        gap: 0.25rem;
      }

      &--type {
        &::first-letter {
          text-transform: uppercase;
        }
      }
    }
  }
</style>
