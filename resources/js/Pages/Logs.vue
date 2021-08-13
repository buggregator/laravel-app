<template>
    <Head :title="currentScreen"/>

    <div>
        <Screens />
        <div v-if="hasEvents" class="flex flex-col">
            <div v-for="event in events" class="border-b border-gray-100">
                <Event :event="event"/>
            </div>
        </div>
        <WsConnectionStatus v-else class="mt-5 mx-3 p-2 md:p-3 lg:p-4 border border-blue-300 rounded bg-gray-100" />
        <notifications />
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import Event from "../Components/Event";
import {Head, Link} from '@inertiajs/inertia-vue3';
import Screens from "../Components/Layout/Screens";
import WsConnectionStatus from "../Components/UI/WsConnectionStatus";

export default {
    components: {
        WsConnectionStatus,
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
            return store.state.events[store.state.currentScreen] || []
        });

        let currentScreen = computed(function () {
            return store.state.currentScreen
        });

        return {
            events, currentScreen
        }
    }
}
</script>
