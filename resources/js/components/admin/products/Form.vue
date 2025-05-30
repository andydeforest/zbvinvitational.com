<template>
  <form @submit.prevent="onSubmit">
    <AdminBaseFormTextInput label="Name" v-model="form.name" :error="form.errors.name" required />
    <AdminBaseFormTextInput label="Short Name" v-model="form.short_name" :error="form.errors.short_name" required />
    <div class="field">
      <label class="label">Type</label>
      <div class="control">
        <div class="select">
          <select v-model="form.type">
            <option v-for="(type, x) in types" :value="type">
              {{ type.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase()) }}
            </option>
          </select>
        </div>
      </div>
      <p class="help is-danger" v-if="form.errors.type">
        {{ form.errors.type }}
      </p>
    </div>
    <div v-if="form.type === 'donation'" class="field mb-5">
      <label class="checkbox">
        <input type="checkbox" v-model="form.allow_custom_price" />
        Allow Custom Pricing (i.e. custom donation amounts)
      </label>
      <p class="help is-danger" v-if="form.errors.allow_custom_price">
        {{ form.errors.allow_custom_price }}
      </p>
    </div>
    <AdminBaseFormSelect
      label="Product Category (optional)"
      v-model="form.product_category_id"
      :options="categories"
      option-value="id"
      option-label="name"
      :error="form.errors.product_category_id"
    />
    <AdminBaseFormPriceInput label="Price (in dollars)" v-model="form.price" :error="form.errors.price" />
    <div class="field">
      <label class="checkbox">
        <input type="checkbox" v-model="form.is_active" />
        Active
      </label>
      <p class="help is-danger" v-if="form.errors.is_active">
        {{ form.errors.is_active }}
      </p>
    </div>
    <AdminBaseFormTextarea label="Description" v-model="form.description" :error="form.errors.description" />
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
  import { useForm } from '@inertiajs/vue3';

  defineProps<{
    form: ReturnType<typeof useForm>;
    onSubmit: () => void;
    categories: ProductCategory[];
    types: string[];
  }>();
</script>
