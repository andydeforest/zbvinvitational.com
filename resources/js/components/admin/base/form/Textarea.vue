<template>
  <div class="field">
    <label class="label">{{ label }}</label>
    <div class="control">
      <textarea class="textarea" :rows="rows" :value="modelValue" @input="onInput" v-bind="attrs" />
    </div>
    <p class="help is-danger" v-if="error">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
  import { toRefs, useAttrs } from 'vue';

  const props = withDefaults(
    defineProps<{
      label: string;
      modelValue?: string;
      error?: string;
      rows?: number;
    }>(),
    {
      modelValue: '',
      rows: 3
    }
  );

  const { modelValue, label, error, rows } = toRefs(props);
  const emit = defineEmits<{
    (e: 'update:modelValue', val: string): void;
  }>();

  function onInput(e: Event) {
    emit('update:modelValue', (e.target as HTMLTextAreaElement).value);
  }

  const attrs = useAttrs();
</script>
