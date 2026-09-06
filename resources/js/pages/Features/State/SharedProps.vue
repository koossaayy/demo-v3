<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('State Management') },
    { title: t('Shared Props') },
];
</script>

<template>
    <Head :title="$t('Shared Props')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Shared Props')"
                docs="data-props/shared-data"
                controller="app/Http/Controllers/Feature/StateController.php#L42"
            >
                <i18n-t keypath="Global data shared across all pages via {el0} middleware." tag="span" scope="global"><template #el0><code class="text-xs">HandleInertiaRequests</code></template></i18n-t>
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Shared props display -->
                <FeatureCard :title="$t('Current Shared Props')">
                    <template #description>
                        <i18n-t keypath="Accessed via {el0}. Available on every page without explicitly passing them." tag="span" scope="global"><template #el0><code class="text-xs">usePage()</code></template></i18n-t>
                    </template>
                    <div class="space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('App Name') }}</span
                                >
                                <Badge variant="secondary">{{
                                    $page.props.name
                                }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Auth User') }}</span
                                >
                                <Badge variant="outline" class="text-xs">{{
                                    $page.props.auth?.user?.name ?? 'null'
                                }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('User Email') }}</span
                                >
                                <Badge
                                    variant="outline"
                                    class="font-mono text-xs"
                                    >{{
                                        $page.props.auth?.user?.email ?? 'null'
                                    }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium"
                                    >{{ $t('Sidebar Open') }}</span
                                >
                                <Badge
                                    :variant="
                                        $page.props.sidebarOpen
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    >{{ $page.props.sidebarOpen }}</Badge
                                >
                            </div>
                        </div>
                    </div>
                </FeatureCard>

                <!-- usePage() raw data -->
                <FeatureCard info-card :title="$t('usePage() Data')">
                    <template #description>
                        <i18n-t keypath="The full {el0} object includes props, url, component, and more." tag="span" scope="global"><template #el0><code class="text-xs">usePage()</code></template></i18n-t>
                    </template>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('URL') }}</span>
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ $page.url }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Component') }}</span>
                            <Badge
                                variant="outline"
                                class="font-mono text-xs"
                                >{{ $page.component }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium">{{ $t('Version') }}</span>
                            <Badge variant="secondary">{{
                                $page.version ?? 'null'
                            }}</Badge>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Server config -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Server Configuration')"
                    :description="$t('How shared props are defined in the HandleInertiaRequests middleware.')"
                >
                    <CodeBlock
                        code="// app/Http/Middleware/HandleInertiaRequests.php

public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'name' => config('app.name'),
        'auth' => [
            'user' => $request->user(),
        ],
        'sidebarOpen' => ! $request->hasCookie('sidebar_state')
            || $request->cookie('sidebar_state') === 'true',
    ];
}"
                    />
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
