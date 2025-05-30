<template>
  <div>
    <NavMenu />
    <main>
      <slot />
    </main>
    <BaseFooter />
    <BaseHideOn :pages="['/shop/checkout']">
      <CartIndex />
    </BaseHideOn>
  </div>
</template>
