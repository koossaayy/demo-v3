import { createInertiaApp } from '@inertiajs/vue3';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';
import i18n from './i18n-setup';
import { createApp, h } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Inertia Kitchen Sink';

createInertiaApp({
    setup({ el, App, props, plugin }) {
        i18n.global.locale.value = props.initialPage.props.locale ?? 'en';
        createApp({ render: () => h(App, props) }).use(plugin).use(i18n).mount(el);
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
