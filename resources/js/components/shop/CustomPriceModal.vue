<!-- resources/js/Components/Shop/CustomPriceModal.vue -->
<template>
  <div class="modal" :class="{ 'is-active': show }">
    <div class="modal-background" @click="$emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Custom Donation</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <label class="label">Enter Amount (USD)</label>
        <div class="control has-icons-left">
          <input class="input" type="number" min="1" step="0.01" placeholder="25.00" v-model="amount" />
          <span class="icon is-small is-left">$</span>
        </div>
        <p v-if="error" class="help is-danger mt-2">{{ error }}</p>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" @click="submit">Add to Cart</button>
        <button class="button" @click="$emit('close')">Cancel</button>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, watch } from 'vue';

  const props = defineProps<{
    show: boolean;
  }>();

  const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submit', amount: number): void;
  }>();

  const amount = ref('');
  const error = ref<string | null>(null);

  watch(
    () => props.show,
    (newVal) => {
      if (!newVal) {
        amount.value = '';
        error.value = null;
      }
    }
  );

  function submit() {
    const value = parseFloat(amount.value);
    if (isNaN(value) || value <= 0) {
      error.value = 'Please enter a valid dollar amount.';
      return;
    }

    emit('submit', value);
  }
</script>
