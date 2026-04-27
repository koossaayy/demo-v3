<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Prefetching' },
    { title: 'Cache Management' },
];
</script>

<template>
    <Head :title="$t('Cache Management')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Cache Management')"
                docs="data-props/prefetching#cache-invalidation"
                controller="app/Http/Controllers/Feature/PrefetchingController.php#L25"
            > {{ $t('Tag-based cache system with targeted invalidation.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Cache Tags on Links -->
                <FeatureCard :title="$t('Cache Tags')">
                    <template #description> {{ $t('Tag prefetched data with') }} <code class="text-xs">{{ $t('cache-tags') }}</code> {{ $t('for targeted invalidation.') }} </template>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold">{{ $t('Tagged Links') }}</h4>
                            <div class="flex flex-wrap gap-2">
                                <Link
                                    href="/contacts"
                                    prefetch
                                    :cache-tags="$t('crm')"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-black/10 bg-background px-3 py-1.5 text-sm hover:bg-accent dark:border-white/10"
                                > {{ $t('Contacts') }} <Badge variant="outline" class="text-xs"
                                        >{{ $t('crm') }}</Badge
                                    >
                                </Link>
                                <Link
                                    href="/organizations"
                                    prefetch
                                    :cache-tags="['crm', 'orgs']"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-black/10 bg-background px-3 py-1.5 text-sm hover:bg-accent dark:border-white/10"
                                > {{ $t('Organizations') }} <Badge variant="outline" class="text-xs"
                                        >{{ $t('crm, orgs') }}</Badge
                                    >
                                </Link>
                                <Link
                                    href="/dashboard"
                                    prefetch
                                    :cache-tags="$t('dashboard')"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-black/10 bg-background px-3 py-1.5 text-sm hover:bg-accent dark:border-white/10"
                                > {{ $t('Dashboard') }} <Badge variant="outline" class="text-xs"
                                        >{{ $t('dashboard') }}</Badge
                                    >
                                </Link>
                            </div>
                            <p class="text-xs text-muted-foreground"> {{ $t('Hover to prefetch, then use the invalidation buttons to flush by tag.') }} </p>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Invalidation Controls -->
                <FeatureCard
                    :title="$t('Invalidation')"
                    :description="$t('Flush cached data by tag or flush all.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold">{{ $t('Flush by Tag') }}</h4>
                            <div class="flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="router.flushByCacheTags('crm')"
                                > {{ $t('Flush "crm"') }} </Button>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="router.flushByCacheTags('orgs')"
                                > {{ $t('Flush "orgs"') }} </Button>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        router.flushByCacheTags('dashboard')
                                    "
                                > {{ $t('Flush "dashboard"') }} </Button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold">{{ $t('Flush All') }}</h4>
                            <Button
                                variant="destructive"
                                size="sm"
                                @click="router.flushAll()"
                            > {{ $t('Flush All Cache') }} </Button>
                        </div>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p>
                                <strong>{{ $t('Tip:') }}</strong> {{ $t('Forms can auto-invalidate cache on submission with') }} <code>{{ $t(':invalidate-cache-tags="[\'tag\']"') }}</code>.
                            </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
