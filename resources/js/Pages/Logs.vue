<template>
    <Head :title="currentScreen"/>

    <div class="flex flex-col h-screen">
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
            ></component>
        </main>

        <section v-else class="flex-1 p-4 flex flex-col justify-center items-center bg-gray-50">
            <WsConnectionStatus/>
            <Tips />
        </section>
    </div>

    <notifications/>
</template>

<script>
import Label from "@/Components/UI/Label";
import {computed} from 'vue';
import {useStore} from "vuex";
import RayEventComponent from "@/Components/Ray/Event";
import SentryEventComponent from "@/Components/Sentry/Event";
import SlackEventComponent from "@/Components/Slack/Event";
import {Head, Link} from '@inertiajs/inertia-vue3';
import Screens from "@/Components/Layout/Screens";
import WsConnectionStatus from "@/Components/UI/WsConnectionStatus";
import Labels from "@/Components/Layout/Labels";
import Colors from "@/Components/Layout/Colors";
import Tips from "@/Components/Layout/Tips";

import RayEvent from "@/Ray/event";
import SentryEvent from "@/Sentry/event";
import SlackEvent from "@/Slack/event";

export default {
    components: {
        Colors, Labels,Screens,
        WsConnectionStatus, Tips,
        Label, Head, Link,
        RayEventComponent, SentryEventComponent, SlackEventComponent
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
            }  else if (event instanceof SlackEvent) {
                return 'SlackEventComponent'
            }

            return 'RayEventComponent'
        }
    },

    setup() {
        const store = useStore();

        const events = computed(() => store.getters.filteredEvents)
        const totalEvents = computed(() => store.getters.totalEvents)
        const currentScreen = computed(()  => store.state.currentScreen)

        return {
            store, events, totalEvents, currentScreen
        }
    }
}
</script>
