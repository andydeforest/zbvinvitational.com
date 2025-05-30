<template>
  <BaseSection class="gallery-section">
    <div>
      <div class="gallery-section__year select is-rounded is-large">
        <select v-model="selectedYear" @change="changeYear">
          <option v-for="year in years" :key="year" :value="year">
            {{ year }}
          </option>
        </select>
      </div>
      <div class="gallery-section__grid">
        <div>
          <div v-for="(src, idx) in images" :key="src" class="gallery-section__grid--item">
            <DeferredContent>
              <div v-if="!loadedImages.includes(src)" class="skeleton-wrapper">
                <Skeleton width="100%" height="0" style="padding-bottom: 66.66%" />
              </div>
              <img
                :src="src"
                loading="lazy"
                @load="onImageLoad(src)"
                @click="openLightbox(idx)"
                class="gallery-section__grid--image"
                :class="{ loaded: loadedImages.includes(src) }"
                :alt="`Image from ${selectedYear}`"
              />
            </DeferredContent>
          </div>
        </div>
      </div>
    </div>
    <Galleria
      v-model:visible="displayLightbox"
      v-model:activeIndex="activeIndex"
      :value="galleryImages"
      :circular="true"
      :fullScreen="true"
      :showItemNavigators="true"
      :showThumbnails="false"
      :baseZIndex="9999"
    >
      <template #item="slotProps">
        <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" />
      </template>
    </Galleria>
  </BaseSection>
</template>

<script setup lang="ts">
  import { ref, computed, watchEffect } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import type { PageProps } from '@inertiajs/core';
  import DeferredContent from 'primevue/deferredcontent';
  import Skeleton from 'primevue/skeleton';
  import Galleria from 'primevue/galleria';

  interface GalleryPageProps extends PageProps {
    years: string[];
    images: string[];
    activeYear: string;
  }

  const page = usePage<GalleryPageProps>();
  const selectedYear = ref(page.props.activeYear);
  const years = ref<string[]>([]);
  const images = ref<string[]>([]);
  const loadedImages = ref<string[]>([]);
  const loading = ref(false);

  const displayLightbox = ref(false);

  const activeIndex = ref(0);

  const galleryImages = computed(() =>
    images.value.map((src) => ({
      itemImageSrc: src,
      thumbnailImageSrc: src,
      alt: `Image from ${selectedYear.value}`
    }))
  );

  watchEffect(() => {
    years.value = page.props.years;
    images.value = page.props.images;
    loadedImages.value = [];
    selectedYear.value = page.props.activeYear;
  });

  function changeYear() {
    loading.value = true;
    router.get(
      '/gallery',
      { year: selectedYear.value },
      {
        preserveScroll: true,
        preserveState: false,
        onFinish: () => {
          loading.value = false;
        }
      }
    );
  }

  function onImageLoad(src: string) {
    if (!loadedImages.value.includes(src)) {
      loadedImages.value.push(src);
    }
  }

  function openLightbox(idx: number) {
    activeIndex.value = idx;
    displayLightbox.value = true;
  }
</script>

<style lang="scss">
  .p-galleria-fullscreen .p-galleria-content {
    overflow: hidden !important;
  }

  .p-galleria-fullscreen {
    overflow: hidden !important;
  }

  .gallery-section {
    min-height: 50vh;

    &__year {
      margin-bottom: 1rem;
      @include mixins.mobile {
        width: 100%;
        select {
          width: 100%;
        }
      }
    }

    &__grid {
      > div {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(1, 1fr);
        @include mixins.tablet {
          grid-template-columns: repeat(2, 1fr);
        }
        @include mixins.desktop {
          grid-template-columns: repeat(3, 1fr);
        }
        @include mixins.widescreen {
          grid-template-columns: repeat(4, 1fr);
        }
      }

      &--item {
        position: relative;
        overflow: hidden;
        cursor: pointer;
      }

      .skeleton-wrapper {
        position: absolute;
        inset: 0;
        z-index: 1;
      }

      &--image {
        width: 100%;
        object-fit: cover;
        aspect-ratio: 3 / 2;
        opacity: 0;
        transition: opacity 0.3s ease;
        &.loaded {
          opacity: 1;
        }
      }
    }
  }
</style>
