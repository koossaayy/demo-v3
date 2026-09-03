<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const { t } = useI18n();

defineProps<{
    timestamp: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Navigation') },
    { title: t('Async Requests') },
];

const eventLog = ref<string[]>([]);
let requestCounter = 0;

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 20) {
        eventLog.value.pop();
    }
}

function sendSyncRequest() {
    const id = ++requestCounter;
    log(t('[#{0}] Sending SYNC request...', [id]));

    router.visit('/features/navigation/async-requests', {
        preserveScroll: true,
        preserveState: true,
        onStart: () => log(t('[#{0}] Started', [id])),
        onSuccess: () => log(t('[#{0}] Completed', [id])),
        onCancel: () => log(t('[#{0}] Cancelled (replaced by newer request)', [id])),
    });
}

function sendAsyncRequest() {
    const id = ++requestCounter;
    log(t('[#{0}] Sending ASYNC request...', [id]));

    router.visit('/features/navigation/async-requests', {
        async: true,
        preserveScroll: true,
        preserveState: true,
        onStart: () => log(t('[#{0}] Started (async)', [id])),
        onSuccess: () => log(t('[#{0}] Completed (async)', [id])),
    });
}

let cancelToken: { cancel: () => void } | null = null;

function sendCancellable() {
    const id = ++requestCounter;
    log(t('[#{0}] Sending cancellable request (2s delay)...', [id]));

    router.get(
        '/features/navigation/async-slow',
        { delay: 2 },
        {
            preserveScroll: true,
            preserveState: true,
            onCancelToken: (token) => {
                cancelToken = token;
            },
            onStart: () => log(t('[#{0}] Started (cancellable)', [id])),
            onSuccess: () => {
                log(t('[#{0}] Completed', [id]));
                cancelToken = null;
            },
            onCancel: () => {
                log(t('[#{0}] Cancelled by user', [id]));
                cancelToken = null;
            },
        },
    );
}

function cancelRequest() {
    if (cancelToken) {
        cancelToken.cancel();
    } else {
        log(t('No active cancellable request'));
    }
}
</script>

<template>
    <Head :title="$t('Async Requests')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Async Requests')"
                docs="the-basics/manual-visits"
                controller="app/Http/Controllers/Feature/NavigationController.php#L58"
            > {{ $t('Request queuing behavior. Sync (default) vs async concurrent requests.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Sync Requests -->
                <FeatureCard
                    :title="$t('Synchronous (Default)')"
                    :description="$t('Each new request cancels any in-flight request. Click rapidly to see cancellation.')"
                >
                    <div class="space-y-3">
                        <Button @click="sendSyncRequest()"> {{ $t('Send Sync Request') }} </Button>
                        <p class="text-xs text-muted-foreground"> {{ $t('Click multiple times quickly. Previous requests get cancelled.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Async Requests -->
                <FeatureCard :title="$t('Async (Concurrent)')">
                    <template #description>
                        <i18n-t keypath="With {el0}, requests run concurrently without cancelling each other." tag="span" scope="global"><template #el0><code class="rounded bg-muted px-1 py-0.5 text-xs"
                            >async: true</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <Button @click="sendAsyncRequest()"> {{ $t('Send Async Request') }} </Button>
                        <p class="text-xs text-muted-foreground"> {{ $t('Click multiple times. All requests complete independently.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Cancel Token -->
                <FeatureCard :title="$t('Cancel Token')">
                    <template #description>
                        <i18n-t keypath="Use {el0} to manually cancel a specific request." tag="span" scope="global"><template #el0><code class="rounded bg-muted px-1 py-0.5 text-xs"
                            >onCancelToken</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex gap-2">
                            <Button @click="sendCancellable()"> {{ $t('Send Slow Request (2s)') }} </Button>
                            <Button
                                variant="destructive"
                                @click="cancelRequest()"
                            > {{ $t('Cancel') }} </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Start a slow request, then cancel it before it completes.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Server State -->
                <FeatureCard info-card :title="$t('Server Response')">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-muted-foreground"
                            >{{ $t('Timestamp:') }}</span
                        >
                        <Badge variant="outline">{{ timestamp }}</Badge>
                    </div>
                </FeatureCard>

                <!-- Event Log -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Request Log')"
                >
                    <template #header-action>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="
                                eventLog = [];
                                requestCounter = 0;
                            "
                            >{{ $t('Clear') }}</Button
                        >
                    </template>
                    <div
                        v-if="eventLog.length"
                        class="max-h-64 space-y-1 overflow-y-auto"
                    >
                        <div
                            v-for="(entry, i) in eventLog"
                            :key="i"
                            class="rounded bg-muted px-2 py-1 font-mono text-xs"
                            :class="{
                                'text-red-600 dark:text-red-400':
                                    entry.includes('Cancelled'),
                                'text-green-600 dark:text-green-400':
                                    entry.includes('Completed'),
                            }"
                        >
                            {{ entry }}
                        </div>
                    </div>
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Click buttons above to see request flow.') }} </p>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
