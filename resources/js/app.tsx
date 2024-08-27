import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ThemeProvider } from "@/Components/theme-provider"
import MainLayout from './Layouts/MainLayout';
import { ReactNode } from 'react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')).then((page: any) => {
        page.default.layout = page.default.layout || ((page: ReactNode) => <MainLayout children={page} />)
        return page;
    }),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <ThemeProvider defaultTheme="light" storageKey="ui-theme">
                <App {...props} />
            </ThemeProvider>);
    },
    progress: {
        color: '#4B5563',
    },
});
