<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Events & Lifecycle') },
    { title: t('Global Events') },
];

const eventLog = ref<string[]>([]);
const removers: (() => void)[] = [];

function log(event: string, detail?: string) {
    eventLog.value.unshift(
        `${new Date().toLocaleTimeString()} - ${event}${detail ? ': ' + detail : ''}`,
    );
    if (eventLog.value.length > 20) eventLog.value.pop();
}

onMounted(() => {
    removers.push(
        router.on('before', (event) => {
            log('before', event.detail.visit.url.toString());
        }),
        router.on('start', (event) => {
            log('start', event.detail.visit.url.toString());
        }),
        router.on('progress', (event) => {
            log('progress', `${event.detail.progress?.percentage}%`);
        }),
        router.on('success', (event) => {
            log('success', event.detail.page.url);
        }),
        router.on('error', (event) => {
            log('error', JSON.stringify(event.detail.errors));
        }),
        router.on('finish', () => {
            log('finish');
        }),
        router.on('navigate', (event) => {
            log('navigate', event.detail.page.url);
        }),
        router.on('flash', (event) => {
            log('flash', JSON.stringify(event.detail.flash));
        }),
    );
});

onUnmounted(() => {
    removers.forEach((remove) => remove());
});
</script>

<template>
    <Head :title="$t('Global Events')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Global Events')"
                docs="advanced/events#registering-listeners"
                controller="app/Http/Controllers/Feature/EventController.php#L12"
            > {{ $t('Router event system with') }} <code class="text-xs">{{ $t('router.on()') }}</code>{{ $t('. Fires on every Inertia request.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard
                    :title="$t('Trigger Events')"
                    :description="$t('Perform actions and watch the event log update in real time.')"
                >
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button size="sm" @click="router.reload()"> {{ $t('Reload Page') }} </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.post(
                                        '/features/events/global-events/action',
                                        {},
                                        { preserveScroll: true },
                                    )
                                "
                            > {{ $t('POST Action') }} </Button>
                            <Link
                                href="/features/events/visit-callbacks"
                                class="inline-flex items-center rounded-md border border-black/10 bg-background px-3 py-1.5 text-sm hover:bg-accent dark:border-white/10"
                            > {{ $t('Navigate Away') }} </Link>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Each action triggers a sequence of events: before → start → success/error → finish → navigate. The progress event only fires during file uploads.') }} </p>

                        <CodeBlock
                            :title="$t('Cleanup:')"
                            code="
                                const remove = router.on('before', (e) => {
                                  console.log(e.detail.visit.url)
                                })

                                // Later: deregister the listener
                                remove()
                            "
                        />
                    </div>
                </FeatureCard>

                <FeatureCard
                    info-card
                    :title="$t('Available Events')"
                    :description="$t('All events fired by the Inertia router.')"
                >
                    <div class="grid grid-cols-2 gap-2">
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('before') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('start') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('progress') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('success') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('error') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('finish') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('navigate') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('flash') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('httpException') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('networkError') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('prefetching') }}</Badge
                        >
                        <Badge variant="outline" class="justify-center"
                            >{{ $t('prefetched') }}</Badge
                        >
                    </div>
                </FeatureCard>

                <FeatureCard info-card class="lg:col-span-2" :title="$t('Event Log')">
                    <template #description
                        >{{ $t('Events captured by') }} <code class="text-xs">{{ $t('router.on()') }}</code> {{ $t('listeners registered on this page.') }}</template
                    >
                    <template #header-action>
                        <Button variant="ghost" size="sm" @click="eventLog = []"
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
                        >
                            {{ entry }}
                        </div>
                    </div>
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Trigger an action to see events.') }} </p>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
