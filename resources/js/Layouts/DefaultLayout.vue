<template>
  <div>
    <NavMenu />
    <main>
      <BasePageTransition>
        <slot />
      </BasePageTransition>
    </main>
    <BaseFooter />
    <BaseHideOn :pages="['/shop/checkout']">
      <CartIndex />
    </BaseHideOn>
  </div>
</template>
