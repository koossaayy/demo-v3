<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import FormContextField from '@/components/FormContextField.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { submitFormComponent } from '@/wayfinder/App/Http/Controllers/Feature/FormController';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Forms') },
    { title: 'useFormContext' },
];
</script>

<template>
    <Head title="useFormContext" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                title="useFormContext"
                docs="the-basics/forms#form-context"
                controller="app/Http/Controllers/Feature/FormController.php#L90"
            >
                <i18n-t keypath="Access parent {el0} state from deeply nested child components. No prop drilling needed." tag="span" scope="global"><template #el0><code class="text-xs">&lt;Form&gt;</code></template></i18n-t>
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Form with child components -->
                <FeatureCard :title="$t('Parent Form')">
                    <template #description>
                        <i18n-t keypath="The {el0} wraps child {el1} components that use {el2} to access form state." tag="span" scope="global"><template #el0><code class="text-xs">&lt;Form&gt;</code></template><template #el1><code class="text-xs">FormContextField</code></template><template #el2><code class="text-xs">useFormContext()</code></template></i18n-t>
                    </template>

                    <Form
                        v-bind="submitFormComponent.form()"
                        class="space-y-4"
                        #default="{
                            processing,
                            isDirty,
                            hasErrors,
                            recentlySuccessful,
                            validating,
                        }"
                    >
                        <!-- Hidden default role so FeatureFormRequest passes -->
                        <input type="hidden" name="role" value="developer" />

                        <!-- These are child components using useFormContext() -->
                        <FormContextField
                            name="name"
                            :label="$t('Name')"
                            :placeholder="$t('Enter your name...')"
                        />
                        <FormContextField
                            name="email"
                            :label="$t('Email')"
                            type="text"
                            placeholder="you@example.com"
                        />
                        <FormContextField
                            name="bio"
                            :label="$t('Bio')"
                            :placeholder="$t('Tell us about yourself...')"
                        />

                        <div class="flex items-center gap-2 pt-2">
                            <Button type="submit" :disabled="processing">
                                {{ processing ? $t('Submitting...') : $t('Submit') }}
                            </Button>
                            <span
                                v-if="isDirty"
                                class="text-sm text-muted-foreground"
                                >{{ $t('Unsaved changes') }}</span
                            >
                            <span
                                v-if="recentlySuccessful"
                                class="text-sm text-green-600"
                                >{{ $t('Saved!') }}</span
                            >
                        </div>

                        <!-- Parent State Display -->
                        <div
                            class="mt-4 space-y-3 rounded-md border border-black/10 p-4 dark:border-white/10"
                        >
                            <h3 class="text-sm font-semibold"> {{ $t('Parent Slot Props') }} </h3>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span>{{ $t('processing') }}</span>
                                    <Badge
                                        :variant="
                                            processing ? 'default' : 'secondary'
                                        "
                                        class="text-xs"
                                        >{{ processing }}</Badge
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>isDirty</span>
                                    <Badge
                                        :variant="
                                            isDirty ? 'default' : 'secondary'
                                        "
                                        class="text-xs"
                                        >{{ isDirty }}</Badge
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>hasErrors</span>
                                    <Badge
                                        :variant="
                                            hasErrors
                                                ? 'destructive'
                                                : 'secondary'
                                        "
                                        class="text-xs"
                                        >{{ hasErrors }}</Badge
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>{{ $t('validating') }}</span>
                                    <Badge
                                        :variant="
                                            validating ? 'default' : 'secondary'
                                        "
                                        class="text-xs"
                                        >{{ validating }}</Badge
                                    >
                                </div>
                            </div>
                        </div>
                    </Form>
                </FeatureCard>

                <!-- How It Works -->
                <div class="space-y-6">
                    <FeatureCard info-card :title="$t('How It Works')">
                        <div class="space-y-3 text-sm text-muted-foreground">
                            <p>
                                <i18n-t keypath="{el0} The parent renders a {el1} component." tag="span" scope="global"><template #el0><strong>1.</strong></template><template #el1><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >&lt;Form&gt;</code
                                ></template></i18n-t>
                            </p>
                            <p>
                                <i18n-t keypath="{el0} Child components call {el1} to access the same form state." tag="span" scope="global"><template #el0><strong>2.</strong></template><template #el1><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >useFormContext()</code
                                ></template></i18n-t>
                            </p>
                            <p>
                                <i18n-t keypath="{el0} No props need to be passed. Context is provided automatically via Vue's provide/inject." tag="span" scope="global"><template #el0><strong>3.</strong></template></i18n-t>
                            </p>
                            <p>
                                <i18n-t keypath="{el0} Children can read {el1}, {el2}, {el3}, and call {el4} / {el5}." tag="span" scope="global"><template #el0><strong>4.</strong></template><template #el1><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >errors</code
                                ></template><template #el2><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >processing</code
                                ></template><template #el3><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >isDirty</code
                                ></template><template #el4><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >validate()</code
                                ></template><template #el5><code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >touch()</code
                                ></template></i18n-t>
                            </p>
                        </div>
                    </FeatureCard>

                    <FeatureCard info-card :title="$t('API Reference')">
                        <div class="space-y-3">
                            <CodeBlock
                                :title="$t('Child Component')"
                                code="import { useFormContext } from '@inertiajs/vue3'

const form = useFormContext()
// form.errors, form.processing, form.isDirty
// form.validate('field'), form.touch('field')"
                            />
                            <CodeBlock :title="$t('Returns null outside Form')">
                                <textarea>
const form = useFormContext()
// Returns null if not inside a <Form>
                                </textarea>
                            </CodeBlock>
                        </div>
                    </FeatureCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
