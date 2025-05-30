<template>
  <BaseSection class="home-learn-more-section">
    <div class="home-learn-more-section__card card-1">
      <h3>About Us</h3>
      <p>
        Learn more about the ZBV Memorial Charities Foundation’s annual volunteer-run golf tournament, celebrating the
        camaraderie and light-hearted spirit Zeke Bondy-Villa cherished.
      </p>
      <Link href="/about" class="button is-light is-outlined">Learn More</Link>
    </div>
    <div class="home-learn-more-section__card card-2">
      <h3>Shop</h3>
      <p>
        Visit our shop to make a donation or register for the tournament. Your support drives vital funding for
        community programs and cancer research, all while celebrating the spirit of fun and purpose he embodied.
      </p>
      <Link href="/shop" class="button is-light is-outlined">Shop Now</Link>
    </div>
    <div class="home-learn-more-section__card card-3">
      <h3>Our Donors</h3>
      <p>
        Check out our donors page, which showcases the organizations and individuals whose generosity drive the
        heartbeat of the ZBV Memorial Charities Foundation.
      </p>
      <Link href="/donors" class="button is-light is-outlined">View Donors</Link>
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
</script>

<style lang="scss">
  .home-learn-more-section {
    display: flex;
    flex-direction: column;
    gap: var(--base-gap);

    @include mixins.desktop {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
    }

    &__card {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 4rem;
      padding: var(--base-gap) var(--base-small-gap);
      border-radius: 10px;
      color: #fff;
      overflow: hidden;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.33);

      h3 {
        color: #fff;
      }

      p {
        margin-bottom: 0;
      }

      .button.is-outlined {
        --bulma-button-border-width: 2px;

        &:hover {
          border: 2px solid rgba(255, 255, 255, 0.5);
        }
      }

      &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0.66;
        z-index: 1;
        filter: brightness(0.6) contrast(1.2);
      }

      &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-size: cover;
        background-position: top;
        background-repeat: no-repeat;
        z-index: 0;
      }

      // Ensure content is above overlays
      > * {
        position: relative;
        z-index: 2;
      }

      &.card-1 {
        &::before {
          background-color: #eb5b00;
        }

        &::after {
          background-image: url('/images/home/card-1.jpg');
        }
      }

      &.card-2 {
        &::before {
          background-color: #180161;
        }

        &::after {
          background-image: url('/images/home/card-2.jpg');
        }
      }

      &.card-3 {
        &::before {
          background-color: #eb3678;
        }

        &::after {
          background-position: bottom;
          background-image: url('/images/home/card-3.jpg');
        }
      }
    }
  }
</style>
