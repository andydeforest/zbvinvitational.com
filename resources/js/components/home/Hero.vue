<template>
  <BaseSection class="home-hero primary-overlay" no-container>
    <div class="home-hero__body">
      <span class="home-hero__body--eyebrow">Welcome To</span>
      <h1>The Zeke Bondy-Villa Invitational Golf Tournament</h1>
    </div>
  </BaseSection>
</template>

<style lang="scss">
  .home-hero {
    display: flex;
    align-items: center;
    background-image: url('/images/home/hero-mobile.jpg');
    background-size: cover;
    background-position: center;
    height: 100dvh;
    position: relative;
    color: #fff;
    padding-top: var(--header-height);
    padding-left: 1rem;
    padding-right: 1rem;
    position: relative;

    @include mixins.desktop {
      background-image: url('/images/home/hero.jpg');
      background-position-y: top;
      height: calc(50dvh + var(--header-height));
      padding: 1rem;
    }

    &__body {
      z-index: 2;

      h1 {
        color: #fff;
        line-height: 100%;
      }

      &--eyebrow {
        text-transform: uppercase;
        font-size: 1.6rem;
        font-family: var(--heading-font-family);
      }

      @include mixins.desktop {
        margin: 0 auto;
        width: var(--max-width);
      }
    }
  }
</style>
