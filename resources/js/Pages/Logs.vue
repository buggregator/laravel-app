<template>
    <Head :title="currentScreen"/>

    <div>
        <Screens />
        <Event :event="event" v-for="event in events"/>
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
