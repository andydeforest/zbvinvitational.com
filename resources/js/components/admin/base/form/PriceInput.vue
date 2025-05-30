<template>
  <div class="field">
    <label class="label">{{ label }}</label>
    <div class="control">
      <input
        class="input"
        type="text"
        inputmode="decimal"
        v-model="priceInput"
        @blur="onBlur"
        @keydown.enter.prevent="onBlur"
        :placeholder="placeholder"
        v-bind="attrs"
      />
    </div>
    <p class="help is-danger" v-if="error">
      {{ error }}
    </p>
  </div>
</template>

<script setup lang="ts">
  import { ref, watch, onMounted, toRefs, useAttrs } from 'vue';

  const props = withDefaults(
    defineProps<{
      label: string;
      modelValue?: number;
      error?: string;
      placeholder?: string;
    }>(),
    {
      modelValue: 0,
      placeholder: '0.00'
    }
  );

  const { label, modelValue, error, placeholder } = toRefs(props);
  const emit = defineEmits<{ (e: 'update:modelValue', val: number): void }>();
  const attrs = useAttrs();

  const priceInput = ref<string>('');

  onMounted(() => {
    formatInput(modelValue.value);
  });

  watch(modelValue, (newCents) => {
    formatInput(newCents);
  });

  function formatInput(cents = 0) {
    priceInput.value = (cents / 100).toFixed(2);
  }

  function onBlur() {
    // strip non-numeric except dot
    const cleaned = priceInput.value.replace(/[^0-9.]/g, '');
    const num = parseFloat(cleaned);
    const cents = isNaN(num) ? 0 : Math.round(num * 100);
    emit('update:modelValue', cents);
    formatInput(cents);
  }
</script>
