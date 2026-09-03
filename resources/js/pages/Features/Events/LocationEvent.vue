<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Events & Lifecycle') },
    { title: t('Location Event') },
];

const updateAvailable = ref(false);
const eventLog = ref<string[]>([]);
let remove: (() => void) | null = null;

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 20) eventLog.value.pop();
}

onMounted(() => {
    remove = router.on('location', (event) => {
        log(t('location event (versionChange: {0})', [event.detail.versionChange]));

        if (event.detail.versionChange) {
            // A new asset version is available. Instead of letting Inertia
            // force a full-page reload, cancel it and surface a banner so the
            // user can finish what they're doing and reload when ready.
            event.preventDefault();
            updateAvailable.value = true;
        }
    });
});

onUnmounted(() => remove?.());

function deploy() {
    router.get('/features/events/location-event/deploy');
}

function reload() {
    window.location.reload();
}
</script>

<template>
    <Head :title="$t('Location Event')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div
                v-if="updateAvailable"
                class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200"
            >
                <span> {{ $t('A new version of this app is available. Reload to get the latest assets.') }} </span>
                <div class="flex gap-2">
                    <Button size="sm" @click="reload">{{ $t('Reload now') }}</Button>
                    <Button
                        variant="ghost"
                        size="sm"
                        @click="updateAvailable = false"
                    > {{ $t('Dismiss') }} </Button>
                </div>
            </div>

            <FeatureHeader
                :title="$t('Location Event')"
                docs="advanced/events#the-location-event"
                controller="app/Http/Controllers/Feature/EventController.php#L54"
            > {{ $t('The cancelable') }} <code class="text-xs">{{ $t('location') }}</code> {{ $t('event fires before Inertia forces a full-page visit. Cancel it to intercept asset version changes with your own UI instead of a hard reload.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard
                    :title="$t('Simulate a Deployment')"
                    :description="$t('The server reports a different asset version, triggering a 409 location visit.')"
                >
                    <div class="space-y-4">
                        <Button size="sm" @click="deploy"> {{ $t('Deploy new version') }} </Button>
                        <p class="text-xs text-muted-foreground"> {{ $t('The asset version usually changes on its own after a deploy, since Inertia derives it from your compiled assets. Here we set it by hand with') }} <code class="text-xs"
                                >{{ $t('Inertia::version(\'new-asset-version-hash\')') }}</code
                            > {{ $t('to fake a deploy. The stale version triggers a reload, which our listener cancels in favor of the banner above.') }} </p>

                        <CodeBlock
                            :title="$t('Server (EventController):')"
                            code="
                                public function deployNewVersion(): RedirectResponse
                                {
                                    Inertia::version('new-asset-version-hash');

                                    return back();
                                }
                            "
                        />
                    </div>
                </FeatureCard>

                <FeatureCard
                    info-card
                    :title="$t('Intercepting the Event')"
                    :description="$t('Listen for location and preventDefault on a version change.')"
                >
                    <CodeBlock
                        code="
                            router.on('location', (event) => {
                              if (event.detail.versionChange) {
                                event.preventDefault()
                                showUpdateBanner()
                              }
                            })
                        "
                    />
                    <p class="mt-4 text-xs text-muted-foreground">
                        <code class="text-xs">versionChange</code> {{ $t('is') }} <code class="text-xs">{{ $t('true') }}</code> {{ $t('for asset version mismatches and') }} <code class="text-xs">{{ $t('false') }}</code> {{ $t('for explicit') }} <code class="text-xs">{{ $t('Inertia::location()') }}</code> {{ $t('redirects. Background requests (') }}<code class="text-xs"
                            >router.reload()</code
                        >{{ $t(', polling) no longer force a reload on version change; new assets are picked up on the next user-initiated visit.') }} </p>
                </FeatureCard>

                <FeatureCard info-card class="lg:col-span-2" :title="$t('Event Log')">
                    <template #description>
                        <code class="text-xs">{{ $t('location') }}</code> {{ $t('events captured on this page.') }} </template>
                    <template #header-action>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="eventLog = []"
                        > {{ $t('Clear') }} </Button>
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
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Deploy a new version to see the event fire.') }} </p>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
