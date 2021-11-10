<template>
    <MainLayout title="SMTP">
        <nav ref="header" class="border-b flex items-center gap-x-2 text-xs font-semibold px-4 py-2">
            <Link class="text-gray-600" :href="route('sentry')">Sentry</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330"><path d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/></svg>
            </div>
            <span class="text-gray-800">Event - {{ event.id }}</span>
        </nav>
        <main class="flex flex-col flex-grow">
            <header class="bg-gray-50 py-5 px-4 md:px-6 lg:px-8 border-b">
                <h1 class="text-2xl font-bold flex items-center">
                    {{ event.payload.type }} <a :href="route('sentry.show.json', event.id)" target="_blank" class="text-sm text-blue-800 ml-5">[JSON]</a>
                </h1>
                <div class="text-gray-700">
                    {{ event.payload.value }}
                    <span class="mx-2">|</span>
                    <span>{{ date }}</span>
                </div>
            </header>
            <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
                <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">tags</h3>

                <div class="flex space-x-5 mb-5">
                    <div v-if="event.runtime" class="border rounded px-4 pb-2 pt-1">
                        <span class="text-gray-500 text-xs font-bold">runtime</span>
                        <h4 class="font-bold">{{ event.runtime.name }}</h4>
                        <p class="text-sm">Version: {{ event.runtime.version }}</p>
                    </div>

                    <div v-if="event.os" class="border rounded px-4 pb-2 pt-1">
                        <span class="text-gray-500 text-xs font-bold">os</span>
                        <h4 class="font-bold">{{ event.os.name }}</h4>
                        <p class="text-sm">Version: {{ event.os.version }}</p>
                    </div>

                    <div v-if="event.sdk" class="border rounded px-4 pb-2 pt-1">
                        <span class="text-gray-500 text-xs font-bold">sdk</span>
                        <h4 class="font-bold">{{ event.sdk.name }}</h4>
                        <p class="text-sm">Version: {{ event.sdk.version }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                   <div class="flex border rounded text-xs items-center">
                       <div class="px-3 py-1 border-r">env</div>
                       <div class="px-3 py-1 bg-gray-100 font-semibold">{{ event.environment }}</div>
                   </div>
                    <div class="flex border rounded text-xs items-center">
                        <div class="px-3 py-1 border-r">logger</div>
                        <div class="px-3 py-1 bg-gray-100 font-semibold">{{ event.logger }}</div>
                    </div>
                    <div class="flex border rounded text-xs items-center">
                        <div class="px-3 py-1 border-r">os</div>
                        <div class="px-3 py-1 bg-gray-100 font-semibold">{{ event.os.name }} {{ event.os.version }}</div>
                    </div>
                    <div class="flex border rounded text-xs items-center">
                        <div class="px-3 py-1 border-r">runtime</div>
                        <div class="px-3 py-1 bg-gray-100 font-semibold">{{ event.runtime.name }} {{ event.runtime.version }}</div>
                    </div>
                    <div class="flex border rounded text-xs items-center">
                        <div class="px-3 py-1 border-r">server name</div>
                        <div class="px-3 py-1 bg-gray-100 font-semibold">{{ event.serverName }}</div>
                    </div>
                </div>
            </section>

            <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
                <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">exception</h3>

                <h3 class="text-gray-900 mb-1 text-xl font-bold">
                    {{ event.payload.type }}
                </h3>
                <div class="text-gray-600 break-all mb-5">
                    {{ event.payload.value }}
                </div>
                <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0"/>
            </section>

            <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
                <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">breadcrumbs</h3>


            </section>

            <section class="py-5 px-4 md:px-6 lg:px-8 border-b" v-if="event.request">
                <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">request</h3>

                <h3 class="text-gray-900 mb-1 text-lg font-medium">
                    <strong>{{ event.request.method }}</strong> {{ event.request.url }}
                </h3>

                <Table>
                    <TableRow :title="title" v-for="(value, title) in event.request.headers">
                        {{ value[0] || value}}
                    </TableRow>
                </Table>
            </section>
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
import Table from "@/Components/UI/Table";
import TableRow from "@/Components/UI/TableRow";

export default {
    components: {
        MainLayout, Link, File, Table, TableRow
    },
    data() {
        return {
            tags: {

            }
        }
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
            this.$page.props.events.forEach((e) => {
                this.store.commit('sentry/pushEvent', EventFactory.create(e))
            })

            this.store.commit('sentry/openEvent', EventFactory.create(this.$page.props.event))
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
