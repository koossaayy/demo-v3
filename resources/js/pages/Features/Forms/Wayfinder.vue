<script setup lang="ts">
import { Form, Head, Link, router, useForm } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    submitUseForm,
    submitFormComponent,
    toggleFavorite,
    wayfinder as wayfinderAction,
    storeAccount,
} from '@/wayfinder/App/Http/Controllers/Feature/FormController';
import contacts from '@/wayfinder/routes/contacts';
import type { Inertia } from '@/wayfinder/types';

defineProps<Inertia.Pages.Features.Forms.Wayfinder>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forms' },
    { title: 'Wayfinder' },
];

// Hardcoded useForm
const hardcodedForm = useForm('wayfinder-hardcoded', {
    name: '',
    email: '',
});

// Controller action useForm
const actionForm = useForm('wayfinder-action', {
    name: '',
    email: '',
});

// Named route useForm
const namedForm = useForm('wayfinder-named', {
    name: '',
    email: '',
});

// Precognition useForm
const precogForm = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
}).withPrecognition(storeAccount());
</script>

<template>
    <Head :title="$t('Wayfinder')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Wayfinder')"
                docs="the-basics/routing#generating-urls"
                controller="app/Http/Controllers/Feature/FormController.php#L109"
            > {{ $t('Wayfinder generates type-safe TypeScript functions from your Laravel routes. Each section compares hardcoded URLs with Wayfinder equivalents across Inertia\'s APIs.') }} </FeatureHeader>

            <div class="space-y-10">
                <!-- ============================================ -->
                <!-- SECTION: Hardcoded URLs (Baseline)           -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Hardcoded URLs') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('The traditional approach. Works, but fragile when routes change.') }} </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <FeatureCard :title="$t('useForm')">
                            <CodeBlock
                                code="form.submit('post', '/features/forms/use-form')"
                            />

                            <form
                                class="mt-3 space-y-3"
                                @submit.prevent="
                                    hardcodedForm.submit(
                                        'post',
                                        '/features/forms/use-form',
                                        {
                                            preserveScroll: true,
                                            onSuccess: () =>
                                                hardcodedForm.defaults(),
                                        },
                                    )
                                "
                            >
                                <div class="space-y-1">
                                    <Label for="hc-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="hc-name"
                                        v-model="hardcodedForm.name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError
                                        :message="hardcodedForm.errors.name"
                                    />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="hardcodedForm.processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="hardcodedForm.recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </form>
                        </FeatureCard>

                        <FeatureCard :title="$t('<Form>')">
                            <CodeBlock>
                                <textarea>
                                <Form action="/features/forms/form-component"
                                      method="post">
                                </textarea>
                            </CodeBlock>

                            <Form
                                action="/features/forms/form-component"
                                :method="$t('post')"
                                preserve-scroll
                                set-defaults-on-success
                                class="mt-3 space-y-3"
                                #default="{
                                    errors,
                                    processing,
                                    recentlySuccessful,
                                }"
                            >
                                <div class="space-y-1">
                                    <Label for="hc-fc-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="hc-fc-name"
                                        name="name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError :message="errors.name" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </Form>
                        </FeatureCard>

                        <FeatureCard :title="$t('router / <Link>')">
                            <CodeBlock>
                                <textarea>
                                router.visit('/features/forms/wayfinder')
                                router.post('/features/forms/optimistic-toggle/1', data)

                                <Link href="/contacts">
                                </textarea>
                            </CodeBlock>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        router.visit(
                                            '/features/forms/wayfinder',
                                            { preserveScroll: true },
                                        )
                                    "
                                > {{ $t('router.visit()') }} </Button>
                                <Button variant="outline" size="sm" as-child>
                                    <Link href="/contacts">{{ $t('Contacts') }}</Link>
                                </Button>
                            </div>
                        </FeatureCard>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SECTION: Controller Action Imports            -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Controller Actions') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('Import by controller method. Keeps frontend in sync with your PHP controllers.') }} </p>
                        <CodeBlock class="mt-2">
                            <textarea>                                {{ $t('import { submitUseForm, toggleFavorite } from \'@/wayfinder/.../FormController\' submitUseForm() // {"url":"/features/forms/use-form","method":"post"} submitUseForm.url() // "/features/forms/use-form" toggleFavorite(1) // {"url":"/features/forms/optimistic-toggle/1","method":"post"} toggleFavorite.url(1) // "/features/forms/optimistic-toggle/1"') }}
                            </textarea>
                        </CodeBlock>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <FeatureCard
                            :title="$t('useForm')"
                            :description="$t('Pass the action directly to submit(). Method and URL are extracted automatically.')"
                        >
                            <CodeBlock code="form.submit(submitUseForm())" />

                            <form
                                class="mt-3 space-y-3"
                                @submit.prevent="
                                    actionForm.submit(submitUseForm(), {
                                        preserveScroll: true,
                                        onSuccess: () => actionForm.defaults(),
                                    })
                                "
                            >
                                <div class="space-y-1">
                                    <Label for="ac-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="ac-name"
                                        v-model="actionForm.name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError
                                        :message="actionForm.errors.name"
                                    />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="actionForm.processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="actionForm.recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </form>
                        </FeatureCard>

                        <FeatureCard
                            :title="$t('<Form>')"
                            :description="$t('The :action prop accepts the { url, method } object directly.')"
                        >
                            <CodeBlock
                                code='<Form :action="submitFormComponent()">'
                            />

                            <Form
                                :action="submitFormComponent()"
                                preserve-scroll
                                set-defaults-on-success
                                class="mt-3 space-y-3"
                                #default="{
                                    errors,
                                    processing,
                                    recentlySuccessful,
                                }"
                            >
                                <div class="space-y-1">
                                    <Label for="ac-fc-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="ac-fc-name"
                                        name="name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError :message="errors.name" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </Form>
                        </FeatureCard>

                        <FeatureCard
                            :title="$t('router / <Link>')"
                            :description="$t('router and Link both accept { url, method } objects.')"
                        >
                            <CodeBlock>
                                <textarea>
                                // Extracts method + URL from action
                                router.visit(wayfinderAction())
                                router.visit(toggleFavorite(contact))

                                // Or use .url for string-only APIs
                                router.post(toggleFavorite.url(contact))

                                // Link also accepts object or string
                                <Link :href="contacts.show(id)">
                                <Link :href="contacts.index.url()">
                                </textarea>
                            </CodeBlock>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        router.visit(wayfinderAction(), {
                                            preserveScroll: true,
                                        })
                                    "
                                > {{ $t('router.visit()') }} </Button>
                                <Button
                                    v-if="sampleContact"
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        router.visit(
                                            toggleFavorite(sampleContact),
                                            { preserveScroll: true },
                                        )
                                    "
                                > {{ $t('Toggle favorite') }} </Button>
                                <Button
                                    v-if="sampleContact"
                                    variant="outline"
                                    size="sm"
                                    as-child
                                >
                                    <Link
                                        :href="contacts.show(sampleContact.id)"
                                    >
                                        {{ sampleContact.first_name }}
                                    </Link>
                                </Button>
                            </div>
                        </FeatureCard>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SECTION: Named Route Imports                  -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Named Routes') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('Import by route name. Mirrors your Laravel route names exactly.') }} </p>
                        <CodeBlock class="mt-2">
                            <textarea>                                {{ $t('import contacts from \'@/wayfinder/routes/contacts\' contacts.index() // {"url":"/contacts","method":"get"} contacts.show(1) // {"url":"/contacts/1","method":"get"} contacts.show.url(1) // "/contacts/1"') }}
                            </textarea>
                        </CodeBlock>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <FeatureCard
                            :title="$t('useForm')"
                            :description="$t('Named route actions work exactly the same as controller actions.')"
                        >
                            <CodeBlock code="form.submit(submitUseForm())" />

                            <form
                                class="mt-3 space-y-3"
                                @submit.prevent="
                                    namedForm.submit(submitUseForm(), {
                                        preserveScroll: true,
                                        onSuccess: () => namedForm.defaults(),
                                    })
                                "
                            >
                                <div class="space-y-1">
                                    <Label for="nr-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="nr-name"
                                        v-model="namedForm.name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError
                                        :message="namedForm.errors.name"
                                    />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="namedForm.processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="namedForm.recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </form>
                        </FeatureCard>

                        <FeatureCard
                            :title="$t('<Form>')"
                            :description="$t('Pass the named route action directly to :action.')"
                        >
                            <CodeBlock
                                code='<Form :action="submitFormComponent()">'
                            />

                            <Form
                                :action="submitFormComponent()"
                                preserve-scroll
                                set-defaults-on-success
                                class="mt-3 space-y-3"
                                #default="{
                                    errors,
                                    processing,
                                    recentlySuccessful,
                                }"
                            >
                                <div class="space-y-1">
                                    <Label for="nr-fc-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="nr-fc-name"
                                        name="name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError :message="errors.name" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </Form>
                        </FeatureCard>

                        <FeatureCard
                            :title="$t('router / <Link>')"
                            :description="$t('Use named routes for navigation and programmatic visits.')"
                        >
                            <CodeBlock>
                                <textarea>
                                router.visit(contacts.index())
                                router.visit(contacts.destroy(id))
                                router.prefetch(contacts.show(id))

                                <Link :href="contacts.create()">
                                </textarea>
                            </CodeBlock>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <Button variant="outline" size="sm" as-child>
                                    <Link :href="contacts.index()"
                                        >{{ $t('All contacts') }}</Link
                                    >
                                </Button>
                                <Button variant="outline" size="sm" as-child>
                                    <Link :href="contacts.create()"
                                        >{{ $t('Create contact') }}</Link
                                    >
                                </Button>
                                <Button
                                    v-if="sampleContact"
                                    variant="outline"
                                    size="sm"
                                    as-child
                                >
                                    <Link
                                        :href="contacts.edit(sampleContact.id)"
                                    >
                                        Edit {{ sampleContact.first_name }}
                                    </Link>
                                </Button>
                            </div>
                        </FeatureCard>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SECTION: Conventional Forms with .form()      -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Conventional Forms') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('The') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('.form()') }}</code
                            > {{ $t('method returns') }} <code
                                class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ '{ action, method }' }}</code
                            > {{ $t('for use with') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('v-bind') }}</code
                            > {{ $t('on the') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('<Form>') }}</code
                            > {{ $t('component.') }} </p>
                        <CodeBlock class="mt-2">
                            <textarea>                                {{ $t('import { submitFormComponent, toggleFavorite } from \'@/wayfinder/.../FormController\' submitFormComponent.form() // {"action":"/features/forms/form-component","method":"post"} toggleFavorite.form(1) // {"action":"/features/forms/optimistic-toggle/1","method":"post"}') }}
                            </textarea>
                        </CodeBlock>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <FeatureCard :title="$t('v-bind with .form()')">
                            <CodeBlock
                                code='<Form v-bind="submitFormComponent.form()">'
                            />

                            <Form
                                v-bind="submitFormComponent.form()"
                                preserve-scroll
                                set-defaults-on-success
                                class="mt-3 space-y-3"
                                #default="{
                                    errors,
                                    processing,
                                    recentlySuccessful,
                                }"
                            >
                                <div class="space-y-1">
                                    <Label for="cf-name">{{ $t('Name') }}</Label>
                                    <Input
                                        id="cf-name"
                                        name="name"
                                        :placeholder="$t('Name...')"
                                    />
                                    <InputError :message="errors.name" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="processing"
                                        >{{ $t('Submit') }}</Button
                                    >
                                    <span
                                        v-if="recentlySuccessful"
                                        class="text-sm text-green-600"
                                        >{{ $t('Saved!') }}</span
                                    >
                                </div>
                            </Form>
                        </FeatureCard>

                        <FeatureCard :title="$t('With route parameters')">
                            <CodeBlock>
                                <textarea>                                {{ $t('// Positional toggleFavorite.form(1) // {"action":"/features/forms/optimistic-toggle/1","method":"post"} // Named toggleFavorite.form({ contact: 1 }) // {"action":"/features/forms/optimistic-toggle/1","method":"post"} // Model object toggleFavorite.form({ id: 1 }) // {"action":"/features/forms/optimistic-toggle/1","method":"post"}') }}
                                </textarea>
                            </CodeBlock>
                        </FeatureCard>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SECTION: Query Parameters                     -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Query Parameters') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('Append or merge query params on any Wayfinder route.') }} </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <FeatureCard
                            :title="$t('query')"
                            :description="$t('Sets query parameters on the generated URL.')"
                        >
                            <CodeBlock>
                                <textarea>                                {{ $t('contacts.index({ query: { search: \'Jane\', page: 2 } }) // {"url":"/contacts?search=Jane&page=2","method":"get"} toggleFavorite(1, { query: { notify: true } }) // {"url":"/features/forms/optimistic-toggle/1?notify=true","method":"post"}') }}
                                </textarea>
                            </CodeBlock>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <Button variant="outline" size="sm" as-child>
                                    <Link
                                        :href="
                                            contacts.index({
                                                query: { search: 'Jane' },
                                            })
                                        "
                                    > {{ $t('Search "Jane"') }} </Link>
                                </Button>
                                <Button variant="outline" size="sm" as-child>
                                    <Link
                                        :href="
                                            contacts.index({
                                                query: { favorite: true },
                                            })
                                        "
                                    > {{ $t('Favorites only') }} </Link>
                                </Button>
                            </div>
                        </FeatureCard>

                        <FeatureCard
                            :title="$t('mergeQuery')"
                            :description="$t('Merges with current URL params. Pass null to remove a param.')"
                        >
                            <CodeBlock>
                                <textarea>                                {{ $t('// Adds page=2 to current URL params contacts.index({ mergeQuery: { page: 2 } }) // {"url":"/contacts?page=2","method":"get"} // Removes sort from current URL params contacts.index({ mergeQuery: { sort: null } }) // {"url":"/contacts","method":"get"}') }}
                                </textarea>
                            </CodeBlock>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        router.visit(
                                            contacts.index({
                                                mergeQuery: { page: 2 },
                                            }),
                                            {
                                                preserveState: true,
                                                preserveScroll: true,
                                            },
                                        )
                                    "
                                > {{ $t('Merge page=2') }} </Button>
                            </div>
                        </FeatureCard>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SECTION: Precognition                         -->
                <!-- ============================================ -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight"> {{ $t('Precognition') }} </h2>
                        <p class="text-sm text-muted-foreground"> {{ $t('Real-time server-side validation. Pass the Wayfinder URL to') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('useForm') }}</code
                            > {{ $t('or the') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('<Form>') }}</code
                            > {{ $t('component. The route must use the') }} <code class="rounded bg-muted px-1 py-0.5 text-xs"
                                >{{ $t('precognitive') }}</code
                            > {{ $t('middleware.') }} </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <FeatureCard :title="$t('useForm + Precognition')">
                            <template #description> {{ $t('Chain') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t('.withPrecognition()') }}</code
                                > {{ $t('on') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t('useForm') }}</code
                                > {{ $t('and pass a Wayfinder route directly.') }} </template>
                            <CodeBlock>
                                <textarea>                                {{ $t('const form = useForm({ username: \'\', email: \'\', ... }).withPrecognition(storeAccount()) // Validate on field change @change="form.validate(\'username\')" // Check field status form.valid(\'username\') form.invalid(\'username\')') }}
                                </textarea>
                            </CodeBlock>

                            <form
                                class="mt-3 space-y-3"
                                @submit.prevent="precogForm.submit()"
                            >
                                <div class="space-y-1">
                                    <Label for="pc-username">{{ $t('Username') }}</Label>
                                    <Input
                                        id="pc-username"
                                        v-model="precogForm.username"
                                        @change="
                                            precogForm.validate('username')
                                        "
                                        :class="{
                                            'border-green-500':
                                                precogForm.valid('username'),
                                            'border-red-500':
                                                precogForm.invalid('username'),
                                        }"
                                        :placeholder="$t('3-20 chars, letters/numbers/dashes')"
                                    />
                                    <p
                                        v-if="precogForm.invalid('username')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ precogForm.errors.username }}
                                    </p>
                                    <p
                                        v-else-if="precogForm.valid('username')"
                                        class="text-sm text-green-600"
                                    > {{ $t('Available!') }} </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pc-email">{{ $t('Email') }}</Label>
                                    <Input
                                        id="pc-email"
                                        v-model="precogForm.email"
                                        type="text"
                                        @change="precogForm.validate('email')"
                                        :class="{
                                            'border-green-500':
                                                precogForm.valid('email'),
                                            'border-red-500':
                                                precogForm.invalid('email'),
                                        }"
                                        placeholder="user@example.com"
                                    />
                                    <p
                                        v-if="precogForm.invalid('email')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ precogForm.errors.email }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pc-password">{{ $t('Password') }}</Label>
                                    <Input
                                        id="pc-password"
                                        v-model="precogForm.password"
                                        type="password"
                                        @change="
                                            precogForm.validate('password')
                                        "
                                        :placeholder="$t('Min 8 characters')"
                                    />
                                    <p
                                        v-if="precogForm.invalid('password')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ precogForm.errors.password }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pc-confirm"
                                        >{{ $t('Confirm Password') }}</Label
                                    >
                                    <Input
                                        id="pc-confirm"
                                        v-model="
                                            precogForm.password_confirmation
                                        "
                                        type="password"
                                        @change="
                                            precogForm.validate(
                                                'password_confirmation',
                                            )
                                        "
                                    />
                                    <p
                                        v-if="
                                            precogForm.invalid(
                                                'password_confirmation',
                                            )
                                        "
                                        class="text-sm text-red-600"
                                    >
                                        {{
                                            precogForm.errors
                                                .password_confirmation
                                        }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="
                                            precogForm.processing ||
                                            precogForm.validating ||
                                            precogForm.hasErrors
                                        "
                                    >
                                        {{
                                            precogForm.processing
                                                ? $t('Creating...')
                                                : precogForm.validating
                                                  ? $t('Validating...')
                                                  : $t('Create Account')
                                        }}
                                    </Button>
                                </div>
                            </form>
                        </FeatureCard>

                        <FeatureCard :title="$t('<Form> + Precognition')">
                            <template #description> {{ $t('The') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t('<Form>') }}</code
                                > {{ $t('component has built-in precognition support. Use') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t('.form()') }}</code
                                > {{ $t('or pass the action object, then add') }} <code
                                    class="rounded bg-muted px-1 py-0.5 text-xs"
                                    >{{ $t(':validation-timeout') }}</code
                                > {{ $t('to enable it.') }} </template>
                            <CodeBlock>
                                <textarea>
                                <Form v-bind="storeAccount.form()"
                                      :validation-timeout="500"
                                      #default="{ validate, valid, invalid }">
                                </textarea>
                            </CodeBlock>

                            <Form
                                v-bind="storeAccount.form()"
                                :validation-timeout="500"
                                class="mt-3 space-y-3"
                                #default="{
                                    errors,
                                    processing,
                                    validating,
                                    validate,
                                    valid,
                                    invalid,
                                    hasErrors,
                                }"
                            >
                                <div class="space-y-1">
                                    <Label for="pcf-username">{{ $t('Username') }}</Label>
                                    <Input
                                        id="pcf-username"
                                        name="username"
                                        @change="validate('username')"
                                        :class="{
                                            'border-green-500':
                                                valid('username'),
                                            'border-red-500':
                                                invalid('username'),
                                        }"
                                        :placeholder="$t('3-20 chars, letters/numbers/dashes')"
                                    />
                                    <p
                                        v-if="invalid('username')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ errors.username }}
                                    </p>
                                    <p
                                        v-else-if="valid('username')"
                                        class="text-sm text-green-600"
                                    > {{ $t('Available!') }} </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pcf-email">{{ $t('Email') }}</Label>
                                    <Input
                                        id="pcf-email"
                                        name="email"
                                        type="text"
                                        @change="validate('email')"
                                        :class="{
                                            'border-green-500': valid('email'),
                                            'border-red-500': invalid('email'),
                                        }"
                                        placeholder="user@example.com"
                                    />
                                    <p
                                        v-if="invalid('email')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ errors.email }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pcf-password">{{ $t('Password') }}</Label>
                                    <Input
                                        id="pcf-password"
                                        name="password"
                                        type="password"
                                        @change="validate('password')"
                                        :placeholder="$t('Min 8 characters')"
                                    />
                                    <p
                                        v-if="invalid('password')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ errors.password }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <Label for="pcf-confirm"
                                        >{{ $t('Confirm Password') }}</Label
                                    >
                                    <Input
                                        id="pcf-confirm"
                                        name="password_confirmation"
                                        type="password"
                                        @change="
                                            validate('password_confirmation')
                                        "
                                    />
                                    <p
                                        v-if="invalid('password_confirmation')"
                                        class="text-sm text-red-600"
                                    >
                                        {{ errors.password_confirmation }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button
                                        type="submit"
                                        size="sm"
                                        :disabled="
                                            processing ||
                                            validating ||
                                            hasErrors
                                        "
                                    >
                                        {{
                                            processing
                                                ? $t('Creating...')
                                                : validating
                                                  ? $t('Validating...')
                                                  : $t('Create Account')
                                        }}
                                    </Button>
                                </div>
                            </Form>
                        </FeatureCard>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
