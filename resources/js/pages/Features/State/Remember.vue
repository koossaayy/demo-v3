<script setup lang="ts">
import { Head, Link, useRemember } from '@inertiajs/vue3';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('State Management') },
    { title: t('Remember') },
];

const form = useRemember(
    {
        name: '',
        email: '',
        notes: '',
    },
    'remember-demo',
);

const counter = useRemember({ count: 0 }, 'remember-counter');
</script>

<template>
    <Head :title="$t('Remember')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Remember')"
                docs="data-props/remembering-state"
                controller="app/Http/Controllers/Feature/StateController.php#L11"
            > {{ $t('State persistence across browser history navigation with') }} <code class="text-xs">{{ $t('useRemember()') }}</code>.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Form state -->
                <FeatureCard
                    :title="$t('Remembered Form State')"
                    :description="$t('Fill in fields, navigate away, then press the browser back button. The form state is restored.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">{{ $t('Name') }}</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                :placeholder="$t('John Doe')"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="email">{{ $t('Email') }}</Label>
                            <Input
                                id="email"
                                type="text"
                                v-model="form.email"
                                placeholder="john@example.com"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="notes">{{ $t('Notes') }}</Label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="3"
                                class="flex w-full rounded-md border border-input/60 bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                                :placeholder="$t('Some notes...')"
                            />
                        </div>

                        <div class="flex gap-2 pt-2">
                            <Link
                                href="/contacts"
                                class="inline-flex items-center rounded-md bg-primary px-3 py-1.5 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                            > {{ $t('Navigate Away') }} </Link>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    form.name = '';
                                    form.email = '';
                                    form.notes = '';
                                "
                            > {{ $t('Clear Fields') }} </Button>
                        </div>
                        <p class="text-xs text-muted-foreground"> {{ $t('Fill fields, click "Navigate Away", then press browser Back. Fields will be restored.') }} </p>
                    </div>
                </FeatureCard>

                <!-- Counter state -->
                <FeatureCard info-card :title="$t('Remembered Counter')">
                    <template #description> {{ $t('A separate') }} <code class="text-xs">{{ $t('useRemember()') }}</code> {{ $t('instance with its own key.') }} </template>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="counter.count--"
                                >-</Button
                            >
                            <Badge
                                variant="secondary"
                                class="px-4 py-1 text-lg"
                                >{{ counter.count }}</Badge
                            >
                            <Button
                                variant="outline"
                                size="sm"
                                @click="counter.count++"
                                >+</Button
                            >
                        </div>
                        <Link
                            href="/contacts"
                            class="inline-flex items-center rounded-md bg-primary px-3 py-1.5 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                        > {{ $t('Navigate Away') }} </Link>
                        <p class="text-xs text-muted-foreground"> {{ $t('Increment the counter, click "Navigate Away", then press browser Back. The counter value is restored.') }} </p>

                        <div
                            class="rounded-lg border border-black/5 bg-neutral-50/80 p-3 font-mono text-xs dark:border-white/5 dark:bg-neutral-900/80"
                        >
                            <p><strong>{{ $t('Manual API:') }}</strong></p>
                            <p class="mt-1">
                                <code>{{ $t('router.remember(data, \'key\')') }}</code>{{ $t('. Save state') }} </p>
                            <p>
                                <code>{{ $t('router.restore(\'key\')') }}</code>{{ $t('. Restore state') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
