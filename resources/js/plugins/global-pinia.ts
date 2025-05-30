import { App } from 'vue';
import { createPinia } from 'pinia';

export function registerPiniaStores(app: App) {
  const pinia = createPinia();
  app.use(pinia);
}
