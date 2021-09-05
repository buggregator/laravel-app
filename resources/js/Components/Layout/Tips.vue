<template>
    <div class="mt-6 p-6 bg-white rounded-l text-gray-600 border">
        <h3 class="text-xl font-bold mb-3">{{ name }} {{ version }}</h3>
        <ul class="flex flex-col space-y-2">
            <li class="flex space-x-3 items-center">
                <svg width="25" height="25" viewBox="0 0 1024 1024" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 0C3.58 0 0 3.58 0 8C0 11.54 2.29 14.53 5.47 15.59C5.87 15.66 6.02 15.42 6.02 15.21C6.02 15.02 6.01 14.39 6.01 13.72C4 14.09 3.48 13.23 3.32 12.78C3.23 12.55 2.84 11.84 2.5 11.65C2.22 11.5 1.82 11.13 2.49 11.12C3.12 11.11 3.57 11.7 3.72 11.94C4.44 13.15 5.59 12.81 6.05 12.6C6.12 12.08 6.33 11.73 6.56 11.53C4.78 11.33 2.92 10.64 2.92 7.58C2.92 6.71 3.23 5.99 3.74 5.43C3.66 5.23 3.38 4.41 3.82 3.31C3.82 3.31 4.49 3.1 6.02 4.13C6.66 3.95 7.34 3.86 8.02 3.86C8.7 3.86 9.38 3.95 10.02 4.13C11.55 3.09 12.22 3.31 12.22 3.31C12.66 4.41 12.38 5.23 12.3 5.43C12.81 5.99 13.12 6.7 13.12 7.58C13.12 10.65 11.25 11.33 9.47 11.53C9.76 11.78 10.01 12.26 10.01 13.01C10.01 14.08 10 14.94 10 15.21C10 15.42 10.15 15.67 10.55 15.59C13.71 14.53 16 11.53 16 8C16 3.58 12.42 0 8 0Z" transform="scale(64)" fill="#333"/>
                </svg>
                <a href="https://github.com/butschster/ray-server" target="_blank" class="text-blue-600 underline">Github repository</a>
            </li>
            <li class="flex space-x-3 items-center">
                <svg width="25" height="25" xmlns="http://www.w3.org/2000/svg" fill="#2563eb" viewBox="0 0 204.4 204.4"><path d="M110 48V0H33c-2 0-4 2-4 5v195c0 2 2 4 4 4h138c3 0 5-2 5-4V61h-52c-7 0-14-6-14-13zm-2 121H62v-10h46v10zm35-25H62v-10h81v10zm0-25H62v-10h81v10zm0-25H62V84h81v10zm-23-46V10l44 41h-40c-2 0-4-1-4-3z"/></svg>
                <span>Read the docs <a href="https://github.com/spatie/ray" target="_blank" class="text-blue-600 underline">spatie/ray</a></span>
            </li>
            <li class="flex space-x-3 items-center">
                <svg width="25" height="22" viewBox="0 0 256 230" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin meet"><path d="M251.22 187.856c5.785 10.045 6.345 20.608 1.538 28.958-4.8 8.357-14.192 13.143-25.768 13.143h-21.816c.259-4.068.431-8.17.431-12.302 0-4.628-.215-9.198-.531-13.754l14.35-.014c3.528 0 6.38-2.867 6.38-6.403a6.46 6.46 0 0 0-.768-3.053l-91.46-158.97a6.349 6.349 0 0 0-5.54-3.24 6.374 6.374 0 0 0-5.461 3.09l-14.522 25.243c44.134 33.27 73.804 84.813 77.864 143.333.316 4.548.531 9.119.531 13.753 0 4.132-.172 8.228-.424 12.302h-71.246c.388-4.053.611-8.148.611-12.302 0-4.649-.273-9.233-.776-13.753-3.52-32.206-19.286-60.748-42.503-80.89l-10.003 17.376c17.54 16.325 29.512 38.558 32.767 63.514.59 4.505.92 9.09.92 13.753 0 4.154-.273 8.264-.74 12.302H29.01c-11.576 0-20.968-4.793-25.768-13.15-4.807-8.35-4.247-18.905 1.538-28.958l13.56-23.238a79.42 79.42 0 0 1 20.715 16.052l-8.083 13.768a6.523 6.523 0 0 0-.77 3.054 6.383 6.383 0 0 0 6.382 6.395l32.033.015c-2.687-15.356-9.772-29.21-19.918-40.183-5.979-6.445-12.963-11.9-20.738-16.074l35.77-62.15a151.427 151.427 0 0 1 21.564 14.624c30.827 25.013 51.644 61.955 55.416 103.776h19.172c-3.91-48.892-28.477-92.006-64.937-120.548a171.205 171.205 0 0 0-21.672-14.457l30.532-53.045C109.583 5.77 118.42 0 128.036 0c9.607 0 18.438 5.77 24.223 15.823l98.961 172.033z" fill="#FB2D26"/></svg>
                <span>Sentry DSN <a href="https://docs.sentry.io/product/sentry-basics/dsn-explainer/" target="_blank" class="text-blue-600 underline">{{ sentryDsn }}</a></span>
            </li>
        </ul>
    </div>
</template>

<script>
import {usePage} from '@inertiajs/inertia-vue3'
import {computed} from "vue";

export default {
    setup() {
        const version = computed(() => usePage().props.value.version)
        const name = computed(() => usePage().props.value.name)
        const [host, port] = window.location.host.split(':')

        const sentryDsn = computed(() => `http://sentry@${host}:${port}/1`)

        return {version, name, sentryDsn}
    }
}
</script>
