<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    ContactRound,
    Building2,
    FormInput,
    LayoutGrid,
    Navigation,
    Database,
    Zap,
    Layers,
    Radio,
    AlertTriangle,
    Wifi,
    Save,
} from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavSection from '@/components/NavSection.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem, type NavSection as NavSectionType } from '@/types';
import { dashboard } from '@/wayfinder/routes';
import AppLogo from './AppLogo.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const crmNavItems: NavItem[] = [
    {
        title: t('Dashboard'),
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: t('Organizations'),
        href: '/organizations',
        icon: Building2,
    },
];

const crmSection: NavSectionType = {
    label: t('CRM demo'),
    groups: [
        {
            title: t('Contacts'),
            icon: ContactRound,
            items: [
                { title: t('All Contacts'), href: '/contacts' },
                { title: t('Create Contact'), href: '/contacts/create' },
            ],
        },
    ],
};

const featuresSection: NavSectionType = {
    label: t('Kitchen Sink'),
    groups: [
        {
            title: t('Forms'),
            icon: FormInput,
            items: [
                { title: 'useForm', href: '/features/forms/use-form' },
                {
                    title: t('Form Component'),
                    href: '/features/forms/form-component',
                },
                { title: t('File Uploads'), href: '/features/forms/file-uploads' },
                { title: t('Validation'), href: '/features/forms/validation' },
                { title: t('Precognition'), href: '/features/forms/precognition' },
                {
                    title: t('Optimistic Updates'),
                    href: '/features/forms/optimistic-updates',
                    badge: 'v3',
                },
                {
                    title: 'useFormContext',
                    href: '/features/forms/use-form-context',
                },
                { title: t('Dotted Keys'), href: '/features/forms/dotted-keys' },
                { title: t('Wayfinder'), href: '/features/forms/wayfinder' },
            ],
        },
        {
            title: t('Navigation'),
            icon: Navigation,
            items: [
                {
                    title: t('Links & Methods'),
                    href: '/features/navigation/links',
                },
                {
                    title: t('Preserve State'),
                    href: '/features/navigation/preserve-state',
                },
                {
                    title: t('Preserve Scroll'),
                    href: '/features/navigation/preserve-scroll',
                },
                {
                    title: t('View Transitions'),
                    href: '/features/navigation/view-transitions',
                },
                {
                    title: t('History Management'),
                    href: '/features/navigation/history-management',
                },
                {
                    title: t('Async Requests'),
                    href: '/features/navigation/async-requests',
                },
                {
                    title: t('Client-Side Visits'),
                    href: '/features/navigation/manual-visits',
                },
                {
                    title: t('Redirects'),
                    href: '/features/navigation/redirects',
                },
                {
                    title: t('Scroll Management'),
                    href: '/features/navigation/scroll-management',
                },
                {
                    title: t('Instant Visits'),
                    href: '/features/navigation/instant-visits',
                    badge: 'v3',
                },
                {
                    title: t('URL Fragments'),
                    href: '/features/navigation/url-fragments',
                    badge: 'v3',
                },
            ],
        },
        {
            title: t('Data Loading'),
            icon: Database,
            items: [
                {
                    title: t('Deferred Props'),
                    href: '/features/data-loading/deferred-props',
                },
                {
                    title: t('Partial Reloads'),
                    href: '/features/data-loading/partial-reloads',
                },
                {
                    title: t('Infinite Scroll'),
                    href: '/features/data-loading/infinite-scroll',
                },
                {
                    title: t('When Visible'),
                    href: '/features/data-loading/when-visible',
                },
                { title: t('Polling'), href: '/features/data-loading/polling' },
                {
                    title: t('Prop Merging'),
                    href: '/features/data-loading/prop-merging',
                },
                {
                    title: t('Once Props'),
                    href: '/features/data-loading/once-props',
                },
                {
                    title: t('Optional Props'),
                    href: '/features/data-loading/optional-props',
                },
            ],
        },
        {
            title: t('Prefetching'),
            icon: Zap,
            items: [
                {
                    title: t('Link Prefetch'),
                    href: '/features/prefetching/link-prefetch',
                },
                {
                    title: t('Stale While Revalidate'),
                    href: '/features/prefetching/stale-while-revalidate',
                },
                {
                    title: t('Manual Prefetch'),
                    href: '/features/prefetching/manual-prefetch',
                },
                {
                    title: t('Cache Management'),
                    href: '/features/prefetching/cache-management',
                },
            ],
        },
        {
            title: t('State Management'),
            icon: Save,
            items: [
                { title: t('Remember'), href: '/features/state/remember' },
                { title: t('Flash Data'), href: '/features/state/flash-data' },
                { title: t('Shared Props'), href: '/features/state/shared-props' },
            ],
        },
        {
            title: t('Layouts & Head'),
            icon: Layers,
            items: [
                {
                    title: t('Persistent Layouts'),
                    href: '/features/layouts/persistent-layouts',
                },
                {
                    title: t('Nested Layouts'),
                    href: '/features/layouts/nested-layouts',
                },
                { title: t('Head Component'), href: '/features/layouts/head' },
                {
                    title: t('Layout Props'),
                    href: '/features/layouts/layout-props',
                    badge: 'v3',
                },
            ],
        },
        {
            title: t('Events & Lifecycle'),
            icon: Radio,
            items: [
                {
                    title: t('Global Events'),
                    href: '/features/events/global-events',
                },
                {
                    title: t('Visit Callbacks'),
                    href: '/features/events/visit-callbacks',
                },
                { title: t('Progress Bar'), href: '/features/events/progress' },
            ],
        },
        {
            title: t('Error Handling'),
            icon: AlertTriangle,
            items: [
                {
                    title: t('HTTP Exceptions'),
                    href: '/features/errors/http-exceptions',
                    badge: 'v3',
                },
                {
                    title: t('Network Errors'),
                    href: '/features/errors/network-errors',
                    badge: 'v3',
                },
            ],
        },
        {
            title: t('HTTP'),
            icon: Wifi,
            items: [
                {
                    title: 'useHttp',
                    href: '/features/http/use-http',
                    badge: 'v3',
                },
            ],
        },
    ],
};

const footerNavItems: NavItem[] = [
    {
        title: t('Documentation'),
        href: 'https://inertiajs.com/docs/v3/getting-started',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavSection :section="crmSection" :items="crmNavItems" />
            <NavSection :section="featuresSection" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
