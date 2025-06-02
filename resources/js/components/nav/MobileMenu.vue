<template>
  <div class="nav-mobile-menu">
    <button @click="isOpen = !isOpen" class="nav-mobile-menu__toggle" :class="{ 'is-active': isOpen }">
      <component :is="isOpen ? X : Menu" :size="30" />
    </button>
    <nav :class="{ 'is-active': isOpen }" class="nav-mobile-menu__panel">
      <ul class="nav-mobile-menu__panel--nav">
        <li v-for="(link, x) in links" :key="`nav-item-${x}`">
          <Link :href="link.href" class="nav-item">{{ link.label }}</Link>
        </li>
      </ul>
      <BaseSocialIconsList class="nav-mobile-menu__panel--social" :icon-size="32" />
    </nav>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import { Menu, X } from 'lucide-vue-next';
  import { Link } from '@inertiajs/vue3';

  const navStore = useNavStore();
  const links = navStore.links;
  const isOpen = ref(false);

  function handleResize() {
    isOpen.value = false;
  }

  onMounted(() => {
    window.addEventListener('resize', handleResize);
  });

  onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
  });
</script>

<style lang="scss">
  .nav-mobile-menu {
    display: flex;
    z-index: 9999;

    &__toggle {
      display: flex;
      color: var(--zbv-primary);
    }

    &__panel {
      display: flex;
      flex-direction: column;
      position: fixed;
      top: var(--header-height);
      bottom: 0;
      width: 75vw;
      background-color: var(--zbv-primary);
      right: -75vw;
      transition: all linear 0.2s;
      justify-content: space-between;
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
