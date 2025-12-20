import { resolve } from 'node:path'
import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

export default defineConfig({
  plugins: [
    react(),
  ],
  root: '.',
  publicDir: false,
  server: {
    port: 5173,
    strictPort: true,
    origin: 'http://localhost:5173',
    hmr: {
      host: 'localhost',
    },
    watch: {
      ignored: ['**/vendor/**', '**/runtime/**', '**/views/**'],
    },
  },
  build: {
    outDir: 'web/dist',
    manifest: true,
    emptyOutDir: true,
    target: 'esnext',
    cssCodeSplit: true,
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/app.jsx'),
      output: {
        manualChunks: (id) => {
          if (id.includes('node_modules')) {
            if (id.includes('react'))
              return 'vendor-react'
            if (id.includes('axios'))
              return 'vendor-utils'
            return 'vendor'
          }
        },
      },
    },
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources/js'),
    },
  },
  optimizeDeps: {
    include: ['react', 'react-dom', '@inertiajs/react', 'axios', 'sonner'],
  },
})
