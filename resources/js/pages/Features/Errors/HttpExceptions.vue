<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {

const { t } = useI18n();
    httpException403,
    httpException404,
    httpException500,
    httpExceptionUnhandled,
} from '@/wayfinder/App/Http/Controllers/Feature/NetworkErrorController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Error Handling') },
    { title: t('HTTP Exceptions') },
];

const eventLog = ref<string[]>([]);
const interceptEnabled = ref(false);
let removeListener: (() => void) | null = null;

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 15) eventLog.value.pop();
}

function toggleIntercept() {
    if (interceptEnabled.value) {
        removeListener?.();
        removeListener = null;
        interceptEnabled.value = false;
        log('Global httpException listener removed');
    } else {
        removeListener = router.on('httpException', (event) => {
            event.preventDefault();
            log(
                `Intercepted HTTP ${event.detail.response.status}. Default error modal suppressed.`,
            );
        });
        interceptEnabled.value = true;
        log('Global httpException listener registered');
    }
}

function triggerUnhandledError() {
    router.get(
        httpExceptionUnhandled.url(),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onHttpException: (response) => {
                log(`onHttpException callback fired: HTTP ${response.status}`);
                if (interceptEnabled.value) {
                    return false;
                }
            },
        },
    );
}

onUnmounted(() => {
    removeListener?.();
});
</script>

<template>
    <Head :title="$t('HTTP Exceptions')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('HTTP Exceptions')"
                docs="advanced/error-handling"
                controller="app/Http/Controllers/Feature/NetworkErrorController.php#L10"
            > {{ $t('Errors handled by') }} <code class="text-xs">{{ $t('handleExceptionsUsing()') }}</code> {{ $t('render as full Inertia pages. Unhandled errors can be intercepted client-side with') }} <code class="text-xs">{{ $t('onHttpException') }}</code>.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <FeatureCard
                    :title="$t('Server-Handled Errors')"
                    description="These status codes are handled by handleExceptionsUsing() and render the ErrorPage component. Use your browser's back button to return."
                >
                    <div class="flex flex-wrap gap-2">
                        <Button as-child variant="outline" size="sm">
                            <Link :href="httpException403.url()"> {{ $t('403 Forbidden') }} </Link>
                        </Button>
                        <Button as-child variant="outline" size="sm">
                            <Link :href="httpException404.url()"> {{ $t('404 Not Found') }} </Link>
                        </Button>
                        <Button as-child variant="destructive" size="sm">
                            <Link :href="httpException500.url()"> {{ $t('500 Server Error') }} </Link>
                        </Button>
                    </div>
                </FeatureCard>

                <FeatureCard
                    :title="$t('Client-Side Interception')"
                    :description="$t('Errors NOT handled server-side trigger the httpException event. Enable interception to suppress the default error modal and stay on this page.')"
                >
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <Button
                                :variant="
                                    interceptEnabled ? 'default' : 'outline'
                                "
                                size="sm"
                                @click="toggleIntercept"
                            >
                                {{ interceptEnabled ? $t('Disable') : $t('Enable') }}
                                Interception
                            </Button>
                            <Badge
                                :variant="
                                    interceptEnabled ? 'default' : 'secondary'
                                "
                            >
                                {{ interceptEnabled ? $t('Active') : $t('Inactive') }}
                            </Badge>
                        </div>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="triggerUnhandledError"
                        > {{ $t('418 I\'m a Teapot') }} </Button>
                        <p class="text-xs text-muted-foreground"> {{ $t('Without interception, this shows the default error modal. With interception enabled,') }} <code>{{ $t('event.preventDefault()') }}</code> {{ $t('suppresses it.') }} </p>
                    </div>
                </FeatureCard>

                <FeatureCard info-card class="lg:col-span-2" :title="$t('Event Log')">
                    <template #header-action>
                        <Button variant="ghost" size="sm" @click="eventLog = []"
                            >{{ $t('Clear') }}</Button
                        >
                    </template>
                    <div
                        v-if="eventLog.length"
                        class="max-h-48 space-y-1 overflow-y-auto"
                    >
                        <div
                            v-for="(entry, i) in eventLog"
                            :key="i"
                            class="rounded bg-muted px-2 py-1 font-mono text-xs"
                        >
                            {{ entry }}
                        </div>
                    </div>
                    <p v-else class="text-xs text-muted-foreground"> {{ $t('Enable interception and trigger an error to see events.') }} </p>
                </FeatureCard>

                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('API Reference')"
                >
                    <div class="grid gap-3 sm:grid-cols-2">
                        <CodeBlock
                            :title="$t('Server-side (handled):')"
                            code="Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
  if (in_array($response->statusCode(), [403, 404, 419, 429, 500, 503])) {
    return $response->render('ErrorPage', [
      'status' => $response->statusCode(),
    ])->withSharedData();
  }
});"
                        />
                        <CodeBlock
                            :title="$t('Client-side (intercepted):')"
                            code="router.on('httpException', (event) => {
  console.log(event.detail.response)
  event.preventDefault()
})"
                        />
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
