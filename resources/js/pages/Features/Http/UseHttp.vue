<script setup lang="ts">
import { Head, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { useHttpApi } from '@/wayfinder/App/Http/Controllers/Feature/HttpController';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'HTTP' }, { title: 'useHttp' }];

interface ApiResponse {
    message: string;
    timestamp: string;
}

const http = useHttp<{ name: string }, ApiResponse>({ name: '' });
const response = ref<ApiResponse | null>(null);
const cancelled = ref(false);

function submitForm() {
    cancelled.value = false;
    http.post(useHttpApi.url(), {
        onSuccess: (res) => {
            response.value = res;
        },
    });
}

function cancelRequest() {
    http.cancel();
    cancelled.value = true;
}
</script>

<template>
    <Head :title="$t('useHttp')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('useHttp')"
                docs="the-basics/http-requests"
                controller="app/Http/Controllers/Feature/HttpController.php#L12"
            > {{ $t('Non-Inertia HTTP requests with') }} <code class="text-xs">{{ $t('useHttp()') }}</code>{{ $t('. Like') }} <code class="text-xs">{{ $t('useForm()') }}</code> {{ $t('but returns JSON, no page visit.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Demo form -->
                <FeatureCard
                    :title="$t('API Request Demo')"
                    :description="$t('Send a POST request to a JSON endpoint. No page navigation occurs.')"
                >
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="name">{{ $t('Your Name') }}</Label>
                            <Input
                                id="name"
                                v-model="http.name"
                                :placeholder="$t('Enter your name...')"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                size="sm"
                                :disabled="http.processing"
                                @click="submitForm"
                            >
                                {{
                                    http.processing
                                        ? $t('Sending...')
                                        : $t('Send Request')
                                }}
                            </Button>
                            <Button
                                v-if="http.processing"
                                variant="destructive"
                                size="sm"
                                @click="cancelRequest"
                            > {{ $t('Cancel') }} </Button>
                            <span
                                v-if="cancelled"
                                class="text-sm text-muted-foreground"
                                >{{ $t('Request cancelled') }}</span
                            >
                        </div>
                    </div>
                </FeatureCard>

                <!-- Reactive state -->
                <FeatureCard info-card :title="$t('Reactive State')">
                    <template #description> {{ $t('All the state properties from') }} <code class="text-xs">{{ $t('useHttp()') }}</code>.
                    </template>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span>{{ $t('processing') }}</span>
                            <Badge
                                :variant="
                                    http.processing ? 'default' : 'secondary'
                                "
                                >{{ http.processing }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span>{{ $t('isDirty') }}</span>
                            <Badge
                                :variant="
                                    http.isDirty ? 'default' : 'secondary'
                                "
                                >{{ http.isDirty }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span>{{ $t('wasSuccessful') }}</span>
                            <Badge
                                :variant="
                                    http.wasSuccessful ? 'default' : 'secondary'
                                "
                                >{{ http.wasSuccessful }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span>{{ $t('recentlySuccessful') }}</span>
                            <Badge
                                :variant="
                                    http.recentlySuccessful
                                        ? 'default'
                                        : 'secondary'
                                "
                                >{{ http.recentlySuccessful }}</Badge
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span>{{ $t('hasErrors') }}</span>
                            <Badge
                                :variant="
                                    http.hasErrors ? 'destructive' : 'secondary'
                                "
                                >{{ http.hasErrors }}</Badge
                            >
                        </div>
                    </div>
                </FeatureCard>

                <!-- Response display -->
                <FeatureCard
                    info-card
                    :title="$t('Response')"
                    :description="$t('The JSON response from the API endpoint.')"
                >
                    <CodeBlock
                        v-if="response"
                        :code="JSON.stringify(response, null, 2)"
                    />
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Send a request to see the response.') }} </p>
                </FeatureCard>

                <!-- Key differences -->
                <FeatureCard
                    info-card
                    :title="$t('useHttp vs useForm')"
                    :description="$t('When to use which.')"
                >
                    <div class="space-y-3 text-xs">
                        <div
                            class="rounded-md border border-black/10 p-2 dark:border-white/10"
                        >
                            <p class="font-semibold">{{ $t('useForm()') }}</p>
                            <p class="text-muted-foreground"> {{ $t('Triggers Inertia page visits. Server returns Inertia responses. Page component gets swapped.') }} </p>
                        </div>
                        <div class="rounded-md border-2 border-primary p-2">
                            <p class="font-semibold">{{ $t('useHttp()') }}</p>
                            <p class="text-muted-foreground"> {{ $t('Plain HTTP requests. Server returns JSON. No page navigation. Great for API endpoints, toggles, status checks.') }} </p>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
