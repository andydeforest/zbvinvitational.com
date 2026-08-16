import './bootstrap';
import '../scss/app.scss';
import 'swiper/css';
import 'ag-grid-community/styles/ag-theme-alpine.css';
import 'vue-toast-notification/dist/theme-sugar.css';

import { createApp, h, ref } from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';

import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
ModuleRegistry.registerModules([AllCommunityModule]);

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue, route } from '../../vendor/tightenco/ziggy';
import { registerGlobalComponents } from './plugins/auto-register-components';
import { registerPiniaStores } from './plugins/global-pinia';
import { createHead } from '@vueuse/head';
import { register } from 'swiper/element/bundle';

register();

const appName = import.meta.env.VITE_APP_NAME || 'The Zeke Bondy-Villa Invitational Golf Tournament';

createInertiaApp({
  title: (title) => (title ? `${title} | ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')).then(
      (module: any) => module.default
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({
      setup() {
        const isPageTransitioning = ref(false);
        let transitionTimer: ReturnType<typeof window.setTimeout>;

        router.on('start', () => {
          window.clearTimeout(transitionTimer);
          isPageTransitioning.value = true;
        });

        router.on('finish', () => {
          transitionTimer = window.setTimeout(() => {
            isPageTransitioning.value = false;
          }, 120);
        });

        return () => [
          h(App, props),
          h('div', {
            class: ['page-transition-overlay', { 'is-active': isPageTransitioning.value }],
            'aria-hidden': 'true'
          })
        ];
      }
    })
      .use(plugin)
      .use(ZiggyVue)
      .use(PrimeVue, {
        theme: {
          preset: Aura,
          options: {
            darkModeSelector: false // disable darkmode globally
          }
        },
        ripple: true
      });
    // global registration
    registerPiniaStores(app);
    registerGlobalComponents(app);

    app.config.globalProperties.$route = route;

    const head = createHead();
    app.use(head);

    app.mount(el);

    return app;
  },
  progress: {
    color: '#4B5563'
  }
});
