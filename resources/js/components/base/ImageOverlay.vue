<template>
  <div class="base-image-overlay primary-overlay">
    <img v-bind="$attrs" />
  </div>
</template>

<style lang="scss">
  .base-image-overlay {
    display: flex;
    position: relative;

    img {
      display: block;
      width: 100%;
      height: auto;
      z-index: 0;
    }
  }
</style>
