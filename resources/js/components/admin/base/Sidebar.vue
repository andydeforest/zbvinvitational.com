<template>
  <nav class="admin-dashboard-sidebar">
    <div class="admin-dashboard-sidebar__title">
      <Link href="/admin">
        <img :src="`/images/logo-small-light.png`" alt="ZBV Logo." />
      </Link>
    </div>
    <div class="admin-dashboard-sidebar__content">
      <ul>
        <li>
          <Link href="/admin">Dashboard</Link>
        </li>
        <li>
          <Link href="/admin/orders">All Orders</Link>
        </li>
        <li>
          <Link href="/admin/golfers">Golfer Registration</Link>
        </li>
        <li>
          <Link href="/admin/products">Shop Management</Link>
        </li>
        <li>
          <Link href="/admin/donors">Donor Management</Link>
        </li>
        <li>
          <Link href="/admin/gallery">Photo Gallery</Link>
        </li>
        <li>
          <Link href="/admin/contact">Contact Messages</Link>
        </li>
      </ul>
      <div class="admin-dashboard-sidebar__footer">
        <button @click="logout" class="button is-outlined is-white is-fullwidth">Logout</button>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
  import { Link, router } from '@inertiajs/vue3';

  const route = getCurrentInstance()?.appContext.config.globalProperties.$route;

  function logout() {
    router.post(route('logout'));
  }
</script>

<style lang="scss">
  .admin-dashboard-sidebar {
    display: none;
    position: fixed; // fix this so that content is pushed to the side
    top: 0;
    left: 0;
    width: var(--admin-side-bar-width);
    height: 100vh;
    background-color: var(--zbv-primary);

    @include mixins.desktop {
      display: flex;
      flex-direction: column;
    }

    &__title {
      display: flex;
      justify-content: center;
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      height: var(--admin-navbar-top-height);
      font-size: calc(var(--admin-navbar-top-height) * 0.9);
      color: #fff;

      img {
        max-height: calc(var(--admin-navbar-top-height) * 0.75);
        max-width: calc(var(--admin-side-bar-width) * 0.75);
      }
    }

    &__content {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-top: 5rem;
      margin-bottom: 1rem;
      flex-grow: 1;

      ul {
        display: flex;
        flex-direction: column;

        li {
          cursor: pointer;
          transition: all linear 0.2s;

          &:hover {
            padding-left: 0.3rem;
            position: relative;

            &::before {
              content: '';
              z-index: -1;
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: #000;
              opacity: 0.5;
            }

            a {
              font-weight: 700;
            }
          }

          a {
            padding: 0.75rem 1rem;
            display: flex;
            height: 100%;
            width: 100%;
            font-size: 20px;
            color: #fff;
          }
        }
      }
    }

    &__footer {
      padding: 0 1rem;
    }
  }
</style>
