<template>
  <div class="admin-base-media-manager">
    <slot name="empty" v-if="!items.length">
      <p class="empty-message">No items found.</p>
    </slot>
    <div class="admin-base-media-manager__grid" v-else>
      <div v-if="loading" class="admin-base-media-manager__overlay">
        <BaseSpinner />
      </div>
      <div
        v-for="item in items"
        :key="item.id"
        class="admin-base-media-manager__item"
        :class="{ selected: isSelected(item.id) }"
        @click="toggleSelection(item.id)"
      >
        <slot name="item" :item="item">
          <img :src="item.media.original_url" class="admin-base-media-manager__image" />
        </slot>
        <div class="admin-base-media-manager__controls">
          <button
            @click.stop="deleteItem(item.id)"
            class="button is-small admin-base-media-manager__controls--delete has-background-danger"
          >
            <span class="icon is-small">
              <X />
            </span>
          </button>

          <input type="checkbox" v-model="selected" :value="item.id" class="" />
        </div>
      </div>
    </div>
    <button
      v-if="selected.length"
      type="button"
      class="button is-danger admin-base-media-manager__bulk-delete"
      @click="bulkDelete"
    >
      Delete Selected ({{ selected.length }})
    </button>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { X } from 'lucide-vue-next';

  interface MediaItem {
    id: number;
    media: { original_url: string };
  }

  defineProps<{
    items: MediaItem[];
    loading: boolean;
  }>();

  const emit = defineEmits<{
    (e: 'update:selected', selected: number[]): void;
    (e: 'delete-item', id: number): void;
    (e: 'bulk-delete', ids: number[]): void;
  }>();

  const selected = ref<number[]>([]);

  function isSelected(id: number) {
    return selected.value.includes(id);
  }

  function toggleSelection(id: number) {
    const idx = selected.value.indexOf(id);
    if (idx > -1) selected.value.splice(idx, 1);
    else selected.value.push(id);
    emit('update:selected', [...selected.value]);
  }

  function deleteItem(id: number) {
    if (!confirm('Delete item?')) return;

    // if the single deletion exists in our selected element, update
    const idx = selected.value.indexOf(id);
    if (idx > -1) {
      selected.value.splice(idx, 1);
      emit('update:selected', [...selected.value]);
    }

    emit('delete-item', id);
  }

  function bulkDelete() {
    if (!confirm(`Delete ${selected.value.length} items?`)) return;
    emit('bulk-delete', [...selected.value]);
    selected.value = [];
  }
</script>

<style lang="scss">
  .admin-base-media-manager {
    &__grid {
      position: relative;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1rem;
      min-height: 10rem;
    }

    &__overlay {
      position: absolute;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 3rem 0;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.7);
      z-index: 100;
    }

    &__item {
      border: 1px solid #ccc;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      object-fit: contain;
    }

    &__controls {
      display: flex;
      justify-content: space-between;
      margin: 0.5rem;
    }

    &__bulk-delete {
      margin-top: 1rem;
    }
  }
</style>
