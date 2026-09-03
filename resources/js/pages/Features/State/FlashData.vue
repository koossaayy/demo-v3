<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, onUnmounted } from 'vue';
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
    { title: t('State Management') },
    { title: t('Flash Data') },
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
                t('Flash received: "{0}" (type: {1})', [flash.message, flash.type || 'default']),
            );
        }
    },
    { deep: true },
);

function registerFlashListener() {
    if (removeFlashListener) return;
    removeFlashListener = router.on('flash', (event) => {
        const flash = event.detail.flash;
        log(t('[router.on(\'flash\')] keys: {0}', [Object.keys(flash).join(', ')]));
    });
    flashListenerActive.value = true;
    log(t('Flash event listener registered'));
}

function unregisterFlashListener() {
    if (!removeFlashListener) return;
    removeFlashListener();
    removeFlashListener = null;
    flashListenerActive.value = false;
    log(t('Flash event listener removed'));
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
            >
                <i18n-t keypath="One-time messages with {el0} (server) and {el1} (client)." tag="span" scope="global"><template #el0><code class="text-xs">Inertia::flash()</code></template><template #el1><code class="text-xs">router.flash()</code></template></i18n-t>
            </FeatureHeader>

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
                        <i18n-t keypath="{el0}. Sends flash with a server request." tag="span" scope="global"><template #el0><code class="text-xs"
                            >Inertia::flash('key', 'value')->back()</code
                        ></template></i18n-t>
                    </template>
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
                        <p class="text-xs text-muted-foreground">
                            <i18n-t keypath="Each button sends a POST, the server responds with {el0} and a redirect back." tag="span" scope="global"><template #el0><code>Inertia::flash()</code></template></i18n-t>
                        </p>
                    </div>
                </FeatureCard>

                <!-- Client-side flash -->
                <FeatureCard :title="$t('Client-Side Flash')">
                    <template #description>
                        <i18n-t keypath="{el0}. Sets flash without a server request." tag="span" scope="global"><template #el0><code class="text-xs">router.flash('key', 'value')</code
                        ></template></i18n-t>
                    </template>
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
                        <p class="text-xs text-muted-foreground">
                            <i18n-t keypath="No server request. Flash data is set directly on the client and available via {el0}." tag="span" scope="global"><template #el0><code>usePage().flash</code></template></i18n-t>
                        </p>
                    </div>
                </FeatureCard>

                <!-- Callback form of router.flash() -->
                <FeatureCard :title="$t('Callback Flash')">
                    <template #description>
                        <i18n-t keypath="{el0}. Merge with existing flash." tag="span" scope="global"><template #el0><code class="text-xs"
                            >router.flash((current) => ({ ...current, key: value
                            }))</code
                        ></template></i18n-t>
                    </template>
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
                            > {{ $t('Increment Counter') }} </Button>
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
                            > {{ $t('Merge Message') }} </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('The callback receives current flash data and returns the new flash state, enabling incremental updates.') }} </p>
                    </div>
                </FeatureCard>

                <!-- router.on('flash') listener -->
                <FeatureCard :title="$t('Flash Event Listener')">
                    <template #description>
                        <i18n-t keypath="{el0}. Fires whenever flash data is received." tag="span" scope="global"><template #el0><code class="text-xs">router.on('flash', callback)</code
                        ></template></i18n-t>
                    </template>
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
                        <p class="text-xs text-muted-foreground">
                            <i18n-t keypath="Register the listener, then trigger any flash. The event log will show entries from both the {el0} and the {el1} listener." tag="span" scope="global"><template #el0><code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >watch</code
                            ></template><template #el1><code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >router.on('flash')</code
                            ></template></i18n-t>
                        </p>
                    </div>
                </FeatureCard>

                <!-- Flash state display -->
                <FeatureCard info-card :title="$t('Current Flash State')">
                    <template #description>
                        <i18n-t keypath="{el0}. Reactive access to current flash data." tag="span" scope="global"><template #el0><code class="text-xs">usePage().flash</code></template></i18n-t>
                    </template>
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
