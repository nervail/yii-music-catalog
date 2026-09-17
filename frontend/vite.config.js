import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

const BACKEND_ORIGIN = 'http://admin.music.local';

export default defineConfig({
  plugins: [react()],
  server: {
    host: 'music.local',
    port: 5173,
    proxy: {
      '/api': {
        target: BACKEND_ORIGIN,
        changeOrigin: true,
      },
    },
  },
});