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
    { title: t('Stale While Revalidate') },
];
</script>

<template>
    <Head :title="$t('Stale While Revalidate')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Stale While Revalidate')"
                docs="data-props/prefetching#stale-while-revalidate"
                controller="app/Http/Controllers/Feature/PrefetchingController.php#L15"
            > {{ $t('SWR caching strategy. Serve stale data instantly while revalidating in the background.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard :title="$t('SWR Link')">
                    <template #description>
                        <i18n-t keypath="{el0}. Prefetched on mount. 10s fresh, then stale for up to 20s." tag="span" scope="global"><template #el0><code class="text-xs">:cacheFor="['10s', '20s']"</code></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <Link
                            href="/contacts"
                            prefetch="mount"
                            :cacheFor="['10s', '20s']"
                            class="inline-flex items-center rounded-md border border-black/10 bg-background px-4 py-2 text-sm font-medium hover:bg-accent dark:border-white/10"
                        > {{ $t('Contacts (10s/20s SWR)') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('Prefetched on mount. Within 10s, clicking navigates instantly from cache. Between 10-20s, stale data is served immediately and the page updates with fresh data. After 20s, the cache is gone.') }} </p>
                    </div>
                </FeatureCard>

                <FeatureCard :title="$t('How SWR Works')">
                    <template #description>
                        <i18n-t keypath="Pass a tuple to {el0}: {el1}." tag="span" scope="global"><template #el0><code class="text-xs">cacheFor</code></template><template #el1><code class="text-xs">[freshPeriod, stalePeriod]</code></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="rounded-md bg-muted p-4 text-sm">
                            <ol class="list-inside list-decimal space-y-2">
                                <li>
                                    <i18n-t keypath="{el0}: cached data is returned immediately, no request made." tag="span" scope="global"><template #el0><strong>Fresh period</strong></template></i18n-t>
                                </li>
                                <li>
                                    <i18n-t keypath="{el0}: stale cached data is served instantly for immediate navigation. The page updates with fresh data from the server response." tag="span" scope="global"><template #el0><strong>Stale period</strong></template></i18n-t>
                                </li>
                                <li>
                                    <i18n-t keypath="{el0}: cache is gone, a full server request happens." tag="span" scope="global"><template #el0><strong>Expired</strong></template></i18n-t>
                                </li>
                            </ol>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
