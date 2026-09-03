<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { onceProps } from '@/wayfinder/App/Http/Controllers/Feature/DataLoadingController';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    page: number;
    staticData?: {
        generatedAt: string;
        randomId: number;
    };
    freshData?: {
        generatedAt: string;
        value: number;
    };
    expiringData?: {
        generatedAt: string;
        value: number;
    };
    aliasedData?: {
        generatedAt: string;
        value: number;
    };
    dynamicData: {
        timestamp: string;
        randomNumber: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Data Loading') },
    { title: t('Once Props') },
];

const otherPage = () => (props.page === 1 ? 2 : 1);
</script>

<template>
    <Head :title="$t('Once Props')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Once Props')"
                docs="data-props/once-props"
                controller="app/Http/Controllers/Feature/DataLoadingController.php#L191"
            > {{ $t('Props that resolve once and are remembered by the client. Navigate between pages to see the once prop persist while dynamic props change.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Navigation -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Navigate Between Pages')"
                >
                    <template #header-action>
                        <Badge variant="secondary"> {{ $t('Page {0}', [page]) }} </Badge>
                    </template>
                    <template #description> {{ $t('Both pages return the same once props. Navigate between them to see once props remembered while dynamic props update. The server skips the callback entirely on subsequent visits.') }} </template>
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <Button
                                :variant="page === 1 ? 'default' : 'outline'"
                                as-child
                            >
                                <Link :href="onceProps(1).url"> {{ $t('Page 1') }} </Link>
                            </Button>
                            <Button
                                :variant="page === 2 ? 'default' : 'outline'"
                                as-child
                            >
                                <Link :href="onceProps(2).url"> {{ $t('Page 2') }} </Link>
                            </Button>
                            <Button
                                variant="outline"
                                @click="
                                    router.reload({
                                        only: ['staticData', 'expiringData'],
                                    })
                                "
                            > {{ $t('Force Refresh Once Prop') }} </Button>
                        </div>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <ul class="list-inside list-disc space-y-1">
                                <li>
                                    <i18n-t keypath="Navigate to Page {v0} and back. The {el0} keeps its original timestamp, the {el1} updates." tag="span" scope="global"><template #v0>{{ otherPage() }}</template><template #el0><strong>once prop</strong></template><template #el1><strong>dynamic prop</strong></template></i18n-t>
                                </li>
                                <li>
                                    <i18n-t keypath="{el0} uses {el1} to explicitly re-resolve once props." tag="span" scope="global"><template #el0><strong>Force Refresh</strong></template><template #el1><code
                                        >router.reload({ only: ['staticData',
                                        'expiringData'] })</code
                                    ></template></i18n-t>
                                </li>
                                <li>
                                    <i18n-t keypath="{el0} re-evaluates on every visit. {el1} remembers for 5 seconds." tag="span" scope="global"><template #el0><strong>.fresh()</strong></template><template #el1><strong>.until(5s)</strong></template></i18n-t>
                                </li>
                            </ul>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Static (once) prop -->
                <FeatureCard :title="$t('Once Prop')">
                    <template #description>
                        <i18n-t keypath="{el0}. Resolved on first load, remembered across navigations." tag="span" scope="global"><template #el0><code class="text-xs">Inertia::once(fn () => ...)</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium"
                                >{{ $t('Generated At') }}</span
                            >
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ staticData?.generatedAt }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Random ID') }}</span>
                            <Badge variant="secondary">{{
                                staticData?.randomId
                            }}</Badge>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Dynamic prop -->
                <FeatureCard
                    :title="$t('Dynamic Prop')"
                    :description="$t('Regular prop, re-evaluated on every visit.')"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Timestamp') }}</span>
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ dynamicData.timestamp }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium"
                                >{{ $t('Random Number') }}</span
                            >
                            <Badge variant="secondary">{{
                                dynamicData.randomNumber
                            }}</Badge>
                        </div>
                    </div>
                </FeatureCard>

                <!-- fresh() -->
                <FeatureCard :title="$t('.fresh()')">
                    <template #description>
                        <i18n-t keypath="{el0}. Forces re-evaluation on every visit." tag="span" scope="global"><template #el0><code class="text-xs">Inertia::once(...)->fresh()</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium"
                                >{{ $t('Generated At') }}</span
                            >
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ freshData?.generatedAt ?? 'N/A' }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Value') }}</span>
                            <Badge variant="secondary">{{
                                freshData?.value ?? 'N/A'
                            }}</Badge>
                        </div>
                        <p class="text-xs text-muted-foreground">
                            <i18n-t keypath="Changes on every visit because {el0} forces re-resolution." tag="span" scope="global"><template #el0><code>.fresh()</code></template></i18n-t>
                        </p>
                    </div>
                </FeatureCard>

                <!-- until() -->
                <FeatureCard :title="$t('.until(5s)')">
                    <template #description>
                        <i18n-t keypath="{el0}. Remembered for 5 seconds, then re-evaluated on the next visit." tag="span" scope="global"><template #el0><code class="text-xs"
                            >Inertia::once(...)->until(now()->addSeconds(5))</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium"
                                >{{ $t('Generated At') }}</span
                            >
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ expiringData?.generatedAt ?? 'N/A' }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Value') }}</span>
                            <Badge variant="secondary">{{
                                expiringData?.value ?? 'N/A'
                            }}</Badge>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Cached for 5 seconds. After expiry, the next visit re-evaluates the callback.') }} </p>
                    </div>
                </FeatureCard>

                <!-- as() -->
                <FeatureCard :title="$t('.as(\'key\')')">
                    <template #description>
                        <i18n-t keypath="{el0}. Custom storage key for cross-page sharing." tag="span" scope="global"><template #el0><code class="text-xs"
                            >Inertia::once(...)->as('shared-once-key')</code
                        ></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium"
                                >{{ $t('Generated At') }}</span
                            >
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ aliasedData?.generatedAt ?? 'N/A' }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Value') }}</span>
                            <Badge variant="secondary">{{
                                aliasedData?.value ?? 'N/A'
                            }}</Badge>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Multiple pages can share cached data under the same custom key, even if their prop names differ.') }} </p>
                    </div>
                </FeatureCard>

                <!-- API Reference -->
                <FeatureCard info-card :title="$t('Fluent API')">
                    <div class="space-y-3 text-xs">
                        <CodeBlock
                            code="
                            // Basic once prop
                            Inertia::once(fn () => ...);

                            // Always re-evaluate
                            Inertia::once(fn () => ...)->fresh();

                            // Expire after 5s
                            Inertia::once(fn () => ...)->until(now()->addSeconds(5));

                            // Custom cache key
                            Inertia::once(fn () => ...)->as('key');

                            // Shared once prop
                            Inertia::shareOnce('key', value);
                        "
                        />
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
