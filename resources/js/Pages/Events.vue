<template>
    <MainLayout :title="currentScreen">
        <header class="md:sticky md:top-0 z-50 bg-white border-b border-gray-200">
            <Screens/>
            <div class="p-2 flex flex-col md:flex-row justify-center md:justify-between items-center gap-2">
                <Labels/>
                <Colors/>
            </div>
        </header>

        <main v-if="hasEvents" class="flex flex-col divide-y border-b">
            <component
                class="flex-grow"
                :is="eventComponent(event)"
                :event="event"
                v-for="event in events"
                :key="event.uuid"
            ></component>
        </main>

        <section v-else class="flex-1 p-4 flex flex-col justify-center items-center bg-gray-50">
            <WsConnectionStatus/>
            <Tips/>
        </section>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import Label from "@/Components/UI/Label"
import {computed} from 'vue'
import {useStore} from "vuex"
import {Head, Link} from '@inertiajs/inertia-vue3'
import Screens from "@/Components/Layout/Screens"
import WsConnectionStatus from "@/Components/UI/WsConnectionStatus"
import Labels from "@/Components/Layout/Labels"
import Colors from "@/Components/Layout/Colors"
import Tips from "@/Components/Layout/Tips"

import RayEventComponent from "@/Components/Ray/Event"
import SentryEventComponent from "@/Components/Sentry/Event"
import SlackEventComponent from "@/Components/Slack/Event"
import SmtpEventComponent from "@/Components/Smtp/Event"
import VarDumpComponent from "@/Components/VarDump/Event"
import MonologComponent from "@/Components/Monolog/Event"

import RayEvent from "@/Ray/event"
import SentryEvent from "@/Sentry/event"
import SlackEvent from "@/Slack/event"
import SmtpEvent from "@/Smtp/event"
import VarDumpEvent from "@/VarDump/event"
import MonologEvent from "@/Monolog/event"
import EventFactory from "@/EventFactory"

export default {
    components: {
        MainLayout,
        Colors,
        Labels,
        WsConnectionStatus, Label, Tips,
        Screens, Head, Link,
        RayEventComponent, SentryEventComponent, SlackEventComponent,
        SmtpEventComponent, VarDumpComponent, MonologComponent
    },

    computed: {
        hasEvents() {
            return this.totalEvents > 0
        }
    },

    methods: {
        eventComponent(event) {
            if (event instanceof SentryEvent) {
                return 'SentryEventComponent'
            } else if (event instanceof SlackEvent) {
                return 'SlackEventComponent'
            } else if (event instanceof SmtpEvent) {
                return 'SmtpEventComponent'
            } else if (event instanceof VarDumpEvent) {
                return 'VarDumpComponent'
            } else if (event instanceof MonologEvent) {
                return 'MonologComponent'
            }

            return 'RayEventComponent'
        },
        loadEvents() {
            this.$page.props.events.forEach((e) => {
                this.store.commit('pushEvent', EventFactory.create(e))
            })
        },
    },

    setup() {
        const store = useStore();

        const events = computed(() => store.getters.filteredEvents)
        const totalEvents = computed(() => store.getters.totalEvents)
        const currentScreen = computed(() => store.state.currentScreen)

        return {
            store, events, totalEvents, currentScreen
        }
    },
    mounted() {
        this.loadEvents()
    }
}
</script>
