<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import FeatureCard from '@/components/FeatureCard.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import PersistentDemoLayout from '@/layouts/PersistentDemoLayout.vue';

defineOptions({ layout: PersistentDemoLayout });

const name = ref('');
const email = ref('');
</script>

<template>
    <Head :title="$t('Persistent Layouts - Page 2')" />

    <div class="grid gap-6 lg:grid-cols-2">
        <FeatureCard
            :title="$t('Page 2 State')"
            :description="$t('A different page component with its own state. This also resets on navigation.')"
        >
            <div class="space-y-4">
                <div class="space-y-2">
                    <Label for="name">{{ $t('Name') }}</Label>
                    <Input
                        id="name"
                        v-model="name"
                        :placeholder="$t('Type something...')"
                    />
                </div>
                <div class="space-y-2">
                    <Label for="email">{{ $t('Email') }}</Label>
                    <Input
                        id="email"
                        v-model="email"
                        :placeholder="$t('Type something...')"
                    />
                </div>

                <p class="text-xs text-muted-foreground"> {{ $t('Type in these fields, switch to Page 1, then come back. The inputs clear because this page component remounts. The layout state above stays intact.') }} </p>
            </div>
        </FeatureCard>

        <FeatureCard
            info-card
            :title="$t('What Persists')"
            :description="$t('Everything in the layout component survives navigation between pages that share it.')"
        >
            <div class="space-y-3">
                <div class="space-y-2 text-xs text-muted-foreground">
                    <p>
                        <span class="font-medium text-foreground"
                            >{{ $t('Refs and reactive state') }}</span
                        > {{ $t('keep their values (stopwatch, counter).') }} </p>
                    <p>
                        <span class="font-medium text-foreground"
                            >{{ $t('Intervals and timers') }}</span
                        > {{ $t('continue running without interruption.') }} </p>
                    <p>
                        <span class="font-medium text-foreground"
                            >{{ $t('Event listeners') }}</span
                        > {{ $t('registered in the layout stay attached.') }} </p>
                    <p>
                        <span class="font-medium text-foreground"
                            >{{ $t('onMounted') }}</span
                        > {{ $t('only fires once, not on every navigation.') }} </p>
                </div>
                <p class="text-xs text-muted-foreground"> {{ $t('Click "Leave layout" to navigate away. When you return, the layout remounts and all state resets.') }} </p>
            </div>
        </FeatureCard>
    </div>
</template>
