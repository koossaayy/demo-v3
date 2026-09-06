<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

declare const __PROJECT_ROOT__: string | undefined;

const props = defineProps<{
    docs?: string;
    controller?: string;
}>();

const page = usePage();
const componentPath = `resources/js/pages/${page.component}.vue`;

const isLocal = typeof __PROJECT_ROOT__ !== 'undefined';

function url(path: string) {
    if (isLocal) {
        const [file, line] = path.split('#L');
        return `vscode://file/${__PROJECT_ROOT__}/${file}${line ? `:${line}` : ''}`;
    }

    return `https://github.com/inertiajs/demo-v3/blob/main/${path}`;
}

const links = [
    props.docs
        ? {
              label: t('Docs'),
              href: `https://inertiajs.com/docs/v3/${props.docs}`,
              external: true,
          }
        : null,
    { label: t('Vue page'), href: url(componentPath), external: !isLocal },
    props.controller
        ? {
              label: t('Controller'),
              href: url(props.controller),
              external: !isLocal,
          }
        : null,
].filter(Boolean) as { label: string; href: string; external?: boolean }[];
</script>

<template>
    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
        <a
            v-for="link in links"
            :key="link.label"
            :href="link.href"
            :target="link.external ? '_blank' : undefined"
            class="inline-flex items-center gap-1 text-sm text-muted-foreground underline decoration-muted-foreground/50 hover:text-foreground hover:decoration-foreground"
        >
            {{ link.label }}
        </a>
    </div>
</template>
