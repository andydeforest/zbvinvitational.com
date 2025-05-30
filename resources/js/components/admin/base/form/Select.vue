<template>
  <div class="field">
    <label class="label">{{ label }}</label>
    <div class="control">
      <div class="select">
        <select :value="modelValue" @change="onChange" v-bind="attrs">
          <option v-for="opt in options" :key="opt[optionValue]" :value="opt[optionValue]">
            {{ opt[optionLabel] }}
          </option>
        </select>
      </div>
    </div>
    <p class="help is-danger" v-if="error">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
  import { toRefs, useAttrs } from 'vue';

  const props = withDefaults(
    defineProps<{
      label: string;
      modelValue?: number | string | null;
      options: any[];
      optionValue: string;
      optionLabel: string;
      error?: string;
    }>(),
    { modelValue: null }
  );

  const { label, modelValue, options, optionValue, optionLabel, error } = toRefs(props);

  const emit = defineEmits<{
    (e: 'update:modelValue', val: number | string | null): void;
  }>();

  function onChange(e: Event) {
    const raw = (e.target as HTMLSelectElement).value;

    let parsed: number | string | null = raw;

    if (raw === '' || raw === 'null') {
      parsed = null;
    } else if (!isNaN(Number(raw))) {
      parsed = Number(raw);
    }

    emit('update:modelValue', parsed);
  }

  const attrs = useAttrs();
</script>
