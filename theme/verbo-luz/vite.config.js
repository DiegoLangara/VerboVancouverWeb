import { defineConfig } from 'vite';

// Compila src/ a dist/ con manifest para que functions.php cargue los archivos con hash.
export default defineConfig({
  base: './',
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: { input: { main: 'src/js/main.js' } },
  },
  server: { port: 5173, strictPort: true, cors: true, origin: 'http://localhost:5173' },
});
