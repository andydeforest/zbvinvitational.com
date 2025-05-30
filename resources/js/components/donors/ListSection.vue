<template>
  <BaseSection class="donors-list-section">
    <div>
      <h2>Our Donors</h2>
      <p class="my-2">
        It is not possible to adequately express our appreciation for the wonderful businesses and organizations that
        have generously donated to the ZBV Invitational Golf Tournament. Below are those who have partnered with us to
        try to raise money for The American Cancer Society and other amazing non-profits. On behalf of Lynn, Adam,
        Austin, Alison and all those family and friends that love and miss Zeke, THANK YOU we could not do it without
        your help!!!
      </p>
    </div>
    <div>
      <DonorsLogoCarousel :logos="logos" />
    </div>
    <div class="donors-list-section__static">
      <a href="https://letsroam.com/" target="_blank">
        <img :src="'/images/donors/lets-roam.png'" alt="Let's Roam logo." />
      </a>
    </div>
    <div class="donors-list-section__individuals">
      <h3 class="subtitle" v-for="(donor, x) in donors" :key="`individual-donor-${x}`">
        {{ donor.name }}
      </h3>
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  defineProps<{
    donors: Donor[];
    logos: DonorLogo[];
  }>();
</script>

<style lang="scss">
  .donors-list-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;

    &__static {
      display: flex;
      justify-content: center;

      a {
        display: flex;
      }

      img {
        max-height: 200px;
      }
    }

    &__individuals {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.5rem;

      @include mixins.desktop {
        margin-top: 1rem;
      }

      .subtitle {
        font-size: 16px !important;
        font-weight: 500;
      }

      @include mixins.tablet {
        grid-template-columns: repeat(3, 1fr);
      }

      @include mixins.desktop {
        grid-template-columns: repeat(4, 1fr);
      }

      @include mixins.fullhd {
        grid-template-columns: repeat(5, 1fr);
      }
    }
  }
</style>
