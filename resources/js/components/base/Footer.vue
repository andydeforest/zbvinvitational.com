<template>
  <footer class="base-footer">
    <BaseSection class="base-footer__section">
      <div class="base-footer__logos">
        <a href="https://www.guidestar.org/profile/83-2803947" target="_blank">
          <img :src="'/images/guidestar.jpg'" alt="Gold Transparency award from Guidestar." />
        </a>
        <Link href="#">
          <img :src="'/images/logo-small-light.png'" alt="ZBV logo." />
        </Link>
      </div>
      <div>
        Non-profit Tax ID #
        <strong>83-2803947</strong>
      </div>
      <div class="base-footer__links">
        <span v-for="(link, x) in links" :key="`footer-link-${x}`">
          <Link :href="link.href">{{ link.label }}</Link>
        </span>
      </div>
      <div class="base-footer__socials">
        <BaseSocialIconsList :icon-size="28" />
      </div>
    </BaseSection>
  </footer>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';

  const navStore = useNavStore();
  const links = navStore.links;
</script>

<style lang="scss">
  .base-footer {
    background-color: var(--zbv-primary);
    color: #fff;

    &__section {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 2rem;
    }

    &__logos {
      display: flex;
      flex-direction: column-reverse;
      gap: 2rem;
      align-items: center;

      @include mixins.desktop {
        flex-direction: row;
      }

      img {
        max-height: 100px;
      }
    }

    &__links {
      display: flex;
      flex-direction: row;

      span:not(:last-of-type)::after {
        margin: 0 0.25rem;
        content: '|';

        @include mixins.desktop {
          margin: 0 1rem;
        }
      }

      a {
        color: #fff;
        text-decoration: underline;
      }
    }

    &__socials {
      ul {
        display: flex;
        flex-direction: row;
        gap: 2rem;

        li {
          a {
            color: #fff;
          }
        }
      }
    }

    strong {
      color: #fff;
    }
  }
</style>
