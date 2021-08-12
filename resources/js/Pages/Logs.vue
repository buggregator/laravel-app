<template>
    <Head :title="currentScreen"/>

    <div>
        <Screens />
        <Event :event="event" v-for="event in events" v-if="hasEvents"/>

        <div v-else class="mt-5 mx-3 p-4 border border-blue-300 rounded bg-gray-100 flex space-x-3 items-center">
            <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#3b82f6" stroke-width="4"></circle>
                <path class="opacity-75" fill="#3b82f6" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>

            <span>Hurry! Give me something. I can't wait to show it.</span>
        </div>
        <notifications />
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import Event from "../Components/Event";
import {Head, Link} from '@inertiajs/inertia-vue3';
import Screens from "../Components/Layout/Screens";

export default {
    components: {
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
