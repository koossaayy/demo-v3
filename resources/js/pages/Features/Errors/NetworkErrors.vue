<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { addToast } from '@/composables/useFlashToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Error Handling') },
    { title: t('Network Errors') },
];

const eventLog = ref<string[]>([]);
const interceptEnabled = ref(false);
let removeListener: (() => void) | null = null;

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 15) eventLog.value.pop();
}

function toggleIntercept() {
    if (interceptEnabled.value) {
        removeListener?.();
        removeListener = null;
        interceptEnabled.value = false;
        log('Global networkError listener removed');
    } else {
        removeListener = router.on('networkError', (event) => {
            event.preventDefault();
            log(`Intercepted network error, default behavior prevented`);
            addToast(
                t('Network error intercepted. Default behavior was prevented.'),
                'warning',
            );
        });
        interceptEnabled.value = true;
        log('Global networkError listener registered');
    }
}

onUnmounted(() => {
    removeListener?.();
});
</script>

<template>
    <Head :title="$t('Network Errors')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Network Errors')"
                docs="advanced/events#network-error"
                controller="app/Http/Controllers/Feature/NetworkErrorController.php#L35"
            > {{ $t('Handle network failures with') }} <code class="text-xs">{{ $t('onNetworkError') }}</code> {{ $t('callback and') }} <code class="text-xs">{{ $t('router.on(\'networkError\')') }}</code>.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard
                    :title="$t('When Network Errors Occur')"
                    :description="$t('Network errors fire when a request fails due to connectivity issues or when page component resolution fails.')"
                >
                    <div class="space-y-3">
                        <div
                            class="space-y-2 rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p>{{ $t('Common causes of network errors:') }}</p>
                            <ul class="list-inside list-disc space-y-1">
                                <li>{{ $t('No internet connection') }}</li>
                                <li>{{ $t('DNS resolution failure') }}</li>
                                <li>{{ $t('Server unreachable / timeout') }}</li>
                                <li>{{ $t('CORS errors') }}</li>
                                <li>{{ $t('Request aborted by browser') }}</li>
                            </ul>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Network errors are different from HTTP exceptions. The server never responded at all.') }} </p>
                    </div>
                </FeatureCard>

                <FeatureCard :title="$t('Error Interception')">
                    <template #description> {{ $t('Toggle global') }} <code class="text-xs">{{ $t('networkError') }}</code> {{ $t('listener.') }} </template>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <Button
                                :variant="
                                    interceptEnabled ? 'default' : 'outline'
                                "
                                size="sm"
                                @click="toggleIntercept"
                            >
                                {{ interceptEnabled ? $t('Disable') : $t('Enable') }}
                                Interception
                            </Button>
                            <Badge
                                :variant="
                                    interceptEnabled ? 'default' : 'secondary'
                                "
                            >
                                {{ interceptEnabled ? $t('Active') : $t('Inactive') }}
                            </Badge>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('When active,') }} <code>{{ $t('event.preventDefault()') }}</code> {{ $t('stops the default network error behavior.') }} </p>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p>
                                <strong>{{ $t('To test:') }}</strong> {{ $t('Open DevTools → Network → set throttling to "Offline", then try navigating.') }} </p>
                        </div>
                    </div>
                </FeatureCard>

                <FeatureCard info-card class="lg:col-span-2" :title="$t('Event Log')">
                    <template #header-action>
                        <Button variant="ghost" size="sm" @click="eventLog = []"
                            >{{ $t('Clear') }}</Button
                        >
                    </template>
                    <div
                        v-if="eventLog.length"
                        class="max-h-48 space-y-1 overflow-y-auto"
                    >
                        <div
                            v-for="(entry, i) in eventLog"
                            :key="i"
                            class="rounded bg-muted px-2 py-1 font-mono text-xs"
                        >
                            {{ entry }}
                        </div>
                    </div>
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Enable interception and simulate an offline state to see events.') }} </p>
                </FeatureCard>

                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('API Reference')"
                >
                    <div class="grid gap-3 sm:grid-cols-2">
                        <CodeBlock
                            :title="$t('Per-visit callback:')"
                            code="router.get('/url', {}, {
  onNetworkError: (error) => {
    // Return false to prevent default
    return false
  },
})"
                        />
                        <CodeBlock
                            :title="$t('Global event:')"
                            code="router.on('networkError', (event) => {
  console.log(event.detail.exception)
  event.preventDefault()
})"
                        />
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
