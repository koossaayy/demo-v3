<script setup lang="ts">
import { Form, Head, router, useForm } from '@inertiajs/vue3';
import { Heart } from 'lucide-vue-next';
import { ref } from 'vue';
import CodeBlock from '@/components/CodeBlock.vue';
import FeatureCard from '@/components/FeatureCard.vue';
import FeatureHeader from '@/components/FeatureHeader.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { App } from '@/wayfinder/types';

interface PageProps {
    contacts: App.Models.Contact[];
}

const props = defineProps<PageProps>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Forms' },
    { title: 'Optimistic Updates' },
];

const simulateError = ref(false);
const activeTab = ref<'router' | 'useForm' | 'formComponent'>('router');
const eventLog = ref<string[]>([]);

function log(message: string) {
    eventLog.value.unshift(`${new Date().toLocaleTimeString()} - ${message}`);
    if (eventLog.value.length > 15) {
        eventLog.value.pop();
    }
}

// router.optimistic() pattern
function toggleFavoriteRouter(
    contact: Pick<
        App.Models.Contact,
        'id' | 'first_name' | 'last_name' | 'is_favorite'
    >,
) {
    log(`[router.optimistic] Toggling ${contact.first_name}...`);

    router
        .optimistic<PageProps>((currentProps) => ({
            contacts: currentProps.contacts.map((c) =>
                c.id === contact.id ? { ...c, is_favorite: !c.is_favorite } : c,
            ),
        }))
        .post(
            `/features/forms/optimistic-toggle/${contact.id}`,
            {
                simulate_error: simulateError.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => log(`[router.optimistic] Server confirmed`),
                onError: () =>
                    log(`[router.optimistic] Error! Auto-rolled back`),
            },
        );
}

// useForm.optimistic() pattern
const favoriteForm = useForm({ simulate_error: false });

function toggleFavoriteUseForm(
    contact: Pick<
        App.Models.Contact,
        'id' | 'first_name' | 'last_name' | 'is_favorite'
    >,
) {
    log(`[useForm.optimistic] Toggling ${contact.first_name}...`);
    favoriteForm.simulate_error = simulateError.value;

    favoriteForm
        .optimistic<PageProps>((currentProps) => ({
            contacts: currentProps.contacts.map((c) =>
                c.id === contact.id ? { ...c, is_favorite: !c.is_favorite } : c,
            ),
        }))
        .post(`/features/forms/optimistic-toggle/${contact.id}`, {
            preserveScroll: true,
            onSuccess: () => log(`[useForm.optimistic] Server confirmed`),
            onError: () => log(`[useForm.optimistic] Error! Auto-rolled back`),
        });
}
</script>

<template>
    <Head :title="$t('Optimistic Updates')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <FeatureHeader
                :title="$t('Optimistic Updates')"
                docs="the-basics/optimistic-updates"
                controller="app/Http/Controllers/Feature/FormController.php#L81"
            > {{ $t('Instant UI feedback with automatic rollback on error. Toggle a favorite and notice how the heart fills immediately while the request is still in flight.') }} </FeatureHeader>

            <!-- Approach Switcher -->
            <div class="flex flex-wrap gap-2">
                <Button
                    :variant="activeTab === 'router' ? 'default' : 'outline'"
                    size="sm"
                    @click="activeTab = 'router'"
                    >{{ $t('router.optimistic()') }}</Button
                >
                <Button
                    :variant="activeTab === 'useForm' ? 'default' : 'outline'"
                    size="sm"
                    @click="activeTab = 'useForm'"
                    >{{ $t('useForm.optimistic()') }}</Button
                >
                <Button
                    :variant="
                        activeTab === 'formComponent' ? 'default' : 'outline'
                    "
                    size="sm"
                    @click="activeTab = 'formComponent'"
                    >{{ $t('Form :optimistic') }}</Button
                >
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Contact List -->
                <FeatureCard :title="$t('Contacts')">
                    <template #description>
                        <template v-if="activeTab === 'router'"
                            ><code class="text-xs">{{ $t('router.optimistic()') }}</code>{{ $t('. Declarative prop updates with automatic rollback.') }}</template
                        >
                        <template v-else-if="activeTab === 'useForm'"
                            ><code class="text-xs">{{ $t('useForm().optimistic()') }}</code
                            >{{ $t('. Chained on the form helper.') }}</template
                        >
                        <template v-else
                            ><code class="text-xs"
                                >{{ $t('<Form :optimistic>') }}</code
                            >{{ $t('. Optimistic updates on the Form component.') }}</template
                        >
                    </template>

                    <!-- Error simulation toggle -->
                    <div
                        class="mb-4 flex items-center gap-2 rounded-md bg-muted p-3"
                    >
                        <input
                            id="simulate-error"
                            type="checkbox"
                            v-model="simulateError"
                            class="size-4 rounded border"
                        />
                        <label for="simulate-error" class="text-sm"> {{ $t('Simulate error (to see rollback)') }} </label>
                    </div>

                    <!-- Manual / router.optimistic / useForm.optimistic -->
                    <div v-if="activeTab !== 'formComponent'" class="space-y-2">
                        <div
                            v-for="contact in props.contacts"
                            :key="contact.id"
                            class="flex items-center gap-3 rounded-xl bg-muted/30 p-3 transition-colors"
                        >
                            <div
                                class="flex size-8 items-center justify-center rounded-full bg-primary/10 text-xs font-medium text-primary"
                            >
                                {{ contact.first_name[0]
                                }}{{ contact.last_name[0] }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-sm font-medium"
                                    >{{ contact.first_name }}
                                    {{ contact.last_name }}</span
                                >
                                <div
                                    v-if="contact.email"
                                    class="truncate text-xs text-muted-foreground"
                                >
                                    {{ contact.email }}
                                </div>
                            </div>
                            <button
                                type="button"
                                class="rounded-md p-1.5 transition-colors hover:bg-accent"
                                @click="
                                    activeTab === 'router'
                                        ? toggleFavoriteRouter(contact)
                                        : toggleFavoriteUseForm(contact)
                                "
                            >
                                <Heart
                                    class="size-5 transition-colors"
                                    :class="
                                        contact.is_favorite
                                            ? 'fill-red-500 text-red-500'
                                            : 'text-muted-foreground'
                                    "
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Form :optimistic approach -->
                    <div v-else class="space-y-2">
                        <div
                            v-for="contact in props.contacts"
                            :key="contact.id"
                            class="flex items-center gap-3 rounded-xl bg-muted/30 p-3 transition-colors"
                        >
                            <div
                                class="flex size-8 items-center justify-center rounded-full bg-primary/10 text-xs font-medium text-primary"
                            >
                                {{ contact.first_name[0]
                                }}{{ contact.last_name[0] }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-sm font-medium"
                                    >{{ contact.first_name }}
                                    {{ contact.last_name }}</span
                                >
                                <div
                                    v-if="contact.email"
                                    class="truncate text-xs text-muted-foreground"
                                >
                                    {{ contact.email }}
                                </div>
                            </div>
                            <Form
                                :action="`/features/forms/optimistic-toggle/${contact.id}`"
                                method="post"
                                :optimistic="
                                    (currentProps) => ({
                                        contacts: (
                                            currentProps.contacts as App.Models.Contact[]
                                        ).map((c) =>
                                            c.id === contact.id
                                                ? {
                                                      ...c,
                                                      is_favorite:
                                                          !c.is_favorite,
                                                  }
                                                : c,
                                        ),
                                    })
                                "
                                preserve-scroll
                                class="contents"
                            >
                                <input
                                    type="hidden"
                                    name="simulate_error"
                                    :value="simulateError ? '1' : '0'"
                                />
                                <button
                                    type="submit"
                                    class="rounded-md p-1.5 transition-colors hover:bg-accent"
                                >
                                    <Heart
                                        class="size-5 transition-colors"
                                        :class="
                                            contact.is_favorite
                                                ? 'fill-red-500 text-red-500'
                                                : 'text-muted-foreground'
                                        "
                                    />
                                </button>
                            </Form>
                        </div>
                    </div>

                    <p
                        v-if="!props.contacts.length"
                        class="py-4 text-center text-sm text-muted-foreground"
                    > {{ $t('No contacts found. Seed the database first.') }} </p>
                </FeatureCard>

                <!-- Event Log + How It Works -->
                <div class="space-y-6">
                    <FeatureCard info-card :title="$t('Approach Comparison')">
                        <div class="space-y-3">
                            <div
                                class="rounded-md p-3 text-xs"
                                :class="
                                    activeTab === 'router'
                                        ? 'bg-muted ring-2 ring-primary'
                                        : 'bg-muted'
                                "
                            >
                                <CodeBlock
                                    :title="$t('router.optimistic()')"
                                    code="router.optimistic((props) => ({
  contacts: /* updated */
})).post(url, data)"
                                />
                            </div>
                            <div
                                class="rounded-md p-3 text-xs"
                                :class="
                                    activeTab === 'useForm'
                                        ? 'bg-muted ring-2 ring-primary'
                                        : 'bg-muted'
                                "
                            >
                                <CodeBlock
                                    :title="$t('useForm.optimistic()')"
                                    code="form.optimistic((props) => ({
  contacts: /* updated */
})).post(url)"
                                />
                            </div>
                            <div
                                class="rounded-md p-3 text-xs"
                                :class="
                                    activeTab === 'formComponent'
                                        ? 'bg-muted ring-2 ring-primary'
                                        : 'bg-muted'
                                "
                            >
                                <CodeBlock :title="$t('<Form :optimistic>')">
                                    <textarea>
<Form :optimistic="(props, data) => ({
  contacts: /* updated */
})" />
                                    </textarea>
                                </CodeBlock>
                            </div>
                        </div>
                    </FeatureCard>

                    <FeatureCard info-card :title="$t('Event Log')">
                        <template #header-action>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="eventLog = []"
                                >{{ $t('Clear') }}</Button
                            >
                        </template>

                        <div v-if="eventLog.length" class="space-y-1">
                            <div
                                v-for="(entry, index) in eventLog"
                                :key="index"
                                class="rounded bg-muted px-3 py-1.5 font-mono text-xs"
                                :class="{
                                    'text-red-600 dark:text-red-400':
                                        entry.includes('Error'),
                                    'text-green-600 dark:text-green-400':
                                        entry.includes('confirmed'),
                                }"
                            >
                                {{ entry }}
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground"> {{ $t('Toggle a favorite to see events here.') }} </p>
                    </FeatureCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
