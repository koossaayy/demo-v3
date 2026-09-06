import { createInertiaApp } from '@inertiajs/vue3';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';
import i18n from './i18n-setup';

const appName = import.meta.env.VITE_APP_NAME || 'Inertia Kitchen Sink';

createInertiaApp({
    withApp(app, { page }) {
        i18n.global.locale.value = String(page.props.locale ?? 'en');
        app.use(i18n);
    },
    title: (title) => (title ? `${title} - ${appName}` : appName),
    defaults: {
        visitOptions: (href, options) => ({
            preserveScroll: options?.preserveScroll ?? 'errors',
            ...options,
        }),
    },
});

// This will set light / dark mode on page load...
initializeTheme();
