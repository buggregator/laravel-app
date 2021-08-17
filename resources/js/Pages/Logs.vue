<template>
    <Head :title="currentScreen"/>

    <div>
        <div class="md:sticky md:top-0 z-50 bg-white border-b border-gray-200">
            <Screens/>
            <div class="p-2 flex flex-col md:flex-row justify-center md:justify-between items-center gap-2">
                <Labels/>
                <Colors/>
            </div>
        </div>

        <div v-if="hasEvents" class="flex flex-col">
            <div v-for="event in events" class="border-b border-gray-100">
                <component
                    class="flex-grow"
                    :is="eventComponent(event)"
                    :event="event"
                ></component>

                <Event/>
            </div>
        </div>

        <WsConnectionStatus v-else class="mt-5 mx-3 p-2 md:p-3 lg:p-4 border border-gray-300 rounded bg-gray-100"/>

        <notifications/>
    </div>
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

import RayEvent from "@/Ray/event";
import SentryEvent from "@/Sentry/event";
import SlackEvent from "@/Slack/event";

export default {
    components: {
        Colors,
        Labels,
        WsConnectionStatus, Label,
        Screens, Head, Link,
        RayEventComponent, SentryEventComponent, SlackEventComponent
    },

    computed: {
        hasEvents() {
            return _.size(this.events) > 0
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

        let events = computed(function () {
            return store.getters.filteredEvents
        });

        let currentScreen = computed(function () {
            return store.state.currentScreen
        });

        return {
            store, events, currentScreen
        }
    }
}
</script>
