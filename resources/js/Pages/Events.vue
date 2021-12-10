<template>
    <MainLayout :title="currentScreen" classes="events-page">
        <header class="events-page__header">
            <Screens class="events-page__screens"/>
            <div class="events-page__filters filters">
                <Labels/>
                <Colors/>
            </div>
        </header>

        <main v-if="hasEvents" class="events-page__events">
            <component
                :is="eventComponent(event)"
                :event="event"
                v-for="event in events"
                :key="event.uuid"
                class="events-page__event"
            />
        </main>

        <section v-else class="events-page__welcome-block">
            <WsConnectionStatus/>
            <Tips class="events-page__tips"/>
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
import InspectorComponent from "@/Components/Inspector/Event"

import RayEvent from "@/Ray/event"
import SentryEvent from "@/Sentry/event"
import SlackEvent from "@/Slack/event"
import SmtpEvent from "@/Smtp/event"
import VarDumpEvent from "@/VarDump/event"
import MonologEvent from "@/Monolog/event"
import InspectorEvent from "@/Inspector/event"
import EventFactory from "@/EventFactory"

export default {
    components: {
        MainLayout,
        Colors,
        Labels,
        WsConnectionStatus, Label, Tips,
        Screens, Head, Link,
        RayEventComponent, SentryEventComponent, SlackEventComponent,
        SmtpEventComponent, VarDumpComponent, MonologComponent, InspectorComponent,
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
            } else if (event instanceof InspectorEvent) {
                return 'InspectorComponent'
            }

            return 'RayEventComponent'
        },
        loadEvents() {
            this.$page.props.events.data.forEach(e => {
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
