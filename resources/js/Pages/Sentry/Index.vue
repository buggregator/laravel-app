<template>
    <MainLayout title="SMTP">
        <main class="divide-y divide-gray-300">
            <ListItem v-for="event in events" class="p-5" :event="event" />
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {computed} from 'vue';
import {useStore} from "vuex";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'
import ListItem from "@/Components/Sentry/List/Item"

export default {
    components: {
        MainLayout, Link, ListItem
    },
    setup() {
        const store = useStore();

        const events = computed(() => store.state.sentry.events)

        return {
            events, store
        }
    },
    mounted() {
        this.loadEvents()
    },
    methods: {
        loadEvents() {
            this.store.commit('sentry/clearEvents')
            this.$page.props.events.forEach((e) => {
                this.store.commit('sentry/pushEvent', EventFactory.create(e))
            })
        }
    }
}
</script>
