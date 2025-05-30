<template>
  <div>
    <button @click="isActive = true" class="button is-secondary is-fullwidth">Bulk Import</button>
    <div class="modal" :class="{ 'is-active': isActive }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Bulk Donor Import</p>
          <button class="delete" aria-label="close" @click="close()" />
        </header>
        <section class="modal-card-body">
          <p>
            multiple donors can be imported by pasting
            <a href="https://en.wikipedia.org/wiki/Comma-separated_values" target="_blank">comma-separated values</a>
            into the box below.
          </p>
          <textarea
            v-model="raw"
            class="textarea"
            placeholder="John Doe, Jane Doe, John Smith"
            @input="validate()"
          ></textarea>
          <ul v-if="errors.length" class="mt-2 has-text-danger">
            <li v-for="(err, i) in errors" :key="i">{{ err }}</li>
          </ul>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" :disabled="!!errors.length || !raw.trim()" @click="doImport">Import</button>
          <button class="button" @click="close()">Cancel</button>
        </footer>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  const emit = defineEmits<{
    (e: 'import', names: string[]): void;
  }>();

  const isActive = ref(false);
  const raw = ref('');
  const errors = ref<string[]>([]);

  function close() {
    isActive.value = false;
    raw.value = '';
    errors.value = [];
  }

  function entries(): string[] {
    return raw.value
      .split(',')
      .map((s) => s.trim())
      .filter((s) => s.length > 0);
  }

  function validate() {
    errors.value = [];

    const list = entries();

    if (!list.length) {
      errors.value.push('Please enter at least one name.');
    }

    // check duplicates
    const dups = list.filter((n, i, a) => a.indexOf(n) !== i);
    if (dups.length) {
      errors.value.push(`Duplicate names: ${[...new Set(dups)].join(', ')}.`);
    }
  }

  function doImport() {
    validate();
    if (errors.value.length) return;
    emit('import', entries());
    close();
  }
</script>
