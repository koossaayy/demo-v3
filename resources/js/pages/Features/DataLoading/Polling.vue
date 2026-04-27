<script setup lang="ts">
import { Head, usePoll } from '@inertiajs/vue3';
import { ref } from 'vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

defineProps<{
    currentTime: string;
    randomNumber: number;
    contactCount: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Data Loading' },
    { title: 'Polling' },
];

const pollCount = ref(0);

// Auto-start polling every 2 seconds
const { start, stop } = usePoll(
    2000,
    {
        onFinish: () => pollCount.value++,
    },
    {
        autoStart: false,
    },
);

const isPolling = ref(false);

function togglePolling() {
    if (isPolling.value) {
        stop();
        isPolling.value = false;
    } else {
        start();
        isPolling.value = true;
    }
}
</script>

<template>
    <Head :title="$t('Polling')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Polling')"
                docs="data-props/polling"
                controller="app/Http/Controllers/Feature/DataLoadingController.php#L104"
            > {{ $t('Periodic data refreshing with') }} <code class="text-xs">{{ $t('usePoll()') }}</code>{{ $t('. Automatically throttled when tab is in background.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Controls -->
                <FeatureCard :title="$t('Polling Controls')">
                    <template #description>
                        <code class="text-xs"
                            >{{ $t('usePoll(2000, options, { autoStart: false })') }}</code
                        >{{ $t('. Manually start and stop polling.') }} </template>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <Button
                                @click="togglePolling"
                                :variant="isPolling ? 'destructive' : 'default'"
                            >
                                {{
                                    isPolling ? $t('Stop Polling') : $t('Start Polling')
                                }}
                            </Button>
                            <Badge
                                :variant="isPolling ? 'default' : 'secondary'"
                            >
                                {{ isPolling ? $t('Active') : $t('Stopped') }}
                            </Badge>
                        </div>

                        <div class="text-sm text-muted-foreground"> {{ $t('Poll count:') }} <strong>{{ pollCount }}</strong>
                        </div>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p>
                                <strong>{{ $t('Tip:') }}</strong> {{ $t('Switch to another tab while polling is active. When you return, notice that polls were throttled (90% reduction) in the background. Use') }} <code>{{ $t('keepAlive: true') }}</code> {{ $t('to prevent this.') }} </p>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Live Data -->
                <FeatureCard
                    info-card
                    :title="$t('Live Data')"
                    :description="$t('These values refresh from the server on each poll cycle.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Server Time') }}</span
                                >
                                <Badge
                                    variant="outline"
                                    class="font-mono text-xs"
                                    >{{ currentTime }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Random Number') }}</span
                                >
                                <Badge variant="secondary">{{
                                    randomNumber
                                }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Contact Count') }}</span
                                >
                                <Badge variant="secondary">{{
                                    contactCount
                                }}</Badge>
                            </div>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
