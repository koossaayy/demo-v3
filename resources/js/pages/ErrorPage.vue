<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const { t } = useI18n();

const props = defineProps<{
    status: number;
}>();

const errors: Record<number, { title: string; description: string }> = {
    403: {
        title: t('Forbidden'),
        description: t('You don\'t have permission to access this page.'),
    },
    404: {
        title: t('Not Found'),
        description: t('The page you\'re looking for doesn\'t exist.'),
    },
    419: {
        title: t('Page Expired'),
        description: t('Your session has expired. Please refresh and try again.'),
    },
    429: {
        title: t('Too Many Requests'),
        description:
            t('You\'ve made too many requests. Please wait a moment and try again.'),
    },
    500: {
        title: t('Server Error'),
        description: t('Something went wrong on our end. Please try again later.'),
    },
    503: {
        title: t('Service Unavailable'),
        description:
            t('We\'re currently performing maintenance. Please check back soon.'),
    },
};

const goBack = () => window.history.back();

const error = computed(
    () =>
        errors[props.status] ?? {
            title: t('Error'),
            description: t('An unexpected error occurred.'),
        },
);
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <Head :title="`${status} - ${error.title}`" />

        <div class="flex flex-col items-center gap-6 text-center">
            <Link href="/" class="flex flex-col items-center gap-2 font-medium">
                <div
                    class="mb-1 flex h-9 w-9 items-center justify-center rounded-md"
                >
                    <AppLogoIcon
                        class="size-9 fill-current text-[var(--foreground)] dark:text-white"
                    />
                </div>
            </Link>

            <div class="space-y-2">
                <p class="text-7xl font-bold text-foreground">{{ status }}</p>
                <h1 class="text-xl font-medium text-foreground">
                    {{ error.title }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    {{ error.description }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    @click="goBack"
                    class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow-xs transition-colors hover:bg-primary/90"
                > {{ $t('Go Back') }} </button>
                <Link
                    href="/"
                    class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground shadow-xs transition-colors hover:bg-accent hover:text-accent-foreground"
                > {{ $t('Go Home') }} </Link>
            </div>
        </div>
    </div>
</template>
