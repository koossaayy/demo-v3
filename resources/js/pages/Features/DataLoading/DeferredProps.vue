<script setup lang="ts">
import { Head, Deferred, router } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    quickStat: string;
    slowStats?: {
        totalContacts: number;
        totalFavorites: number;
    };
    heavyData?: Array<{ id: number; name: string }>;
    flakyReport?: { value: number };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Data Loading') },
    { title: t('Deferred Props') },
];
</script>

<template>
    <Head :title="$t('Deferred Props')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Deferred Props')"
                docs="data-props/deferred-props"
                controller="app/Http/Controllers/Feature/DataLoadingController.php#L16"
            > {{ $t('Lazy-loaded props with skeleton fallbacks. Expensive data loads after the initial page render.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Instant Prop -->
                <FeatureCard
                    :title="$t('Instant Prop')"
                    :description="$t('Loaded immediately with the page response.')"
                >
                    <Badge>{{ quickStat }}</Badge>
                </FeatureCard>

                <!-- Deferred: slowStats (default group) -->
                <FeatureCard :title="$t('Deferred Stats')">
                    <template #description>
                        <i18n-t keypath="{el0}. Default group, ~800ms delay." tag="span" scope="global"><template #el0><code class="text-xs">Inertia::defer()</code></template></i18n-t>
                    </template>
                    <Deferred data="slowStats">
                        <template #fallback>
                            <div class="space-y-3">
                                <div
                                    class="h-4 w-3/4 animate-pulse rounded bg-muted"
                                />
                                <div
                                    class="h-4 w-1/2 animate-pulse rounded bg-muted"
                                />
                            </div>
                        </template>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm">{{ $t('Total Contacts') }}</span>
                                <Badge variant="secondary">{{
                                    slowStats?.totalContacts
                                }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm">{{ $t('Total Favorites') }}</span>
                                <Badge variant="secondary">{{
                                    slowStats?.totalFavorites
                                }}</Badge>
                            </div>
                        </div>
                    </Deferred>
                </FeatureCard>

                <!-- Deferred: heavyData (named group "heavy") -->
                <FeatureCard :title="$t('Heavy Data')">
                    <template #description>
                        <i18n-t keypath="{el0}. Named group, ~1.5s delay." tag="span" scope="global"><template #el0><code class="text-xs">Inertia::defer(fn, 'heavy')</code
                        ></template></i18n-t>
                    </template>
                    <Deferred data="heavyData">
                        <template #fallback>
                            <div class="space-y-2">
                                <div
                                    v-for="i in 5"
                                    :key="i"
                                    class="h-4 animate-pulse rounded bg-muted"
                                    :style="{
                                        width: `${60 + Math.random() * 40}%`,
                                    }"
                                />
                            </div>
                        </template>
                        <div class="space-y-1.5">
                            <div
                                v-for="contact in heavyData"
                                :key="contact.id"
                                class="flex items-center justify-between rounded bg-muted/50 px-2 py-1 text-sm"
                            >
                                <span>{{ contact.name }}</span>
                                <Badge variant="outline" class="text-xs"
                                    >#{{ contact.id }}</Badge
                                >
                            </div>
                        </div>
                    </Deferred>
                </FeatureCard>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Reloading slot demo -->
                <FeatureCard :title="$t('Reloading Slot')">
                    <template #description>
                        <i18n-t keypath="The {el0} slot prop lets you show stale data with a visual indicator while refreshing." tag="span" scope="global"><template #el0><code class="text-xs">reloading</code></template></i18n-t>
                    </template>
                    <template #header-action>
                        <Button
                            variant="outline"
                            @click="router.reload({ only: ['slowStats'] })"
                        > {{ $t('Reload Stats') }} </Button>
                    </template>
                    <Deferred data="slowStats">
                        <template #fallback>
                            <div class="space-y-3">
                                <div
                                    class="h-4 w-3/4 animate-pulse rounded bg-muted"
                                />
                                <div
                                    class="h-4 w-1/2 animate-pulse rounded bg-muted"
                                />
                            </div>
                        </template>
                        <template #default="{ reloading }">
                            <div
                                :class="{
                                    'opacity-50 transition-opacity': reloading,
                                }"
                                class="space-y-2"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-sm"
                                        ><i18n-t keypath="Total Contacts: {el0}" tag="span" scope="global"><template #el0><strong>{{
                                            slowStats?.totalContacts
                                        }}</strong></template></i18n-t></span
                                    >
                                    <Badge
                                        v-if="reloading"
                                        variant="secondary"
                                        class="text-xs"
                                        >{{ $t('Refreshing...') }}</Badge
                                    >
                                </div>
                                <div class="text-sm">
                                    <i18n-t keypath="Total Favorites: {el0}" tag="span" scope="global"><template #el0><strong>{{
                                        slowStats?.totalFavorites
                                    }}</strong></template></i18n-t>
                                </div>
                            </div>
                        </template>
                    </Deferred>
                </FeatureCard>

                <FeatureCard :title="$t('Rescued Deferred Prop')" badge="v3.1">
                    <template #description>
                        <i18n-t keypath="{el0}. When the closure throws, the prop is marked rescued and the {el1} slot renders instead of the fallback. Retry sends an {el2} header so the server returns data instead of throwing." tag="span" scope="global"><template #el0><code class="text-xs"
                            >Inertia::defer(fn, rescue: true)</code
                        ></template><template #el1><code class="text-xs">rescue</code></template><template #el2><code class="text-xs">X-Force-Success</code></template></i18n-t>
                    </template>
                    <Deferred data="flakyReport">
                        <template #fallback>
                            <div class="space-y-3">
                                <div
                                    class="h-4 w-2/3 animate-pulse rounded bg-muted"
                                />
                                <div
                                    class="h-4 w-1/3 animate-pulse rounded bg-muted"
                                />
                            </div>
                        </template>
                        <template #rescue="{ reloading }">
                            <div
                                :class="{
                                    'opacity-50 transition-opacity': reloading,
                                }"
                                class="space-y-3 rounded border border-destructive/30 bg-destructive/5 p-3 text-sm text-destructive"
                            >
                                <div>
                                    <div class="font-medium"> {{ $t('Failed to load report') }} </div>
                                    <div class="text-xs opacity-80"> {{ $t('Server threw during deferred resolution.') }} </div>
                                </div>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    :disabled="reloading"
                                    @click="
                                        router.reload({
                                            only: ['flakyReport'],
                                            headers: { 'X-Force-Success': '1' },
                                        })
                                    "
                                >
                                    {{ reloading ? $t('Retrying...') : $t('Retry') }}
                                </Button>
                            </div>
                        </template>
                        <div
                            class="rounded border border-emerald-500/30 bg-emerald-500/5 p-3 text-sm"
                        >
                            <div
                                class="font-medium text-emerald-700 dark:text-emerald-400"
                            > {{ $t('Report loaded') }} </div>
                            <div class="text-xs">
                                <i18n-t keypath="Value: {el0}" tag="span" scope="global"><template #el0><strong>{{ flakyReport?.value }}</strong></template></i18n-t>
                            </div>
                        </div>
                    </Deferred>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
