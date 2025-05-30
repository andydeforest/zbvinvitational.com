<template>
  <AdminBaseSection class="admin-products-list-section" title="Product Categories">
    <div class="mb-4">
      <Link href="/admin/categories/create" class="button is-primary">Create New Product Category</Link>
    </div>

    <table class="table is-fullwidth is-striped admin-categories-list-section__table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="categories.length">
          <tr v-for="category in categories" :key="category.id">
            <td class="admin-categories-list-section__table--title">
              <span>{{ category.name }}</span>
            </td>
            <td class="admin-categories-list-section__table--actions">
              <a :href="`/admin/categories/${category.id}/edit`" class="button is-small is-link">Edit</a>
              <AdminBaseConfirmDelete
                class="is-small"
                resource="categories"
                :id="category.id"
                :item-name="category.name"
                :modal-title="`Delete ${category.name}?`"
              >
                <template #body>
                  <p>
                    Are you sure you'd like to delete product category
                    <strong>{{ category.name }}</strong>
                    ? This will make all products associated with it un-categorized.
                  </p>
                </template>
              </AdminBaseConfirmDelete>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="2">No categories to display.</td>
          </tr>
        </template>
      </tbody>
    </table>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';

  const props = defineProps<{
    categories: Product[];
  }>();
</script>

<style lang="scss">
  .admin-categories-list-section {
    &__table {
      &--actions {
        display: flex;
        gap: 0.25rem;
      }
    }
  }
</style>
