<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { submitFormComponent } from '@/wayfinder/App/Http/Controllers/Feature/FormController';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Forms') },
    { title: t('Form Component') },
];

const formRef = ref<any>(null);

const resetOnSuccess = ref(false);
const setDefaultsOnSuccess = ref(true);
</script>

<template>
    <Head :title="$t('Form Component')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Form Component')"
                docs="the-basics/forms#form-component"
                controller="app/Http/Controllers/Feature/FormController.php#L32"
            >
                Template-based &lt;Form&gt; component with slot props. No
                useForm composable needed.
            </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Form -->
                <FeatureCard :title="$t('Demo Form')">
                    <template #description>
                        Uses the &lt;Form&gt; component with native HTML inputs
                        and slot props.
                    </template>

                    <!-- Component Props Toggle -->
                    <div
                        class="mb-4 flex flex-wrap gap-4 rounded-md bg-muted p-3"
                    >
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                v-model="resetOnSuccess"
                                class="size-4 rounded border"
                            /> {{ $t('resetOnSuccess') }} </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                v-model="setDefaultsOnSuccess"
                                class="size-4 rounded border"
                            /> {{ $t('setDefaultsOnSuccess') }} </label>
                    </div>

                    <Form
                        ref="formRef"
                        v-bind="submitFormComponent.form()"
                        :reset-on-success="resetOnSuccess"
                        :set-defaults-on-success="setDefaultsOnSuccess"
                        class="space-y-4"
                        #default="{
                            errors,
                            processing,
                            isDirty,
                            recentlySuccessful,
                            progress,
                            submit,
                            reset,
                            clearErrors,
                            setError,
                        }"
                    >
                        <div class="space-y-2">
                            <Label for="fc-name">{{ $t('Name') }}</Label>
                            <Input id="fc-name" name="name" />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="fc-email">{{ $t('Email') }}</Label>
                            <Input id="fc-email" type="text" name="email" />
                            <InputError :message="errors.email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="fc-bio">{{ $t('Bio') }}</Label>
                            <textarea
                                id="fc-bio"
                                name="bio"
                                rows="3"
                                class="flex w-full rounded-md border border-input/60 bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                                :placeholder="$t('Tell us about yourself...')"
                            />
                            <InputError :message="errors.bio" />
                        </div>

                        <div class="space-y-2">
                            <Label for="fc-role">{{ $t('Role') }}</Label>
                            <select
                                id="fc-role"
                                name="role"
                                class="flex h-9 w-full rounded-md border border-input/60 bg-background px-3 py-1 text-sm focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                            >
                                <option value="developer">{{ $t('Developer') }}</option>
                                <option value="designer">{{ $t('Designer') }}</option>
                                <option value="manager">{{ $t('Manager') }}</option>
                                <option value="other">{{ $t('Other') }}</option>
                            </select>
                            <InputError :message="errors.role" />
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="fc-subscribe"
                                type="checkbox"
                                name="subscribe"
                                value="1"
                                class="size-4 rounded border"
                            />
                            <Label for="fc-subscribe"
                                >{{ $t('Subscribe to newsletter') }}</Label
                            >
                        </div>

                        <!-- Progress -->
                        <div
                            v-if="progress"
                            class="w-full rounded-full bg-secondary"
                        >
                            <div
                                class="h-2 rounded-full bg-primary transition-all"
                                :style="{ width: `${progress.percentage}%` }"
                            />
                        </div>

                        <!-- Submit -->
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

                        <!-- Slot Methods -->
                        <div
                            class="space-y-3 rounded-md border border-black/10 p-4 dark:border-white/10"
                        >
                            <h3 class="text-sm font-semibold">{{ $t('Slot Methods') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="submit"
                                    >{{ $t('submit()') }}</Button
                                >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="reset()"
                                    >{{ $t('reset()') }}</Button
                                >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="clearErrors()"
                                    >{{ $t('clearErrors()') }}</Button
                                >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        setError(
                                            'name',
                                            'Manual error via setError()',
                                        )
                                    "
                                    >{{ $t('setError()') }}</Button
                                >
                            </div>
                        </div>
                    </Form>
                </FeatureCard>

                <div class="flex flex-col gap-6">
                    <!-- Reactive State -->
                    <FeatureCard
                        info-card
                        :title="$t('Slot Props (Reactive State)')"
                        :description="$t('Live state from the Form component, read via template ref.')"
                    >
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span>{{ $t('processing') }}</span>
                                <Badge
                                    :variant="
                                        formRef?.processing
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                    >{{ formRef?.processing ?? false }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ $t('isDirty') }}</span>
                                <Badge
                                    :variant="
                                        formRef?.isDirty
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                    >{{ formRef?.isDirty ?? false }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ $t('hasErrors') }}</span>
                                <Badge
                                    :variant="
                                        formRef?.hasErrors
                                            ? 'destructive'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                    >{{ formRef?.hasErrors ?? false }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ $t('wasSuccessful') }}</span>
                                <Badge
                                    :variant="
                                        formRef?.wasSuccessful
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                    >{{
                                        formRef?.wasSuccessful ?? false
                                    }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ $t('recentlySuccessful') }}</span>
                                <Badge
                                    :variant="
                                        formRef?.recentlySuccessful
                                            ? 'default'
                                            : 'secondary'
                                    "
                                    class="text-xs"
                                    >{{
                                        formRef?.recentlySuccessful ?? false
                                    }}</Badge
                                >
                            </div>
                            <div class="flex items-center justify-between">
                                <span>{{ $t('progress') }}</span>
                                <Badge variant="secondary" class="text-xs">{{
                                    formRef?.progress
                                        ? `${formRef.progress.percentage}%`
                                        : $t('null')
                                }}</Badge>
                            </div>
                        </div>
                    </FeatureCard>

                    <!-- Ref Methods -->
                    <FeatureCard
                        info-card
                        :title="$t('Template Ref Methods')"
                        :description="$t('Control the form from outside using a template ref.')"
                    >
                        <div class="space-y-4">
                            <CodeBlock>
                                <textarea>
                                    import { Form } from '@inertiajs/vue3'
                                    import { ref } from 'vue'

                                    const formRef = ref(null)

                                    // Use in template: <Form ref="formRef" ...>
                                    // Then call methods from anywhere:
                                    formRef.value?.submit()
                                    formRef.value?.reset()
                                    formRef.value?.clearErrors()
                                </textarea>
                            </CodeBlock>

                            <p class="text-sm text-muted-foreground"> {{ $t('These buttons call methods via') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t('formRef.value?.method()') }}</code
                                >
                                from outside the &lt;Form&gt; slot scope.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="formRef?.submit()"
                                    >{{ $t('ref.submit()') }}</Button
                                >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="formRef?.reset()"
                                    >{{ $t('ref.reset()') }}</Button
                                >
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="formRef?.clearErrors()"
                                    >{{ $t('ref.clearErrors()') }}</Button
                                >
                            </div>
                        </div>
                    </FeatureCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
