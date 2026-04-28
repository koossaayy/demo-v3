<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Prefetching') },
    { title: t('Manual Prefetch') },
];
</script>

<template>
    <Head :title="$t('Manual Prefetch')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Manual Prefetch')"
                docs="data-props/prefetching#programmatic-prefetching"
                controller="app/Http/Controllers/Feature/PrefetchingController.php#L20"
            > {{ $t('Programmatic prefetching with') }} <code class="text-xs">{{ $t('router.prefetch()') }}</code>.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- router.prefetch() -->
                <FeatureCard
                    :title="$t('router.prefetch()')"
                    :description="$t('Programmatically prefetch any URL with custom options.')"
                >
                    <div class="space-y-4">
                        <Button
                            variant="outline"
                            @click="router.prefetch('/contacts')"
                        > {{ $t('Prefetch Contacts') }} </Button>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p> {{ $t('Open DevTools Network tab, click the button, then navigate to Contacts in the sidebar. The page will load instantly from the prefetch cache.') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
