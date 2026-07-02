import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    proxy: {
      // Leitet alle Requests, die mit /api starten, an Laravel Herd weiter
      '/api': {
        target: 'http://dbapi.test', // <-- Ersetze das mit deiner echten Herd-Domain!
        changeOrigin: true,
        rewrite: (path) => path // Lässt den Pfad /api/stations.php intakt
      }
    }
  }
})
