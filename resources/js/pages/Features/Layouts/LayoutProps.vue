<script setup lang="ts">
import { Head, resetLayoutProps, setLayoutProps } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';

const { t } = useI18n();

defineOptions({
    layout: [
        AppLayout,
        {
            breadcrumbs: [
                { title: t('Layouts & Head') },
                { title: t('Layout Props') },
            ],
        },
    ],
});

const subtitle = ref('');

function apply() {
    if (subtitle.value) {
        setLayoutProps({ subtitle: subtitle.value });
    }
}

function reset() {
    subtitle.value = '';
    resetLayoutProps();
}
</script>

<template>
    <Head :title="$t('Layout Props')" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <FeatureHeader
            :title="$t('Layout Props')"
            docs="the-basics/layouts#layout-props"
            controller="app/Http/Controllers/Feature/LayoutController.php#L30"
        > {{ $t('Pass data from pages to layouts with') }} <code class="text-xs">setLayoutProps()</code> {{ $t('and regular component props.') }} </FeatureHeader>

        <div class="grid gap-6 lg:grid-cols-2">
            <FeatureCard
                title="setLayoutProps()"
                :description="$t('Type a subtitle and click Apply. A banner appears below the breadcrumbs.')"
            >
                <div class="space-y-4">
                    <div class="space-y-2">
                        <Label :for="$t('subtitle')">{{ $t('Subtitle') }}</Label>
                        <div class="flex gap-2">
                            <Input
                                :id="$t('subtitle')"
                                v-model="subtitle"
                                :placeholder="$t('Enter a subtitle...')"
                                @keyup.enter="apply"
                            />
                            <Button @click="apply">{{ $t('Apply') }}</Button>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                subtitle = 'Welcome back!';
                                apply();
                            "
                        > {{ $t('"Welcome back!"') }} </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                subtitle = 'Maintenance scheduled tonight';
                                apply();
                            "
                        > {{ $t('"Maintenance..."') }} </Button>
                        <Button variant="outline" size="sm" @click="reset"> {{ $t('Reset') }} </Button>
                    </div>

                    <p class="text-xs text-muted-foreground"> {{ $t('The layout declares a') }} <code>{{ $t('subtitle') }}</code> {{ $t('prop with a default value. Pages override it with') }} <code>setLayoutProps()</code>{{ $t('. Dynamic props auto-reset on navigation.') }} </p>
                </div>
            </FeatureCard>

            <!-- API Reference -->
            <FeatureCard
                info-card
                :title="$t('API Reference')"
                :description="$t('The complete layout props API.')"
            >
                <div class="space-y-3">
                    <div class="space-y-2">
                        <CodeBlock
                            :title="$t('In Layout:')"
                            code="defineProps({
  subtitle: {
    type: String,
    default: '',
  },
})"
                        />
                        <CodeBlock
                            :title="$t('In Page:')"
                            code="setLayoutProps({
  subtitle: 'Custom subtitle',
})"
                        />
                        <CodeBlock :title="$t('Reset:')" code="resetLayoutProps()" />
                    </div>
                </div>
            </FeatureCard>
        </div>
    </div>
</template>
