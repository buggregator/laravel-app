<template>
    <MainLayout title="SMTP">
        <nav ref="header" class="border-b flex items-center gap-x-2 text-xs font-semibold px-4 py-2">
            <Link class="text-gray-600" :href="event.route.index">Sentry</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330">
                    <path
                        d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/>
                </svg>
            </div>
            <span class="text-gray-800">Event - {{ event.id }}</span>
        </nav>
        <main class="flex flex-col flex-grow">
            <header class="bg-gray-50 py-5 px-4 md:px-6 lg:px-8 border-b">
                <div class="flex justify-between">
                    <h1 class="text-2xl font-bold flex items-center">
                        {{ event.payload.type }}
                        <a :href="event.route.json" target="_blank"  class="text-sm text-blue-800 ml-5">[JSON]</a>
                    </h1>

                    <button class="h-5 w-5" @click="deleteEvent">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="m338 197-19 221c-1 10 14 11 15 1l19-221a8 8 0 0 0-15-1zM166 190c-4 0-7 4-7 8l19 221c1 10 16 9 15-1l-19-221c0-4-4-7-8-7zM249 197v222a7 7 0 1 0 15 0V197a7 7 0 1 0-15 0z"/>
                            <path d="M445 58H327V32c0-18-14-32-31-32h-80c-17 0-31 14-31 32v26H67a35 35 0 0 0 0 69h8l28 333c2 29 27 52 57 52h192c30 0 55-23 57-52l4-46a8 8 0 0 0-15-2l-4 46c-2 22-20 39-42 39H160c-22 0-40-17-42-39L90 127h22a7 7 0 1 0 0-15H67a20 20 0 0 1 0-39h378a20 20 0 0 1 0 39H147a7 7 0 1 0 0 15h275l-21 250a8 8 0 0 0 15 2l21-252h8a35 35 0 0 0 0-69zm-133 0H200V32c0-10 7-17 16-17h80c9 0 16 7 16 17v26z"/>
                        </svg>
                    </button>
                </div>

                <p class="text-gray-700">{{ event.payload.value }}</p>
                <p class="text-gray-500 text-sm mt-3">{{ date }}</p>
            </header>

            <Tags :event="event"/>

            <section class="py-5 px-4 md:px-6 lg:px-8 border-b">
                <h3 class="text-gray-400 font-bold uppercase text-sm mb-5">exception</h3>

                <h3 class="text-gray-900 mb-1 text-xl font-bold">
                    {{ event.payload.type }}
                </h3>
                <div class="text-gray-600 break-all mb-5">
                    {{ event.payload.value }}
                </div>
                <div class="border border-purple-200">
                    <File :file="file" v-for="(file, i) in stacktrace" :collapsed="i !== 0"/>
                </div>
            </section>

            <Breadcrumbs :event="event"/>
            <Request :event="event"/>
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
        async deleteEvent() {
            try {
                await this.$inertia.delete(this.event.route.delete)
                window.location = route('sentry')
            } catch (e) {

            }
        },
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
