import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import AutoImport from 'unplugin-auto-import/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.ts'],
      refresh: true
    }),
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => tag.startsWith('swiper-')
        }
      }
    }),
    AutoImport({
      imports: [
        'vue',
        'pinia',
        {
          '~/js/stores/navigation': ['useNavStore']
        }
      ],
      dts: path.resolve(__dirname, 'resources/js/auto-imports.d.ts')
    })
  ],
  resolve: {
    alias: {
      '~': path.resolve(__dirname, 'resources')
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `
            @use "bulma/sass/utilities/mixins" as mixins;
            @use "~/scss/variables" as *;
        `
      }
    }
  }
});
