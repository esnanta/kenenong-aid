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
      // Ignore Yii2
      ignored: ['**/vendor/**', '**/runtime/**', '**/views/**', '**/controllers/**'],
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
            if (id.includes('lucide-react'))
              return 'vendor-icons'
            if (id.includes('axios') || id.includes('sonner'))
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
