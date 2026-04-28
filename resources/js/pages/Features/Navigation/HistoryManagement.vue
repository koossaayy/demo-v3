<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    visit: number;
    timestamp: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Navigation') },
    { title: t('History Management') },
];

function visitPush() {
    const next = props.visit + 1;
    router.get(`/features/navigation/history-management?visit=${next}`);
}

function visitReplace() {
    const next = props.visit + 1;
    router.get(
        `/features/navigation/history-management?visit=${next}`,
        {},
        {
            replace: true,
        },
    );
}
</script>

<template>
    <Head :title="$t('History Management')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('History Management')"
                docs="the-basics/manual-visits#browser-history"
                controller="app/Http/Controllers/Feature/NavigationController.php#L45"
            > {{ $t('Control how visits affect the browser history stack.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Current State -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Current State')"
                >
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-muted-foreground"
                                >{{ $t('Visit #') }}</span
                            >
                            <Badge
                                variant="secondary"
                                class="min-w-8 justify-center text-lg tabular-nums"
                                >{{ visit }}</Badge
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-muted-foreground"
                                >{{ $t('URL:') }}</span
                            >
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ $page.url }}</Badge
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-muted-foreground"
                                >{{ $t('Timestamp:') }}</span
                            >
                            <Badge variant="outline" class="text-xs">{{
                                timestamp
                            }}</Badge>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Push vs Replace -->
                <FeatureCard
                    :title="$t('replace: true vs push')"
                    :description="$t('Push adds a new history entry. Replace overwrites the current one. Click each a few times, then use the browser Back button to see the difference.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Button
                                variant="outline"
                                class="w-full"
                                @click="visitPush()"
                            >
                                Push (default)
                            </Button>
                            <p class="text-xs text-muted-foreground"> {{ $t('Each click adds a history entry. Back returns to the previous visit.') }} </p>
                        </div>

                        <div class="space-y-2">
                            <Button class="w-full" @click="visitReplace()"> {{ $t('Replace') }} </Button>
                            <p class="text-xs text-muted-foreground"> {{ $t('Each click overwrites the current entry. Back skips replaced visits entirely.') }} </p>
                        </div>
                    </div>
                </FeatureCard>

                <!-- preserveUrl -->
                <FeatureCard
                    :title="$t('preserveUrl: true')"
                    :description="$t('Makes a server request but keeps the browser URL unchanged. First use the Push button a few times to increase the visit number, then try these.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Button
                                class="w-full"
                                @click="
                                    router.post(
                                        '/features/navigation/history-management',
                                        {},
                                        {
                                            preserveUrl: true,
                                            preserveScroll: true,
                                        },
                                    )
                                "
                            >
                                POST (preserveUrl)
                            </Button>
                            <p class="text-xs text-muted-foreground"> {{ $t('The timestamp updates but the URL stays the same.') }} </p>
                        </div>

                        <div class="space-y-2">
                            <Button
                                variant="outline"
                                class="w-full"
                                @click="
                                    router.post(
                                        '/features/navigation/history-management',
                                        {},
                                        {
                                            preserveScroll: true,
                                        },
                                    )
                                "
                            >
                                POST (default)
                            </Button>
                            <p class="text-xs text-muted-foreground"> {{ $t('For comparison. The URL may update.') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
