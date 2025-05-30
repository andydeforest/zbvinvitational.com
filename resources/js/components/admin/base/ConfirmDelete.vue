<template>
  <button :class="['button', triggerClass]" v-bind="$attrs" @click="open">
    <slot>{{ triggerLabel }}</slot>
  </button>
  <div class="modal" :class="{ 'is-active': isActive }">
    <div class="modal-background" @click="close" />
    <div class="modal-card">
      <header class="modal-card-head p-3">
        <p class="modal-card-title m-0">
          {{ modalTitle }}
        </p>
        <button class="delete" aria-label="close" @click="close"></button>
      </header>
      <section class="modal-card-body p-3">
        <slot name="body">
          Are you sure you want to delete
          <strong>{{ itemName }}</strong>
          ?
        </slot>
      </section>
      <footer class="modal-card-foot p-3">
        <div class="buttons">
          <button
            class="button is-danger"
            :class="{ 'is-loading': form.processing }"
            @click="confirm"
            :disabled="form.processing"
          >
            {{ confirmLabel }}
          </button>
          <button class="button" @click="close">
            {{ cancelLabel }}
          </button>
        </div>
      </footer>
    </div>
    <button class="modal-close is-large" aria-label="close" @click="close"></button>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';

  defineOptions({ inheritAttrs: false });

  const props = defineProps({
    resource: { type: String, required: true },
    id: { type: [String, Number], required: true },
    itemName: { type: String, required: true },
    triggerLabel: { type: String, default: 'Delete' },
    triggerClass: { type: String, default: 'is-danger' },
    confirmLabel: { type: String, default: 'Yes, delete' },
    cancelLabel: { type: String, default: 'Cancel' },
    modalTitle: { type: String, default: 'Delete Item?' }
  });

  const emit = defineEmits<{
    (e: 'deleted'): void;
    (e: 'cancel'): void;
  }>();

  const deleteUrl = computed(() => `/admin/${props.resource}/${props.id}`);

  const form = useForm({});

  const isActive = ref(false);
  function open() {
    isActive.value = true;
  }
  function close() {
    isActive.value = false;
    emit('cancel');
  }

  function confirm() {
    form.delete(deleteUrl.value, {
      preserveScroll: true,
      onSuccess: () => {
        isActive.value = false;
        emit('deleted');
      }
    });
  }

  const title = props.modalTitle || `Delete ${props.itemName}?`;
</script>
