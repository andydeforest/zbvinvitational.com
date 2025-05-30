<template>
  <div ref="carouselRoot" class="donors-logo-carousel">
    <div v-if="imageUrls.length > 6" class="donors-logo-carousel__dynamic">
      <swiper-container class="donor-logo-carousel" init="false">
        <swiper-slide v-for="(url, i) in imageUrls" :key="`donor-logo-${i}`" class="donors-logo-carousel__slide">
          <img :src="url" :alt="`Donor logo ${i + 1}`" />
        </swiper-slide>
      </swiper-container>
    </div>
    <div v-else class="donors-logo-carousel__static">
      <div v-for="(url, i) in imageUrls" :key="`donor-logo-${i}`">
        <img :src="url" :alt="`Donor logo ${i + 1}`" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  const props = defineProps<{
    logos: Array<DonorLogo | string>;
  }>();

  const imageUrls = computed<string[]>(() =>
    props.logos.map((l) => (typeof l === 'string' ? l : l.media.original_url))
  );

  const carouselRoot = ref<HTMLElement | null>(null);

  onMounted(() => {
    if (!carouselRoot.value) return;

    const swiperEls = carouselRoot.value.querySelectorAll<HTMLElement>('swiper-container');

    swiperEls.forEach((el) => {
      const swiper = el as any;
      Object.assign(swiper, {
        slidesPerView: 1,
        spaceBetween: 30,
        speed: 500,
        loop: true,
        autoplay: { delay: 750, disableOnInteraction: false },
        breakpoints: {
          380: { slidesPerView: 2 },
          640: { slidesPerView: 3 },
          800: { slidesPerView: 4 },
          1300: { slidesPerView: 6 }
        }
      });
      swiper.initialize();
    });
  });
</script>

<style lang="scss">
  .donors-logo-carousel {
    --carousel-height: 200px;

    &__dynamic {
      height: var(--carousel-height);
    }

    &__slide {
      display: flex;
      align-items: center;
      height: var(--carousel-height);
    }

    &__static {
      display: flex;
      flex-direction: column;
      gap: 1rem;

      @include mixins.desktop {
        flex-direction: row;
        gap: 3rem;

        > div {
          display: flex;
        }
      }

      img {
        object-fit: contain;
      }
    }
  }
</style>
