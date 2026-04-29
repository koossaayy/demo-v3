<script setup lang="ts">
import { Head, WhenVisible } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    section1?: Array<{ id: number; name: string }>;
    section2?: { totalContacts: number; generated: string };
    section3?: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Data Loading') },
    { title: t('When Visible') },
];
</script>

<template>
    <Head :title="$t('When Visible')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('When Visible')"
                docs="data-props/load-when-visible"
                controller="app/Http/Controllers/Feature/DataLoadingController.php#L70"
            > {{ $t('Viewport-triggered data loading using') }} <code class="text-xs">{{ $t('Inertia::optional()') }}</code> +
                <code class="text-xs">&lt;WhenVisible&gt;</code>.
            </FeatureHeader>

            <FeatureCard :title="$t('How It Works')">
                <template #description> {{ $t('Each section below uses') }} <code class="text-xs">{{ $t('Inertia::optional()') }}</code> {{ $t('on the server and') }} <code class="text-xs">&lt;WhenVisible&gt;</code> {{ $t('on the client. Data is only fetched when the component scrolls into the viewport.') }} </template>
                <p class="text-sm text-muted-foreground"> {{ $t('Scroll down to trigger each section. Each has a simulated server delay.') }} </p>
            </FeatureCard>

            <!-- Spacer to force scrolling -->
            <div
                class="flex h-[75vh] items-center justify-center rounded-lg bg-gradient-to-b from-transparent to-muted/50"
            >
                <p class="animate-bounce text-sm text-muted-foreground">
                    &#8595; Scroll down to trigger lazy loading
                </p>
            </div>

            <!-- Section 1: Contact list (~500ms) -->
            <FeatureCard :title="$t('Recent Contacts')">
                <WhenVisible data="section1">
                    <template #fallback>
                        <div class="space-y-2">
                            <div
                                v-for="i in 3"
                                :key="i"
                                class="h-8 animate-pulse rounded bg-muted"
                            />
                        </div>
                    </template>

                    <div class="space-y-2">
                        <div
                            v-for="contact in section1"
                            :key="contact.id"
                            class="flex items-center justify-between rounded bg-muted/50 px-3 py-2 text-sm"
                        >
                            <span>{{ contact.name }}</span>
                            <Badge variant="outline" class="text-xs"
                                >#{{ contact.id }}</Badge
                            >
                        </div>
                    </div>
                </WhenVisible>
            </FeatureCard>

            <!-- Another spacer -->
            <div
                class="flex h-[75vh] items-center justify-center rounded-lg bg-gradient-to-b from-transparent to-muted/50"
            >
                <p class="animate-bounce text-sm text-muted-foreground">
                    &#8595; Keep scrolling
                </p>
            </div>

            <!-- Section 2: Stats (~800ms) -->
            <FeatureCard :title="$t('Statistics')">
                <template #description> {{ $t('Loaded with') }} <code class="text-xs">{{ $t(':buffer="200"') }}</code>{{ $t('. Starts loading 200px before visible.') }} </template>
                <WhenVisible data="section2" :buffer="200">
                    <template #fallback>
                        <div class="flex gap-4">
                            <div
                                class="h-8 w-32 animate-pulse rounded bg-muted"
                            />
                            <div
                                class="h-8 w-48 animate-pulse rounded bg-muted"
                            />
                        </div>
                    </template>

                    <div class="flex flex-wrap gap-3">
                        <Badge variant="secondary"
                            >Total Contacts:
                            {{ section2?.totalContacts }}</Badge
                        >
                        <Badge variant="secondary"
                            >Generated: {{ section2?.generated }}</Badge
                        >
                    </div>
                </WhenVisible>
            </FeatureCard>

            <!-- Another spacer -->
            <div
                class="flex h-[75vh] items-center justify-center rounded-lg bg-gradient-to-b from-transparent to-muted/50"
            >
                <p class="animate-bounce text-sm text-muted-foreground">
                    &#8595; Almost there
                </p>
            </div>

            <!-- Section 3: Favorites (~600ms) -->
            <FeatureCard :title="$t('Favorite Contacts')">
                <WhenVisible data="section3">
                    <template #fallback>
                        <div class="space-y-2">
                            <div
                                v-for="i in 5"
                                :key="i"
                                class="h-8 animate-pulse rounded bg-muted"
                            />
                        </div>
                    </template>

                    <div v-if="section3?.length" class="space-y-2">
                        <div
                            v-for="contact in section3"
                            :key="contact.id"
                            class="flex items-center justify-between rounded bg-muted/50 px-3 py-2 text-sm"
                        >
                            <span>{{ contact.name }}</span>
                            <span class="text-yellow-500">&#9733;</span>
                        </div>
                    </div>
                    <p v-else class="text-sm text-muted-foreground"> {{ $t('No favorite contacts found.') }} </p>
                </WhenVisible>
            </FeatureCard>
        </div>
    </AppLayout>
</template>
