<template>
    <div class="tips">
        <h3 class="tips__title">{{ name }} {{ version }}</h3>
        <ul class="tips__list">
            <li class="tips__item">
                <GithubIcon class="w-6 text-gray-800 dark:text-white" />
                <a href="https://github.com/buggregator/app" target="_blank" class="tips__link">Github repository</a>
            </li>
            <li class="tips__item">
                <SentryIcon class="w-6 text-red-800 dark:text-white" />
                <span>Sentry DSN <a href="https://docs.sentry.io/product/sentry-basics/dsn-explainer/" target="_blank" class="tips__link">{{ sentryDsn }}</a></span>
            </li>
            <li class="tips__item">
                <InspectorIcon class="w-6 text-blue-800 dark:text-white" />
                <span>Inspector URL <a href="https://docs.inspector.dev/raw-php" target="_blank" class="tips__link">{{ inspectorUrl }}</a></span>
            </li>
            <li class="tips__item">
                <DocsIcon class="w-6 text-blue-800 dark:text-white" />
                <span>Read the docs <a href="https://github.com/spatie/ray" target="_blank" class="tips__link">spatie/ray</a></span>
            </li>
        </ul>
    </div>
</template>

<script>
import {usePage} from '@inertiajs/inertia-vue3'
import {computed} from "vue";
import GithubIcon from "../UI/Icons/GithubIcon";
import SentryIcon from "../UI/Icons/SentryIcon";
import InspectorIcon from "../UI/Icons/InspectorIcon";
import DocsIcon from "../UI/Icons/DocsIcon";

export default {
    components: {DocsIcon, InspectorIcon, SentryIcon, GithubIcon},
    setup() {
        const version = computed(() => usePage().props.value.version)
        const name = computed(() => usePage().props.value.name)
        const [host, port] = window.location.host.split(':')

        const sentryDsn = computed(() => `http://sentry@${host}:${port}/1`)
        const inspectorUrl = computed(() => `http://${host}:${port}/inspector`)

        return {version, name, sentryDsn, inspectorUrl}
    }
}
</script>
