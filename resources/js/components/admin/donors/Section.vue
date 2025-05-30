<template>
  <AdminBaseSection class="admin-donors-section" title="Donor Management">
    <form>
      <div class="admin-donors-section__grid">
        <div v-for="(donor, i) in form.donors" :key="`donor-input-${i}`" class="field is-grouped is-align-items-center">
          <div class="control is-expanded">
            <input v-model="form.donors[i].name" class="input" placeholder="Donor Name" />
          </div>
          <div class="control">
            <button class="button is-danger" type="button" @click="removeDonor(i)">Remove</button>
          </div>
        </div>
      </div>
    </form>
    <div class="field mt-4">
      <button class="button is-dark" type="button" @click="addDonor">Add Donor Field</button>
    </div>
    <div class="admin-donors-section__controls">
      <div>
        <button @click="submit" class="button is-primary is-fullwidth" :class="{ 'is-loading': form.processing }">
          Save Changes
        </button>
        <p v-if="form.isDirty" class="help is-warning my-2">You have unsaved changes.</p>
      </div>
      <AdminDonorsBulkImportModal @import="receiveImport" />
    </div>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3';
  const route = getCurrentInstance()?.appContext.config.globalProperties.$route;

  const props = defineProps<{ donors: Donor[] }>();

  // inertia form holds both upserts (donors) and deletions (deleted)
  const form = useForm<{ donors: Array<{ id?: number; name: string }>; deleted: number[] }>(() => ({
    donors: props.donors.map((d) => ({ id: d.id, name: d.name })),
    deleted: []
  }));

  function addDonor() {
    form.donors.push({ name: '' });
  }

  function removeDonor(i: number) {
    const d = form.donors[i];
    if (d.id) form.deleted.push(d.id);
    form.donors.splice(i, 1);
  }

  function submit() {
    form.donors = form.donors.filter((d) => d.name.trim() !== '');
    form.put(route('admin.donors.update'));
  }

  function receiveImport(names: string[]) {
    names.forEach((name: string) => {
      form.donors.push({ name });
    });
  }
</script>

<style lang="scss">
  .admin-donors-section {
    &__grid {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;

      .field.is-grouped {
        margin-bottom: 0;
      }

      @include mixins.tablet {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
      }

      @include mixins.desktop {
        grid-template-columns: repeat(3, 1fr);
      }

      @include mixins.widescreen {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    &__controls {
      display: flex;
      flex-direction: column;
      gap: 1rem;

      @include mixins.tablet {
        flex-direction: row;
      }
    }
  }
</style>
