<template>
  <div class="field">
    <label class="label">{{ props.label }}</label>
    <div class="control">
      <input :type="props.type || 'text'" class="input" :value="modelValue" @input="onInput" v-bind="attrs" />
    </div>
    <p class="help is-danger" v-if="props.error">
      {{ props.error }}
    </p>
  </div>
</template>

<script setup lang="ts">
  import { toRefs, useAttrs } from 'vue';

  const props = withDefaults(
    defineProps<{
      label: string;
      modelValue?: string | number;
      error?: string;
      type?: string;
    }>(),
    {
      modelValue: ''
    }
  );

  const { label, modelValue, error, type } = toRefs(props);
  const emit = defineEmits<{
    (e: 'update:modelValue', val: string): void;
  }>();

  function onInput(e: Event) {
    emit('update:modelValue', (e.target as HTMLInputElement).value);
  }

  const attrs = useAttrs();
</script>
