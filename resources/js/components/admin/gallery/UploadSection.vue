<template>
  <AdminBaseSection class="admin-gallery-upload-section" title="Upload Gallery Photos">
    <form @submit.prevent="submitUpload">
      <div class="field is-size-4">
        <label class="label">Upload to Gallery</label>
        <div class="control">
          <div class="select">
            <select v-model="form.year">
              <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>
        </div>
      </div>
      <AdminBaseMultifileUpload
        :customUpload="true"
        :multiple="true"
        accept="image/*"
        url="/api/gallery"
        :custom-data="{ year: form.year }"
        @uploaded="onUploaded"
      />
    </form>
    <AdminBaseMediaManager
      class="admin-gallery-upload-section__media"
      :items="selectedGallery"
      :loading="mediaLoading"
      v-model:selected="selectedForDeletion"
      @delete-item="deleteImage"
      @bulk-delete="deleteSelected"
    >
      <template #item="{ item }">
        <img :src="item.media.original_url" :alt="`Donor logo ${item.id}.`" />
      </template>
      <template #empty>
        <p class="has-text-grey-light">No {{ form.year }} images yet.</p>
      </template>
    </AdminBaseMediaManager>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import axios from 'axios';

  const props = defineProps<{
    photos: GalleryItem[];
  }>();

  const photoList = ref<GalleryItem[]>([...props.photos]);
  const selectedForDeletion = ref([]);
  const mediaLoading = ref(false);

  const selectedGallery = computed(() => {
    return photoList.value
      .filter((photo: GalleryItem) => {
        return photo.year === form.year && photo.media.length > 0;
      })
      .map((photo: GalleryItem) => ({
        id: photo.id,
        media: photo.media[0]!
      }));
  });

  async function deleteImage(id: number) {
    mediaLoading.value = true;
    await axios.delete(`/api/gallery/${id}`);
    photoList.value = photoList.value.filter((photo: GalleryItem) => photo.id !== id);
    mediaLoading.value = false;
  }

  async function deleteSelected(ids: number[]) {
    mediaLoading.value = true;
    await axios.post('/api/gallery/bulk-delete', { ids });
    photoList.value = photoList.value.filter((photo: GalleryItem) => !ids.includes(photo.id));
    mediaLoading.value = false;
  }

  const form = useForm({
    year: new Date().getFullYear(),
    files: []
  });

  const onUploaded = (files: GalleryItem[]) => {
    console.log('do i fire?', files);
    mediaLoading.value = true;
    const normalized = files.map((f) => ({
      id: f.id,
      year: f.year,
      url: f.url,
      media: [f.media]
    }));
    photoList.value.push(...normalized);
    mediaLoading.value = false;
  };

  const availableYears = computed(() => {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let y = currentYear; y >= 2018; y--) {
      years.push(y);
    }
    return years;
  });

  function submitUpload() {
    form.post('/admin/gallery', {
      forceFormData: true,
      onSuccess: () => {
        form.reset('files');
      }
    });
  }
</script>

<style lang="scss">
  .admin-gallery-upload-section {
    &__media {
      .admin-base-media-manager__grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));

        img {
          height: 250px;
          object-fit: cover;
        }
      }
    }
  }
</style>
