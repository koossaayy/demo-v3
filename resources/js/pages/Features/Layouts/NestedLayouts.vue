<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Layouts & Head' },
    { title: 'Nested Layouts' },
];
</script>

<template>
    <Head :title="$t('Nested Layouts')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Nested Layouts')"
                docs="the-basics/layouts#nested-layouts"
                controller="app/Http/Controllers/Feature/LayoutController.php#L20"
            > {{ $t('Multi-level layout nesting using') }} <code class="text-xs"
                    >{{ $t('defineOptions({ layout: [Outer, Inner] })') }}</code
                >.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- How nesting works -->
                <FeatureCard
                    :title="$t('How Nesting Works')"
                    :description="$t('Pass an array of layouts to nest them from outermost to innermost.')"
                >
                    <div class="space-y-3">
                        <CodeBlock>
                            <textarea>                                {{ $t('import AppLayout from "./AppLayout.vue" import SectionLayout from "./SectionLayout.vue" defineOptions({ layout: [AppLayout, SectionLayout], })') }}
                            </textarea>
                        </CodeBlock>
                        <p class="text-xs text-muted-foreground"> {{ $t('This renders as:') }} <code
                                >AppLayout &gt; SectionLayout &gt; Page
                                Content</code
                            >{{ $t('. Each layout uses') }} <code>&lt;slot /&gt;</code> {{ $t('for its child content.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Visual nesting demo -->
                <FeatureCard
                    info-card
                    :title="$t('Nesting Visualization')"
                    :description="$t('How nested layouts wrap around page content.')"
                >
                    <div
                        class="rounded-md border-2 border-blue-300 p-3 dark:border-blue-700"
                    >
                        <p
                            class="mb-2 text-xs font-semibold text-blue-600 dark:text-blue-400"
                        > {{ $t('App Layout (outermost)') }} </p>
                        <div
                            class="rounded-md border-2 border-green-300 p-3 dark:border-green-700"
                        >
                            <p
                                class="mb-2 text-xs font-semibold text-green-600 dark:text-green-400"
                            > {{ $t('Section Layout (middle)') }} </p>
                            <div
                                class="rounded-md border-2 border-orange-300 p-3 dark:border-orange-700"
                            >
                                <p
                                    class="text-xs font-semibold text-orange-600 dark:text-orange-400"
                                > {{ $t('Page Content (innermost)') }} </p>
                                <p class="mt-1 text-xs text-muted-foreground"> {{ $t('This is where your page component renders.') }} </p>
                            </div>
                        </div>
                    </div>
                </FeatureCard>

                <!-- Current app structure -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    title="This App's Layout Structure"
                    :description="$t('How this demo app uses layouts.')"
                >
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <CodeBlock :title="$t('Standard pages:')">
                                <textarea>
                                    <AppLayout :breadcrumbs="...">
                                      <!-- page content -->
                                    </AppLayout>
                                </textarea>
                            </CodeBlock>
                            <p class="mt-2 text-xs text-muted-foreground"> {{ $t('Single layout wrapping page content directly in the template.') }} </p>
                        </div>
                        <div>
                            <CodeBlock :title="$t('Persistent nested:')">
                                <textarea>
                                    defineOptions({
                                      layout: [AppLayout, SectionLayout],
                                    })

                                    // Template has no layout wrapper
                                    <template>
                                      <!-- content only -->
                                    </template>
                                </textarea>
                            </CodeBlock>
                            <p class="mt-2 text-xs text-muted-foreground"> {{ $t('The template has no layout wrapper. Inertia manages both layouts externally.') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
