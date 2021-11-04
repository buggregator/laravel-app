<template>
    <MainLayout title="SMTP">
        <div ref="header" class="border-b flex items-center gap-x-2 text-xs font-semibold px-4 py-2">
            <Link class="text-gray-600" href="/mail">Sentry</Link>
        </div>
        <main class="flex flex-grow w-full">

        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {computed} from 'vue';
import {useStore} from "vuex";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {
        MainLayout, Link
    },
    setup() {
        const store = useStore();

        const events = computed(() => store.state.sentry.events)
        const event = computed(() => store.state.sentry.event)
        return {
            events, event, store
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

            this.store.commit('sentry/openEvent', EventFactory.create(this.$page.props.event))
        }
    }
}
</script>
