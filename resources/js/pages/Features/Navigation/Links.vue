<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
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
    { title: t('Links & Methods') },
];

const eventLog = ref<string[]>([]);

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 10) {
        eventLog.value.pop();
    }
}

function manualVisit(method: string) {
    const fn = router[method as keyof typeof router] as (
        ...args: unknown[]
    ) => void;
    if (method === 'delete') {
        fn('/features/navigation/links', {
            preserveScroll: true,
            onSuccess: () => log(`router.${method}() completed`),
        });
    } else {
        fn(
            '/features/navigation/links',
            { demo: true },
            {
                preserveScroll: true,
                onSuccess: () => log(`router.${method}() completed`),
            },
        );
    }
}
</script>

<template>
    <Head :title="$t('Links & Methods')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Links & Methods')"
                docs="the-basics/links"
                controller="app/Http/Controllers/Feature/NavigationController.php#L13"
            >
                The &lt;Link&gt; component and programmatic router visits.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Server Timestamp -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Server Response')"
                >
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-muted-foreground"
                            >{{ $t('Server timestamp:') }}</span
                        >
                        <Badge variant="outline">{{ timestamp }}</Badge>
                    </div>
                </FeatureCard>

                <!-- Link Component -->
                <FeatureCard
                    :title="$t('<Link> Component')"
                    :description="$t('Client-side navigation that avoids full page reloads.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold"> {{ $t('GET Link (default)') }} </h4>
                            <Link
                                href="/features/navigation/links"
                                class="text-sm text-primary underline underline-offset-2"
                            >
                                Reload this page via &lt;Link&gt;
                            </Link>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold"> {{ $t('POST as button') }} </h4>
                            <Link
                                href="/features/navigation/links"
                                method="post"
                                as="button"
                                preserve-scroll
                                class="inline-flex items-center rounded-md bg-primary px-3 py-1.5 text-sm text-primary-foreground hover:bg-primary/90"
                            > {{ $t('POST Request') }} </Link>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold">{{ $t('PUT as button') }}</h4>
                            <Link
                                href="/features/navigation/links"
                                method="put"
                                as="button"
                                preserve-scroll
                                class="inline-flex items-center rounded-md bg-primary px-3 py-1.5 text-sm text-primary-foreground hover:bg-primary/90"
                            > {{ $t('PUT Request') }} </Link>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold"> {{ $t('DELETE as button') }} </h4>
                            <Link
                                href="/features/navigation/links"
                                method="delete"
                                as="button"
                                preserve-scroll
                                class="inline-flex items-center rounded-md bg-destructive px-3 py-1.5 text-sm text-destructive-foreground hover:bg-destructive/90"
                            > {{ $t('DELETE Request') }} </Link>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold"> {{ $t('Link with data') }} </h4>
                            <Link
                                href="/features/navigation/links"
                                method="post"
                                as="button"
                                :data="{ name: 'Inertia' }"
                                preserve-scroll
                                class="inline-flex items-center rounded-md border border-black/10 bg-background px-3 py-1.5 text-sm hover:bg-accent dark:border-white/10"
                            > {{ $t('POST with payload') }} </Link>
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold"> {{ $t('Link with custom headers') }} </h4>
                            <Link
                                href="/features/navigation/links"
                                :headers="{ 'X-Custom-Header': 'demo' }"
                                class="text-sm text-primary underline underline-offset-2"
                            > {{ $t('GET with custom header') }} </Link>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Manual Visits -->
                <FeatureCard
                    :title="$t('Programmatic Visits')"
                    :description="$t('Navigate using router.get(), router.post(), etc.')"
                >
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="manualVisit('get')"
                                >router.get()</Button
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="manualVisit('post')"
                                >router.post()</Button
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="manualVisit('put')"
                                >router.put()</Button
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="manualVisit('patch')"
                                >router.patch()</Button
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="manualVisit('delete')"
                                >router.delete()</Button
                            >
                        </div>

                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold">
                                router.reload()
                            </h4>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    router.reload({
                                        onSuccess: () =>
                                            log('router.reload() completed'),
                                    })
                                "
                            >
                                Reload current page
                            </Button>
                        </div>

                        <div
                            class="mt-4 rounded-md border border-black/10 p-3 dark:border-white/10"
                        >
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-semibold">{{ $t('Event Log') }}</h4>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="eventLog = []"
                                    >{{ $t('Clear') }}</Button
                                >
                            </div>
                            <div v-if="eventLog.length" class="space-y-1">
                                <div
                                    v-for="(entry, i) in eventLog"
                                    :key="i"
                                    class="rounded bg-muted px-2 py-1 font-mono text-xs"
                                >
                                    {{ entry }}
                                </div>
                            </div>
                            <p v-else class="text-xs text-muted-foreground"> {{ $t('Click a button to see events.') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
