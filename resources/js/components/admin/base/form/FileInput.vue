<template>
  <div class="field admin-base-form-file-input">
    <label class="label">{{ label }}</label>
    <div class="control">
      <input type="file" accept="image/*" @change="onChange" v-bind="attrs" />
    </div>
    <p class="help is-danger" v-if="error">{{ error }}</p>
    <div class="admin-base-form-file-input__preview">
      <div class="admin-base-form-file-input__delete">
        <button
          v-if="clearable && previewUrl"
          class="has-background-danger"
          type="button"
          title="Clear Image"
          @click="onClear"
        >
          <X :size="16" />
        </button>
      </div>
      <figure v-if="previewUrl" class="image is-128x128 mt-2">
        <img :src="previewUrl" />
      </figure>
    </div>
  </div>
</template>

<style lang="scss">
  .admin-base-form-file-input {
    &__delete {
      position: absolute;
      left: 150px;
      top: 0;

      button {
        height: 16px;
        width: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        padding: 2px;
      }
    }

    &__preview {
      position: relative;

      img {
        object-fit: contain;
        height: 128px;
        width: 128px;
      }
    }
  }
</style>

<script setup lang="ts">
  import { X } from 'lucide-vue-next';
  import { ref, watch, toRefs, useAttrs } from 'vue';

  const props = withDefaults(
    defineProps<{
      label: string;
      modelValue?: File | string | null;
      error?: string;
      clearable?: boolean;
    }>(),
    {
      modelValue: null,
      clearable: false
    }
  );

  const { label, modelValue, error } = toRefs(props);
  const emit = defineEmits<{
    (e: 'update:modelValue', file: File | string | null): void;
  }>();
  const attrs = useAttrs();

  // local preview URL
  const previewUrl = ref<string | null>(null);

  // when the prop changes (edit-mode), show existing string URL
  watch(
    modelValue,
    (val) => {
      if (val instanceof File) {
        previewUrl.value = URL.createObjectURL(val);
      } else if (typeof val === 'string') {
        previewUrl.value = val;
      } else {
        previewUrl.value = null;
      }
    },
    { immediate: true }
  );

  // on file select
  function onChange(e: Event) {
    const file = (e.target as HTMLInputElement).files?.[0] ?? null;
    emit('update:modelValue', file);
  }

  function onClear() {
    previewUrl.value = null;
    emit('update:modelValue', null);
  }
</script>
