<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { Plus, X } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { submitDottedKeys } from '@/wayfinder/App/Http/Controllers/Feature/FormController';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Forms') },
    { title: t('Dotted Keys') },
];

const page = usePage();
const tags = ref<string[]>(['']);

function addTag() {
    tags.value.push('');
}

function removeTag(index: number) {
    tags.value.splice(index, 1);
}

const parsedData = computed(
    () => page.flash.parsedData as Record<string, unknown> | undefined,
);
</script>

<template>
    <Head :title="$t('Dotted Keys')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Dotted Keys')"
                docs="the-basics/forms#dotted-key-notation"
                controller="app/Http/Controllers/Feature/FormController.php#L95"
            > {{ $t('Nested objects, arrays, and escaped dots in form field names.') }} </FeatureHeader>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Form -->
                <FeatureCard :title="$t('Nested Form Fields')">
                    <template #description> {{ $t('Dotted notation creates nested objects:') }} <code class="text-xs">user.name</code> {{ $t('becomes') }} <code class="text-xs">{{
                            '{ user: { name: ... } }'
                        }}</code
                        >.
                    </template>

                    <Form
                        v-bind="submitDottedKeys.form()"
                        class="space-y-6"
                        #default="{
                            errors,
                            processing,
                            isDirty,
                            recentlySuccessful,
                        }"
                    >
                        <!-- User fields (nested) -->
                        <fieldset
                            class="space-y-3 rounded-lg border border-black/10 p-4 dark:border-white/10"
                        >
                            <legend class="px-2 text-sm font-semibold"> {{ $t('User (') }}<code class="text-xs">{{ $t('user.*') }}</code>)
                            </legend>
                            <div class="space-y-2">
                                <Label for="dk-user-name">{{ $t('Name') }}</Label>
                                <Input
                                    id="dk-user-name"
                                    name="user.name"
                                    :placeholder="$t('John Doe')"
                                />
                                <InputError :message="errors['user.name']" />
                            </div>
                            <div class="space-y-2">
                                <Label for="dk-user-email">{{ $t('Email') }}</Label>
                                <Input
                                    id="dk-user-email"
                                    type="text"
                                    name="user.email"
                                    placeholder="john@example.com"
                                />
                                <InputError :message="errors['user.email']" />
                            </div>
                        </fieldset>

                        <!-- Address fields (nested) -->
                        <fieldset
                            class="space-y-3 rounded-lg border border-black/10 p-4 dark:border-white/10"
                        >
                            <legend class="px-2 text-sm font-semibold"> {{ $t('Address (') }}<code class="text-xs">{{ $t('address.*') }}</code>)
                            </legend>
                            <div class="space-y-2">
                                <Label for="dk-address-street">{{ $t('Street') }}</Label>
                                <Input
                                    id="dk-address-street"
                                    name="address.street"
                                    :placeholder="$t('123 Main St')"
                                />
                                <InputError
                                    :message="errors['address.street']"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="dk-address-city">{{ $t('City') }}</Label>
                                <Input
                                    id="dk-address-city"
                                    name="address.city"
                                    :placeholder="$t('Springfield')"
                                />
                                <InputError :message="errors['address.city']" />
                            </div>
                        </fieldset>

                        <!-- Array fields -->
                        <fieldset
                            class="space-y-3 rounded-lg border border-black/10 p-4 dark:border-white/10"
                        >
                            <legend class="px-2 text-sm font-semibold"> {{ $t('Tags (') }}<code class="text-xs">{{ $t('tags[]') }}</code>)
                            </legend>
                            <div
                                v-for="(tag, index) in tags"
                                :key="index"
                                class="flex items-center gap-2"
                            >
                                <Input
                                    :name="`tags[]`"
                                    v-model="tags[index]"
                                    :placeholder="`Tag ${index + 1}`"
                                />
                                <Button
                                    v-if="tags.length > 1"
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    @click="removeTag(index)"
                                >
                                    <X class="size-4" />
                                </Button>
                            </div>
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                @click="addTag"
                            >
                                <Plus class="mr-1 size-4" /> {{ $t('Add Tag') }} </Button>
                        </fieldset>

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
                    </Form>
                </FeatureCard>

                <!-- Parsed Data Display -->
                <div class="space-y-6">
                    <FeatureCard
                        info-card
                        :title="$t('Parsed Request Data')"
                        :description="$t('The server echoes back the parsed data structure via flash.')"
                    >
                        <CodeBlock
                            v-if="parsedData"
                            :code="JSON.stringify(parsedData, null, 2)"
                        />
                        <p v-else class="text-sm text-muted-foreground"> {{ $t('Submit the form to see the parsed data structure.') }} </p>
                    </FeatureCard>

                    <FeatureCard info-card :title="$t('How It Works')">
                        <div class="space-y-3 text-sm text-muted-foreground">
                            <CodeBlock :title="$t('Nested Objects')">
                                <textarea>
                                    <input name="user.name" />
                                    <input name="user.email" />
                                    // Submits: { user: { name: ..., email: ... } }
                                </textarea>
                            </CodeBlock>
                            <CodeBlock :title="$t('Array Fields')">
                                <textarea>
                                    <input name="tags[]" />
                                    <input name="tags[]" />
                                    // Submits: { tags: ["a", "b"] }
                                </textarea>
                            </CodeBlock>
                            <CodeBlock :title="$t('Escaped Dots')">
                                <textarea>
                                    <input name="config\.version" />
                                    // Submits: { "config.version": "1.0" }
                                </textarea>
                            </CodeBlock>
                        </div>
                    </FeatureCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
