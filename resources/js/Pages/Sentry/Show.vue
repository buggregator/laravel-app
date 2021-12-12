<template>
    <MainLayout title="Sentry" class="sentry-show">
        <nav ref="header" class="sentry-show__nav">
            <Link class="text-muted" :href="event.route.index">Sentry</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330"><path d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/></svg>
            </div>
            <span>Event - {{ event.id }}</span>
        </nav>
        <main class="sentry-show__main">
            <header class="sentry-show__main-wrap">
                <div class="sentry-show__header">
                    <h1 class="sentry-show__title">
                        {{ event.payload.type }}
                        <a :href="event.route.json" target="_blank"  class="sentry-show__json">[JSON]</a>
                    </h1>

                    <button class="sentry-show__btn-delete" @click="deleteEvent">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="m338 197-19 221c-1 10 14 11 15 1l19-221a8 8 0 0 0-15-1zM166 190c-4 0-7 4-7 8l19 221c1 10 16 9 15-1l-19-221c0-4-4-7-8-7zM249 197v222a7 7 0 1 0 15 0V197a7 7 0 1 0-15 0z"/>
                            <path d="M445 58H327V32c0-18-14-32-31-32h-80c-17 0-31 14-31 32v26H67a35 35 0 0 0 0 69h8l28 333c2 29 27 52 57 52h192c30 0 55-23 57-52l4-46a8 8 0 0 0-15-2l-4 46c-2 22-20 39-42 39H160c-22 0-40-17-42-39L90 127h22a7 7 0 1 0 0-15H67a20 20 0 0 1 0-39h378a20 20 0 0 1 0 39H147a7 7 0 1 0 0 15h275l-21 250a8 8 0 0 0 15 2l21-252h8a35 35 0 0 0 0-69zm-133 0H200V32c0-10 7-17 16-17h80c9 0 16 7 16 17v26z"/>
                        </svg>
                    </button>
                </div>
                <p class="text-muted">{{ event.payload.value }}</p>
                <p class="sentry-show__date">{{ date }}</p>
            </header>

            <Tags :event="event" class="sentry-show__tags"/>

            <section class="sentry-show__exception">
                <h3 class="sentry-show__exception-title">exception</h3>

                <h3 class="sentry-show__event-type">
                    {{ event.payload.type }}
                </h3>
                <div class="sentry-show__event-value">
                    {{ event.payload.value }}
                </div>
                <div class="sentry-show__files">
                    <File
                        :file="file"
                        v-for="(file, i) in stacktrace"
                        :collapsed="i !== 0"
                        class="sentry-show__file"
                    />
                </div>
            </section>

            <Breadcrumbs :event="event" class="sentry-show__breadcrumbs" />
            <Request :event="event" class="sentry-show__request"/>
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
