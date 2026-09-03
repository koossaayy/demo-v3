<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Prefetching') },
    { title: t('Link Prefetch') },
];
</script>

<template>
    <Head :title="$t('Link Prefetch')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Link Prefetch')"
                docs="data-props/prefetching#link-prefetching"
                controller="app/Http/Controllers/Feature/PrefetchingController.php#L10"
            > {{ $t('Automatic prefetching on mount, hover, and click. Open DevTools Network tab to observe prefetch requests.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Hover (default) -->
                <FeatureCard :title="$t('Hover Prefetch (default)')">
                    <template #description>
                        <code class="text-xs">{{ $t('prefetch') }}</code>{{ $t('. Prefetches after 75ms hover delay.') }} </template>
                    <div class="space-y-3">
                        <Link
                            href="/contacts"
                            prefetch
                            class="inline-flex items-center rounded-md border border-black/10 bg-background px-4 py-2 text-sm font-medium hover:bg-accent dark:border-white/10"
                        > {{ $t('Contacts') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('Hover over the link and watch the Network tab. Data loads before you click.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Click -->
                <FeatureCard :title="$t('Click Prefetch')">
                    <template #description>
                        <code class="text-xs">{{ $t('prefetch="click"') }}</code>{{ $t('. Prefetches on mousedown, loads by the time click fires.') }} </template>
                    <div class="space-y-3">
                        <Link
                            href="/organizations"
                            prefetch="click"
                            class="inline-flex items-center rounded-md border border-black/10 bg-background px-4 py-2 text-sm font-medium hover:bg-accent dark:border-white/10"
                        > {{ $t('Organizations') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('Data starts loading on mousedown. The page loads almost instantly.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Mount -->
                <FeatureCard :title="$t('Mount Prefetch')">
                    <template #description>
                        <code class="text-xs">{{ $t('prefetch="mount"') }}</code>{{ $t('. Prefetches immediately when the component mounts.') }} </template>
                    <div class="space-y-3">
                        <Link
                            href="/contacts/create"
                            prefetch="mount"
                            class="inline-flex items-center rounded-md border border-black/10 bg-background px-4 py-2 text-sm font-medium hover:bg-accent dark:border-white/10"
                        > {{ $t('Create Contact') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('This link\'s page data was prefetched as soon as this page loaded.') }} </p>
                    </div>
                </FeatureCard>

                <!-- cacheFor -->
                <FeatureCard :title="$t('Cache Duration')">
                    <template #description>
                        <code class="text-xs">{{ $t('cacheFor="10s"') }}</code>{{ $t('. Controls how long prefetched data stays cached.') }} </template>
                    <div class="space-y-3">
                        <Link
                            href="/dashboard"
                            prefetch
                            cacheFor="10s"
                            class="inline-flex items-center rounded-md border border-black/10 bg-background px-4 py-2 text-sm font-medium hover:bg-accent dark:border-white/10"
                        > {{ $t('Dashboard (10s cache)') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('Hover to prefetch, then hover again after 10 seconds to see a new request in the Network tab. Default cache duration is 30 seconds.') }} </p>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
