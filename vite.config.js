import { defineConfig } from 'vite';
import { resolve } from 'path';



export default defineConfig({
    root: './',
    build: {
        outDir: 'build',
        rollupOptions: {
            input: {
                'index': resolve(__dirname, 'index.html'),
                'pages/reset-password': resolve(__dirname, 'src/pages/reset-password.html'),
                'pages/dashboard': resolve(__dirname, 'src/pages/dashBoard.html'),

                'pages/projects/projects': resolve(__dirname, 'src/pages/projects/projects.html'),
                'pages/projects/project-creation': resolve(__dirname, 'src/pages/projects/project-creation.html'),
                'pages/projects/project-details': resolve(__dirname, 'src/pages/projects/project-details.html'),

                'pages/tickets/tickets': resolve(__dirname, 'src/pages/tickets/tickets.html'),
                'pages/tickets/ticket-creation': resolve(__dirname, 'src/pages/tickets/ticket-creation.html'),
                'pages/tickets/ticket-details': resolve(__dirname, 'src/pages/tickets/ticket-details.html'),

                'pages/profile': resolve(__dirname, 'src/pages/profile.html'),
            },
            output: {
                entryFileNames: '[name].js',
                assetFileNames: 'assets/css/[name][extname]'
            }
        }
    },
    server: {
        port: 3000,
        open: true
    }
});