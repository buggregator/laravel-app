<template>
    <MainLayout title="SMTP">
        <nav ref="header" class="border-b dark:border-gray-400 flex items-center gap-x-2 text-xs font-semibold px-4 py-2">
            <Link class="text-muted" :href="event.route.index">Sentry</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330"><path d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/></svg>
            </div>
            <span>Event - {{ event.id }}</span>
        </nav>
        <main class="flex flex-col flex-grow">
            <header class="bg-gray-50 dark:bg-gray-900 py-5 px-4 md:px-6 lg:px-8 border-b dark:border-gray-400">
                <h1 class="text-2xl font-bold flex items-center">
                    {{ event.payload.type }} <a :href="event.route.json" target="_blank" class="text-sm text-blue-800 dark:text-blue-300 ml-5">[JSON]</a>
                </h1>
                <p class="text-muted">{{ event.payload.value }}</p>
                <p class="text-muted text-sm mt-3">{{ date }}</p>
            </header>

            <Tags :event="event" />

            <section class="py-5 px-4 md:px-6 lg:px-8 border-b dark:border-gray-400">
                <h3 class="text-muted font-bold uppercase text-sm mb-5">exception</h3>

                <h3 class="mb-1 text-xl font-bold">
                    {{ event.payload.type }}
                </h3>
                <div class="text-muted break-all mb-5">
                    {{ event.payload.value }}
                </div>
                <div class="border border-purple-200 dark:border-gray-400">
                    <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0"/>
                </div>
            </section>

            <Breadcrumbs :event="event" />
            <Request :event="event" />
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {computed} from 'vue';
import {useStore} from "vuex";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'
import File from "@/Components/Sentry/UI/File";
import Tags from "@/Components/Sentry/Show/Tags";
import Breadcrumbs from "@/Components/Sentry/Show/Breadcrumbs";
import Request from "@/Components/Sentry/Show/Request";
export default {
    components: {
        MainLayout, Link, File,
        Tags, Breadcrumbs, Request,
    },
    setup() {
        const store = useStore();

        const events = computed(() => store.state.sentry.events)
        const event = computed(() => store.state.sentry.event)
        return {
            events, event, store
        }
    },
    mounted() {
        this.loadEvents()
    },
    methods: {
        loadEvents() {
            this.store.commit('sentry/clearEvents')
            this.$page.props.events.data.forEach((e) => {
                this.store.commit('sentry/pushEvent', EventFactory.create(e))
            })

            this.store.commit('sentry/openEvent', EventFactory.create(this.$page.props.event.data))
        }
    },
    computed: {
        date() {
            return this.event.date.fromNow()
        },
        stacktrace() {
            return this.event.stacktrace
        }
    }
}
</script>
