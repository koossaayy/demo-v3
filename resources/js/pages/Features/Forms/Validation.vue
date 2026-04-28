<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
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
import {
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
    submitValidation,
    submitValidationSecondary,
} from '@/wayfinder/App/Http/Controllers/Feature/FormController';

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Forms') },
    { title: t('Validation') },
];

// Primary form
const primaryForm = useForm({
    name: '',
    email: '',
    age: '',
    website: '',
});

function submitPrimary() {
    primaryForm.submit(submitValidation(), {
        preserveScroll: true,
    });
}

// Secondary form with error bag
const secondaryForm = useForm({
    title: '',
    body: '',
});

function submitSecondary() {
    secondaryForm.submit(submitValidationSecondary(), {
        preserveScroll: true,
        errorBag: 'secondaryForm',
    });
}
</script>

<template>
    <Head :title="$t('Validation')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Validation')"
                docs="the-basics/validation"
                controller="app/Http/Controllers/Feature/FormController.php#L56"
            > {{ $t('Error handling patterns. Server-side errors, error bags, and manual error management.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Primary Form -->
                <FeatureCard
                    :title="$t('Server-Side Validation')"
                    :description="$t('Submit an empty form to see server validation errors with custom messages.')"
                >
                    <form class="space-y-4" @submit.prevent="submitPrimary">
                        <div class="space-y-2">
                            <Label for="v-name">{{ $t('Name') }}</Label>
                            <Input id="v-name" v-model="primaryForm.name" />
                            <InputError :message="primaryForm.errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="v-email">{{ $t('Email') }}</Label>
                            <Input
                                id="v-email"
                                type="text"
                                v-model="primaryForm.email"
                            />
                            <InputError :message="primaryForm.errors.email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="v-age">{{ $t('Age') }}</Label>
                            <Input
                                id="v-age"
                                type="number"
                                v-model="primaryForm.age"
                            />
                            <InputError :message="primaryForm.errors.age" />
                        </div>

                        <div class="space-y-2">
                            <Label for="v-website">{{ $t('Website') }}</Label>
                            <Input
                                id="v-website"
                                v-model="primaryForm.website"
                                placeholder="https://example.com"
                            />
                            <InputError :message="primaryForm.errors.website" />
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <Button
                                type="submit"
                                :disabled="primaryForm.processing"
                            >
                                {{
                                    primaryForm.processing
                                        ? $t('Validating...')
                                        : $t('Submit')
                                }}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                @click="primaryForm.clearErrors()"
                            > {{ $t('clearErrors()') }} </Button>
                        </div>
                    </form>

                    <!-- Error display -->
                    <div v-if="primaryForm.hasErrors" class="mt-4 space-y-2">
                        <h4 class="text-sm font-semibold">{{ $t('form.errors') }}</h4>
                        <CodeBlock
                            :code="JSON.stringify(primaryForm.errors, null, 2)"
                        />
                    </div>
                </FeatureCard>

                <!-- Secondary Form with Error Bag -->
                <FeatureCard :title="$t('Error Bags')">
                    <template #description> {{ $t('When multiple forms share field names, validation errors can bleed between them. The') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                            >{{ $t('errorBag') }}</code
                        > {{ $t('option scopes errors under a unique key (') }}<code
                            class="rounded bg-muted px-1 py-0.5 text-xs"
                            >{{ $t('page.props.errors.secondaryForm') }}</code
                        >{{ $t(') so each form only displays its own errors. If the backend uses') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                            >{{ $t('validateWithBag()') }}</code
                        >{{ $t(', you must specify a matching') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                            >{{ $t('errorBag') }}</code
                        > {{ $t('on the frontend so Inertia knows where to find the errors.') }} </template>

                    <form class="space-y-4" @submit.prevent="submitSecondary">
                        <div class="space-y-2">
                            <Label for="v2-title">{{ $t('Title') }}</Label>
                            <Input
                                id="v2-title"
                                v-model="secondaryForm.title"
                            />
                            <InputError :message="secondaryForm.errors.title" />
                        </div>

                        <div class="space-y-2">
                            <Label for="v2-body"
                                >{{ $t('Body (min 10 characters)') }}</Label
                            >
                            <textarea
                                id="v2-body"
                                v-model="secondaryForm.body"
                                rows="3"
                                class="flex w-full rounded-md border border-input/60 bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                            />
                            <InputError :message="secondaryForm.errors.body" />
                        </div>

                        <Button
                            type="submit"
                            :disabled="secondaryForm.processing"
                        >
                            {{
                                secondaryForm.processing
                                    ? $t('Validating...')
                                    : $t('Submit (Error Bag)')
                            }}
                        </Button>
                    </form>

                    <!-- Error display -->
                    <div v-if="secondaryForm.hasErrors" class="mt-4 space-y-2">
                        <h4 class="text-sm font-semibold"> {{ $t('secondaryForm.errors') }} </h4>
                        <CodeBlock
                            :code="
                                JSON.stringify(secondaryForm.errors, null, 2)
                            "
                        />
                    </div>
                </FeatureCard>

                <!-- Manual Error Management -->
                <FeatureCard
                    info-card
                    class="lg:col-span-2"
                    :title="$t('Manual Error Management')"
                    :description="$t('Use setError() and clearErrors() to manage errors programmatically.')"
                >
                    <div class="flex flex-wrap gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                primaryForm.setError(
                                    'name',
                                    'This name is already taken',
                                )
                            "
                        > {{ $t('setError(\'name\', ...)') }} </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="
                                primaryForm.setError({
                                    email: 'Email is blocked',
                                    website: 'Domain not allowed',
                                })
                            "
                        > {{ $t('setError({ email, website })') }} </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="primaryForm.clearErrors('name')"
                        > {{ $t('clearErrors(\'name\')') }} </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="primaryForm.clearErrors('email', 'website')"
                        > {{ $t('clearErrors(\'email\', \'website\')') }} </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="primaryForm.clearErrors()"
                        > {{ $t('clearErrors() (all)') }} </Button>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <h4 class="mb-2 text-sm font-semibold"> {{ $t('Primary Form State') }} </h4>
                            <div class="space-y-2">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span>{{ $t('hasErrors') }}</span>
                                    <Badge
                                        :variant="
                                            primaryForm.hasErrors
                                                ? 'destructive'
                                                : 'secondary'
                                        "
                                        >{{ primaryForm.hasErrors }}</Badge
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span>{{ $t('isDirty') }}</span>
                                    <Badge
                                        :variant="
                                            primaryForm.isDirty
                                                ? 'default'
                                                : 'secondary'
                                        "
                                        >{{ primaryForm.isDirty }}</Badge
                                    >
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-2 text-sm font-semibold"> {{ $t('Secondary Form State') }} </h4>
                            <div class="space-y-2">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span>{{ $t('hasErrors') }}</span>
                                    <Badge
                                        :variant="
                                            secondaryForm.hasErrors
                                                ? 'destructive'
                                                : 'secondary'
                                        "
                                        >{{ secondaryForm.hasErrors }}</Badge
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span>{{ $t('isDirty') }}</span>
                                    <Badge
                                        :variant="
                                            secondaryForm.isDirty
                                                ? 'default'
                                                : 'secondary'
                                        "
                                        >{{ secondaryForm.isDirty }}</Badge
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </FeatureCard>
            </div>
        </div>
    </AppLayout>
</template>
