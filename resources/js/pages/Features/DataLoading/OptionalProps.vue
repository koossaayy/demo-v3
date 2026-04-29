<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    regularData: {
        timestamp: string;
        message: string;
    };
    optionalData?: {
        generatedAt: string;
        contacts: Array<{ id: number; name: string }>;
    };
    deferredData?: {
        generatedAt: string;
        totalContacts: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Data Loading') },
    { title: t('Optional Props') },
];
</script>

<template>
    <Head :title="$t('Optional Props')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Optional Props')"
                docs="data-props/partial-reloads#lazy-data-evaluation"
                controller="app/Http/Controllers/Feature/DataLoadingController.php#L162"
            > {{ $t('Compare regular, optional, and deferred prop loading behaviors.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Regular prop -->
                <FeatureCard
                    :title="$t('Regular Prop')"
                    :description="$t('Always included in the response. Available immediately.')"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Status') }}</span>
                            <Badge variant="default">{{ $t('Loaded') }}</Badge>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Timestamp') }}</span>
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ regularData.timestamp }}</Badge
                            >
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{ regularData.message }}
                        </p>
                    </div>
                </FeatureCard>

                <!-- Optional prop -->
                <FeatureCard :title="$t('Optional Prop')">
                    <template #description>
                        <code class="text-xs">{{ $t('Inertia::optional()') }}</code>{{ $t('. Only loaded when explicitly requested via partial reload.') }} </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Status') }}</span>
                            <Badge
                                :variant="
                                    optionalData ? 'default' : 'secondary'
                                "
                            >
                                {{ optionalData ? $t('Loaded') : $t('Not loaded') }}
                            </Badge>
                        </div>
                        <template v-if="optionalData">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Generated At') }}</span
                                >
                                <Badge
                                    variant="outline"
                                    class="font-mono text-xs"
                                    >{{ optionalData.generatedAt }}</Badge
                                >
                            </div>
                            <div class="space-y-1">
                                <span class="text-sm font-medium"
                                    >{{ $t('Contacts') }}</span
                                >
                                <div
                                    v-for="contact in optionalData.contacts"
                                    :key="contact.id"
                                    class="rounded bg-muted px-2 py-1 text-xs"
                                >
                                    {{ contact.name }}
                                </div>
                            </div>
                        </template>
                        <p v-else class="text-xs text-muted-foreground"> {{ $t('Click "Load Optional Data" to fetch this prop.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Deferred prop -->
                <FeatureCard :title="$t('Deferred Prop')">
                    <template #description>
                        <code class="text-xs">{{ $t('Inertia::defer()') }}</code>{{ $t('. Automatically loaded after the initial page render.') }} </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Status') }}</span>
                            <Badge
                                :variant="
                                    deferredData ? 'default' : 'secondary'
                                "
                            >
                                {{ deferredData ? $t('Loaded') : $t('Loading...') }}
                            </Badge>
                        </div>
                        <template v-if="deferredData">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Generated At') }}</span
                                >
                                <Badge
                                    variant="outline"
                                    class="font-mono text-xs"
                                    >{{ deferredData.generatedAt }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Total Contacts') }}</span
                                >
                                <Badge variant="secondary">{{
                                    deferredData.totalContacts
                                }}</Badge>
                            </div>
                        </template>
                        <p v-else class="text-xs text-muted-foreground"> {{ $t('Automatically loads after the initial page render...') }} </p>
                    </div>
                </FeatureCard>
            </div>

            <!-- Controls -->
            <FeatureCard
                :title="$t('Controls')"
                :description="$t('Trigger different reload strategies to see how each prop type behaves.')"
            >
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        <Button
                            @click="router.reload({ only: ['optionalData'] })"
                        > {{ $t('Load Optional Data') }} </Button>
                        <Button
                            variant="outline"
                            @click="router.reload({ only: ['regularData'] })"
                        > {{ $t('Reload Regular Only') }} </Button>
                        <Button variant="outline" @click="router.reload()"> {{ $t('Reload All') }} </Button>
                    </div>

                    <div
                        class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                    >
                        <p><strong>{{ $t('Key Differences:') }}</strong></p>
                        <ul class="mt-1 list-inside list-disc space-y-1">
                            <li>
                                <strong>{{ $t('Regular') }}</strong>{{ $t(': always resolved on full visits. Excluded from partial reloads unless explicitly requested.') }} </li>
                            <li>
                                <strong>{{ $t('Optional') }}</strong>{{ $t(': never resolved unless explicitly requested with') }} <code>{{ $t('only: [\'optionalData\']') }}</code>.
                            </li>
                            <li>
                                <strong>{{ $t('Deferred') }}</strong>{{ $t(': not included on initial load, but automatically fetched client-side after render.') }} </li>
                        </ul>
                    </div>
                </div>
            </FeatureCard>
        </div>
    </AppLayout>
</template>
