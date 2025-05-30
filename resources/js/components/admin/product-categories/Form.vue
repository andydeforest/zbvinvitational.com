<template>
  <form @submit.prevent="onSubmit">
    <AdminBaseFormTextInput label="Name" v-model="form.name" :error="form.errors.name" name="name" required />
    <AdminBaseFormTextarea label="Description" v-model="form.description" :rows="6" :error="form.errors.description" />
    <AdminBaseFormFileInput
      label="Cover Image (optional)"
      v-model="form.cover_image"
      :error="form.errors.cover_image"
      clearable
    />
    <button
      class="button is-primary"
      type="submit"
      :disabled="form.processing"
      :class="{ 'is-loading': form.processing }"
    >
      Save
    </button>
  </form>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';

  const { form, onSubmit } = defineProps<{
    form: ReturnType<typeof useForm>;
    onSubmit: () => void;
  }>();

  const descriptionValue = computed({
    get: () => String(form.description ?? ''),
    set: (val: string) => {
      form.description = val;
    }
  });
</script>
