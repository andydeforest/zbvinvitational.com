<template>
  <header class="nav-header">
    <div class="nav-header__top">
      <div class="nav-header__container">
        <div>
          <Calendar />
          <span>
            Our next tournmant is
            <strong>{{ formattedEventDate ?? 'TBD' }}</strong>
          </span>
        </div>
        <div class="nav-header__social">
          <span>Follow us on</span>
          <BaseSocialIconsList />
        </div>
      </div>
    </div>
    <nav class="nav-header__menu nav-header__container">
      <Link href="/">
        <img :src="'/images/logo.png'" alt="Zeke Bondy-Villa Invitational Golf Tournament logo." aria-hidden="true" />
      </Link>
      <div class="nav-header__menu--mobile">
        <NavMobileMenu />
      </div>
      <div class="nav-header__menu--desktop">
        <ul>
          <li v-for="(link, x) in links" :key="`nav-link-${x}`">
            <Link :href="link.href">{{ link.label }}</Link>
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  import { Calendar } from 'lucide-vue-next';
  import { useEventInfo } from '@/composables/useEventInfo';

  const { formattedEventDate } = useEventInfo();

  const navStore = useNavStore();

  const links: NavItem[] = navStore.links;
</script>

<style lang="scss">
  .nav-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9998;
    background-color: #fff;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 18px 50px -10px;

    @include mixins.desktop {
      position: relative;
    }

    &__top {
      display: none;
      height: var(--header-top-height);
      background-color: var(--zbv-primary);
      color: #fff;

      @include mixins.desktop {
        display: flex;
      }

      a {
        color: #fff;
      }

      > div {
        display: flex;
        justify-content: space-between;

        > div {
          display: flex;
          gap: 0.5rem;
          align-items: center;
        }
      }

      strong {
        color: #fff;
      }
    }

    &__social {
      display: flex;

      ul {
        display: flex;
        gap: 1rem;

        li {
          a {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.25);
            height: calc(var(--header-top-height) * 0.66);
            width: calc(var(--header-top-height) * 0.66);
            border-radius: 50%;
            display: flex;
            align-items: center;

            &:hover {
              background-color: #fff;
              color: var(--zbv-primary);
            }
          }
        }
      }
    }

    &__menu {
      display: flex;
      height: var(--header-menu-height);
      justify-content: space-between;

      img {
        max-height: calc(var((--header-menu-height)) * 0.75);
        max-width: 250px;

        @include mixins.desktop {
          max-width: 350px;
        }
      }

      &--mobile {
        display: flex;

        @include mixins.tablet {
          display: none;
        }
      }

      li {
        font-family: var(--heading-font-family);
        font-weight: 500;
        text-transform: uppercase;
      }

      &--desktop {
        display: none;

        ul {
          display: flex;
          flex-direction: row;
          gap: 1.5rem;
          font-size: 18px;
          font-weight: 700;

          li {
            a {
              --highlight-height: 2px;

              position: relative;
              display: inline-block;
              color: var(--bulma-body-color);

              &::after {
                content: '';
                position: absolute;
                bottom: calc(var(--highlight-height) * -1);
                right: 0;
                width: 0;
                height: var(--highlight-height);
                background-color: var(--zbv-secondary);
                transition: width 0.2s ease-out;
              }

              &:hover {
                color: var(--zbv-primary);

                &::after {
                  width: 100%;
                  left: 0;
                  right: auto;
                }
              }
            }
          }
        }

        @include mixins.tablet {
          display: flex;
        }
      }
    }

    &__top,
    &__menu {
      align-items: center;
    }

    &__container {
      padding: 0 var(--base-container-padding);

      @include mixins.tablet {
        margin: 0 auto;
        width: 100%;
        max-width: var(--max-width);
      }
    }
  }
</style>
