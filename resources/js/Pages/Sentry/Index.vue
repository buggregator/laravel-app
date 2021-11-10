<template>
    <MainLayout title="SMTP">
        <main class="m-3">
            <h1 class="text-2xl font-bold mb-3">Sentry issue</h1>
            <div class="divide-y border-2">
                <ListItem v-for="event in events" :event="event" />
            </div>
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
