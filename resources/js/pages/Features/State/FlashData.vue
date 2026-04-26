<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, onUnmounted } from 'vue';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'State Management' },
    { title: 'Flash Data' },
];

const page = usePage();
const eventLog = ref<string[]>([]);
const flashListenerActive = ref(false);
let removeFlashListener: (() => void) | null = null;

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 15) eventLog.value.pop();
}

watch(
    () => page.flash,
    (flash) => {
        if (flash.message) {
            log(
                `Flash received: "${flash.message}" (type: ${flash.type || 'default'})`,
            );
        }
    },
    { deep: true },
);

function registerFlashListener() {
    if (removeFlashListener) return;
    removeFlashListener = router.on('flash', (event) => {
        const flash = event.detail.flash;
        log(`[router.on('flash')] keys: ${Object.keys(flash).join(', ')}`);
    });
    flashListenerActive.value = true;
    log('Flash event listener registered');
}

function unregisterFlashListener() {
    if (!removeFlashListener) return;
    removeFlashListener();
    removeFlashListener = null;
    flashListenerActive.value = false;
    log('Flash event listener removed');
}

onUnmounted(() => {
    removeFlashListener?.();
});
</script>

<template>
    <Head :title="$t('Flash Data')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Flash Data')"
                docs="data-props/flash-data"
                controller="app/Http/Controllers/Feature/StateController.php#L16"
            > {{ $t('One-time messages with') }} <code class="text-xs">{{ $t('Inertia::flash()') }}</code> {{ $t('(server) and') }} <code class="text-xs">{{ $t('router.flash()') }}</code> {{ $t('(client).') }} </FeatureHeader>

            <!-- Flash display -->
            <div
                v-if="page.flash.message"
                class="rounded-lg border p-3 text-sm"
                :class="{
                    'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200':
                        page.flash.type === 'success',
                    'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200':
                        page.flash.type === 'error',
                    'border-yellow-200 bg-yellow-50 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-950 dark:text-yellow-200':
                        page.flash.type === 'warning',
                    'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-200':
                        !page.flash.type,
                }"
            >
                <div class="flex items-center gap-2">
                    <span>{{ page.flash.message }}</span>
                    <Badge variant="outline" class="text-xs">{{
                        page.flash.type || 'default'
                    }}</Badge>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Server-side flash -->
                <FeatureCard :title="$t('Server-Side Flash')">
                    <template #description>
                        <code class="text-xs"
                            >{{ $t('Inertia::flash(\'key\', \'value\')->back()') }}</code
                        >{{ $t('. Sends flash with a server request.') }} </template>
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button
                                size="sm"
                                @click="
                                    router.post(
                                        '/features/state/flash-data',
                                        {},
                                        { preserveScroll: true },
                                    )
                                "
                            > {{ $t('Success Flash') }} </Button>
                            <Button
                                variant="destructive"
                                size="sm"
                                @click="
                                    router.post(
                                        '/features/state/flash-data/error',
                                        {},
                                        { preserveScroll: true },
                                    )
                                "
                            > {{ $t('Error Flash') }} </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.post(
                                        '/features/state/flash-data/warning',
                                        {},
                                        { preserveScroll: true },
                                    )
                                "
                            > {{ $t('Warning Flash') }} </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Each button sends a POST, the server responds with') }} <code>{{ $t('Inertia::flash()') }}</code> {{ $t('and a redirect back.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Client-side flash -->
                <FeatureCard :title="$t('Client-Side Flash')">
                    <template #description>
                        <code class="text-xs">{{ $t('router.flash(\'key\', \'value\')') }}</code
                        >{{ $t('. Sets flash without a server request.') }} </template>
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.flash({
                                        message: 'Client-side flash!',
                                        type: 'success',
                                    })
                                "
                            > {{ $t('Client Flash (object)') }} </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.flash(
                                        'message',
                                        'Hello from the client!',
                                    )
                                "
                            > {{ $t('Client Flash (key/value)') }} </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('No server request. Flash data is set directly on the client and available via') }} <code>{{ $t('usePage().flash') }}</code>.
                        </p>
                    </div>
                </FeatureCard>

                <!-- Callback form of router.flash() -->
                <FeatureCard :title="$t('Callback Flash')">
                    <template #description>
                        <code class="text-xs"
                            >{{ $t('router.flash((current) => ({ ...current, key: value }))') }}</code
                        >{{ $t('. Merge with existing flash.') }} </template>
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.flash(
                                        (current: Record<string, unknown>) => ({
                                            ...current,
                                            counter:
                                                ((current.counter as number) ||
                                                    0) + 1,
                                        }),
                                    )
                                "
                            >
                                Increment Counter
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.flash(
                                        (current: Record<string, unknown>) => ({
                                            ...current,
                                            message: 'Merged!',
                                            type: 'success',
                                        }),
                                    )
                                "
                            >
                                Merge Message
                            </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('The callback receives current flash data and returns the new flash state, enabling incremental updates.') }} </p>
                    </div>
                </FeatureCard>

                <!-- router.on('flash') listener -->
                <FeatureCard :title="$t('Flash Event Listener')">
                    <template #description>
                        <code class="text-xs">{{ $t('router.on(\'flash\', callback)') }}</code
                        >{{ $t('. Fires whenever flash data is received.') }} </template>
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <Badge
                                :variant="
                                    flashListenerActive
                                        ? 'default'
                                        : 'secondary'
                                "
                                class="text-xs"
                                >{{
                                    flashListenerActive ? $t('Active') : $t('Inactive')
                                }}</Badge
                            >
                            <Button
                                v-if="!flashListenerActive"
                                size="sm"
                                @click="registerFlashListener"
                                >{{ $t('Register') }}</Button
                            >
                            <Button
                                v-else
                                variant="outline"
                                size="sm"
                                @click="unregisterFlashListener"
                                >{{ $t('Remove') }}</Button
                            >
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Register the listener, then trigger any flash. The event log will show entries from both the') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('watch') }}</code
                            > {{ $t('and the') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('router.on(\'flash\')') }}</code
                            > {{ $t('listener.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Flash state display -->
                <FeatureCard info-card :title="$t('Current Flash State')">
                    <template #description>
                        <code class="text-xs">{{ $t('usePage().flash') }}</code>{{ $t('. Reactive access to current flash data.') }} </template>
                    <CodeBlock :code="JSON.stringify(page.flash, null, 2)" />
                    <p class="mt-2 text-xs text-muted-foreground"> {{ $t('Flash data is one-time. It clears after being sent to the client and is not persisted in browser history state.') }} </p>
                </FeatureCard>

                <!-- Event Log -->
                <FeatureCard info-card :title="$t('Event Log')">
                    <template #header-action>
                        <Button variant="ghost" size="sm" @click="eventLog = []"
                            >{{ $t('Clear') }}</Button
                        >
                    </template>
                    <div v-if="eventLog.length" class="space-y-1">
                        <div
                            v-for="(entry, i) in eventLog"
                            :key="i"
                            class="rounded bg-muted px-2 py-1 font-mono text-xs"
                        >
                            {{ entry }}
                        </div>
                    </div>
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Trigger a flash to see events.') }} </p>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
