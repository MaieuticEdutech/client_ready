import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

/**
 * The static build of the public site, with no Laravel behind it.
 *
 *   npm run dev:site     — local preview with hot reload
 *   npm run build:site   — writes a deployable folder to ./dist
 *
 * Vercel runs the build command from vercel.json and serves ./dist.
 */
export default defineConfig({
    root: 'site',
    publicDir: 'public',
    build: {
        outDir: '../dist',
        emptyOutDir: true,
    },
    plugins: [tailwindcss()],
});
