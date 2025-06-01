<template>
  <AdminBaseSection title="Donor Logos" class="admin-donors-logo-section">
    <AdminBaseMediaManager
      :items="logosList"
      :loading="logosLoading"
      v-model:selected="selectedForDeletion"
      @delete-item="deleteLogo"
      @bulk-delete="deleteSelected"
    >
      <template #item="{ item }">
        <img :src="item.media.original_url" :alt="`Donor logo ${item.id}.`" />
      </template>
      <template #empty>
        <p class="has-text-grey-light">No logos yet.</p>
      </template>
    </AdminBaseMediaManager>
    <AdminBaseMultifileUpload
      :customUpload="true"
      :multiple="true"
      accept="image/*"
      url="/api/donor-logos"
      @uploaded="onUploaded"
    />
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import axios from 'axios';

  const props = defineProps<{
    logos: DonorLogo[];
  }>();

  const logosList = ref<DonorLogo[]>([...props.logos]);
  const selectedForDeletion = ref<number[]>([]);
  const logosLoading = ref(false);

  const onUploaded = (files: DonorLogo[]) => {
    logosList.value.unshift(...files);
  };

  async function deleteLogo(id: number) {
    logosLoading.value = true;
    await axios.delete(`/api/donor-logos/${id}`);
    logosList.value = logosList.value.filter((l) => l.id !== id);
    logosLoading.value = false;
  }

  // bulk delete handler
  async function deleteSelected(ids: number[]) {
    logosLoading.value = true;
    await axios.post('/api/donor-logos/bulk-delete', { ids });
    logosList.value = logosList.value.filter((l) => !ids.includes(l.id));
    selectedForDeletion.value = [];
    logosLoading.value = false;
  }
</script>

<style lang="scss">
  .admin-donors-logo-section {
    .container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
  }
</style>
