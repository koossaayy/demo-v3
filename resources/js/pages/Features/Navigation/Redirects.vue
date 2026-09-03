<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
    { title: t('Redirects') },
];

const flashMessage = ref<string | null>(null);

watch(
    () => usePage().props,
    () => {
        const message = usePage<{ flash?: { message?: string } }>().props.flash
            ?.message;
        if (message) {
            flashMessage.value = message;
        }
    },
    { deep: true },
);

router.on('success', () => {
    const message = usePage<{ flash?: { message?: string } }>().props.flash
        ?.message;
    if (message) {
        flashMessage.value = message;
    }
});
</script>

<template>
    <Head :title="$t('Redirects')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Redirects')"
                docs="the-basics/redirects"
                controller="app/Http/Controllers/Feature/NavigationController.php#L111"
            > {{ $t('Server-side redirect patterns in Inertia. All redirects from PUT, PATCH, and DELETE are automatically converted to 303 responses.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard
                    info-card
                    :title="$t('Server Response')"
                    class="lg:col-span-2"
                >
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-muted-foreground"
                                >{{ $t('Timestamp:') }}</span
                            >
                            <Badge variant="outline">{{ timestamp }}</Badge>
                        </div>
                        <div
                            v-if="flashMessage"
                            class="flex items-center gap-2"
                        >
                            <span class="text-sm text-muted-foreground"
                                >{{ $t('Flash:') }}</span
                            >
                            <Badge variant="default">{{ flashMessage }}</Badge>
                        </div>
                    </div>
                </FeatureCard>

                <FeatureCard
                    title="redirect()->back()"
                    :description="$t('The most common pattern. Redirects back to the previous page after processing a form or action.')"
                >
                    <div class="space-y-3">
                        <p class="text-xs text-muted-foreground"> {{ $t('Uses') }} <code>{{ $t('Inertia::flash(\'message\', ...)->back()') }}</code> {{ $t('to redirect back with flash data.') }} </p>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                router.post(
                                    '/features/navigation/redirects/back',
                                    {},
                                    { preserveScroll: true },
                                )
                            "
                        > {{ $t('Submit and redirect back') }} </Button>
                    </div>
                </FeatureCard>

                <FeatureCard
                    title="to_route()"
                    :description="$t('Redirect to a specific named route. Commonly used after creating or updating a resource.')"
                >
                    <div class="space-y-3">
                        <p class="text-xs text-muted-foreground"> {{ $t('Uses') }} <code>{{ $t('Inertia::flash(...)') }}</code> {{ $t('then') }} <code
                                >to_route('features.navigation.redirects')</code
                            > {{ $t('to redirect to a named route.') }} </p>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                router.post(
                                    '/features/navigation/redirects/to-route',
                                )
                            "
                        > {{ $t('Submit and redirect to route') }} </Button>
                    </div>
                </FeatureCard>

                <FeatureCard
                    :title="$t('Inertia::location()')"
                    :description="$t('External redirects that leave the Inertia app. Generates a 409 response with an X-Inertia-Location header.')"
                >
                    <div class="space-y-3">
                        <p class="text-xs text-muted-foreground"> {{ $t('Triggers a full page visit to an external URL via') }} <code>window.location</code>{{ $t('. The browser navigates away from the SPA entirely.') }} </p>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                router.post(
                                    '/features/navigation/redirects/external',
                                )
                            "
                        > {{ $t('Redirect externally') }} </Button>
                    </div>
                </FeatureCard>

                <FeatureCard
                    :title="$t('303 Redirect Behavior')"
                    :description="$t('PUT, PATCH, and DELETE requests are automatically converted to GET redirects via 303 status.')"
                >
                    <div class="space-y-3">
                        <p class="text-xs text-muted-foreground"> {{ $t('This prevents browsers from resubmitting the original request method on redirect. Inertia\'s server-side adapter handles this automatically.') }} </p>
                        <div
                            class="rounded-lg border border-dashed p-3 text-xs text-muted-foreground"
                        >
                            <p class="font-medium text-foreground"> {{ $t('How it works:') }} </p>
                            <ol class="mt-2 list-inside list-decimal space-y-1">
                                <li>{{ $t('Client sends PUT/PATCH/DELETE request') }}</li>
                                <li>{{ $t('Server responds with 303 redirect') }}</li>
                                <li> {{ $t('Browser follows redirect with GET request') }} </li>
                                <li>{{ $t('Inertia renders the redirected page') }}</li>
                            </ol>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
