<template>
  <div class="nav-mobile-menu">
    <button @click="isOpen = !isOpen" class="nav-mobile-menu__toggle" :class="{ 'is-active': isOpen }">
      <component :is="isOpen ? X : Menu" :size="30" />
    </button>
    <nav :class="{ 'is-active': isOpen }" class="nav-mobile-menu__panel">
      <div class="nav-mobile-menu__container">
        <ul class="nav-mobile-menu__panel--nav">
          <li v-for="(link, x) in links" :key="`nav-item-${x}`">
            <Link :href="link.href" class="nav-item">{{ link.label }}</Link>
          </li>
        </ul>
        <BaseSocialIconsList class="nav-mobile-menu__panel--social" :icon-size="32" />
      </div>
    </nav>
  </div>
</template>

<script setup lang="ts">
  import { ref, watch } from 'vue';
  import { Menu, X } from 'lucide-vue-next';
  import { Link } from '@inertiajs/vue3';

  const isOpen = ref(false);
  const navStore = useNavStore();
  const links = navStore.links;

  // Whenever isOpen toggles, add/remove a class on <body> so the page itself cannot scroll.
  watch(isOpen, (val) => {
    document.body.classList.toggle('no-scroll', val);
  });

  // (Optional) Close the menu whenever the viewport resizes
  function handleResize() {
    isOpen.value = false;
  }
  onMounted(() => window.addEventListener('resize', handleResize));
  onUnmounted(() => window.removeEventListener('resize', handleResize));
</script>

<style lang="scss">
  .no-scroll {
    overflow-y: hidden !important;
  }

  .nav-mobile-menu {
    display: flex;

    &__toggle {
      display: flex;
      color: var(--zbv-primary);
    }

    &__container {
      display: flex;
      flex-direction: column;
      height: calc(100dvh - var(--header-height) - 4rem); // 4rem to account for 2x top/bottom padding
      justify-content: space-between;
    }

    &__panel {
      z-index: 9999;
      position: fixed;
      top: var(--header-height);
      bottom: -10vh;
      width: 75vw;
      background-color: var(--zbv-primary);
      right: -75vw;
      transition: all linear 0.2s;
      padding: 2rem var(--base-container-padding);

      &.is-active {
        right: 0;
      }

      &--nav {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1.5rem;

        li {
          a {
            color: #fff;
            font-size: 32px;
          }
        }
      }

      &--social {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 2rem;

        a {
          color: #fff;
        }
      }
    }
  }
</style>
