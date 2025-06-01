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
          <div v-for="(image, x) in images" :key="`image-thumb-${x}`" class="gallery-section__grid--item">
            <DeferredContent>
              <div v-if="!loadedThumbs.includes(image.thumb)" class="skeleton-wrapper">
                <Skeleton width="100%" height="0" style="padding-bottom: 66.66%" />
              </div>
              <img
                :src="image.thumb"
                loading="lazy"
                @load="onThumbLoad(image.thumb)"
                @click="openLightbox(x)"
                class="gallery-section__grid--image"
                :class="{ loaded: loadedThumbs.includes(image.thumb) }"
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

  interface ImageEntry {
    full: string;
    thumb: string;
    alt?: string;
  }
  interface GalleryPageProps extends PageProps {
    years: string[];
    images: ImageEntry[]; // now an array of objects
    activeYear: string;
  }

  const page = usePage<GalleryPageProps>();

  const selectedYear = ref<string>(page.props.activeYear);
  const years = ref<string[]>([]);
  const images = ref<ImageEntry[]>([]);
  const loadedThumbs = ref<string[]>([]);
  const loading = ref<boolean>(false);

  const displayLightbox = ref(false);
  const activeIndex = ref(0);

  const galleryImages = computed(() =>
    images.value.map((img) => ({
      itemImageSrc: img.full,
      thumbnailImageSrc: img.thumb,
      alt: img.alt ?? `Image from ${selectedYear.value}`
    }))
  );

  watchEffect(() => {
    years.value = page.props.years;
    images.value = page.props.images;
    loadedThumbs.value = [];
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

  function onThumbLoad(thumbUrl: string) {
    if (!loadedThumbs.value.includes(thumbUrl)) {
      loadedThumbs.value.push(thumbUrl);
    }
  }

  function openLightbox(index: number) {
    activeIndex.value = index;
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
