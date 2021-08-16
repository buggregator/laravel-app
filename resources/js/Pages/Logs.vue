<template>
    <Head :title="currentScreen"/>

    <div>
        <div class="md:sticky md:top-0 z-50 bg-white border-b border-gray-200">
            <Screens />
            <div class="p-2 flex flex-col md:flex-row justify-center md:justify-between items-center gap-2">
                <Labels />
                <Colors />
            </div>
        </div>

        <div v-if="hasEvents" class="flex flex-col">
            <div v-for="event in events" class="border-b border-gray-100">
                <Event :event="event"/>
            </div>
        </div>
        <WsConnectionStatus v-else class="mt-5 mx-3 p-2 md:p-3 lg:p-4 border border-gray-300 rounded bg-gray-100" />
        <notifications />
    </div>
</template>

<script>
import Label from "../Components/UI/Label";
import {computed} from 'vue';
import {useStore} from "vuex";
import Event from "../Components/Event";
import {Head, Link} from '@inertiajs/inertia-vue3';
import Screens from "../Components/Layout/Screens";
import WsConnectionStatus from "../Components/UI/WsConnectionStatus";
import Labels from "../Components/Layout/Labels";
import Colors from "../Components/Layout/Colors";

export default {
    components: {
        Colors,
        Labels,
        WsConnectionStatus, Label,
        Screens, Head, Link, Event
    },

    computed: {
        hasEvents() {
            return _.size(this.events) > 0
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
