<template>
    <MainLayout title="HttpDump">
        <nav ref="header" class="breadcrumbs">
            <Link class="text-muted" :href="event.route.index">Http requests</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330"><path d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/></svg>
            </div>
            <span>{{ event.id }}</span>
        </nav>
        <main class="flex flex-col flex-grow">
            <header class="bg-gray-50 dark:bg-gray-900 py-5 px-4 md:px-6 lg:px-8 border-b">
                <div class="flex justify-between items-center">
                    {{ httpEvent.request.method }} {{ decodedUri }}
                </div>
            </header>
            <PostData :event="httpEvent" v-if="hasPost"/>
            <QueryParameters :event="httpEvent"/>
            <Headers :event="httpEvent"/>
            <RequestBody :event="httpEvent" v-if="hasBody"/>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {useStore} from "vuex";
import {computed} from "vue";
import {Link} from '@inertiajs/inertia-vue3'
import EventFactory from "@/EventFactory";
import QueryParameters from "@/Components/HttpDump/Show/QueryParameters";
import Headers from "@/Components/HttpDump/Show/Headers";
import RequestBody from "@/Components/HttpDump/Show/RequestBody";
import PostData from "@/Components/HttpDump/Show/PostData";


export default {
    components: {
        PostData,
        MainLayout, QueryParameters, Headers, RequestBody, Link
    },
    setup() {
        const store = useStore();
        const events = computed(() => store.state.httpdump.events)
        const event = computed(() => store.state.httpdump.event)
        const httpEvent = computed(() => store.state.httpdump.event.event)

        return {
            events, event, httpEvent, store
        }
    },
    mounted() {
        this.loadEvents()
    },
    methods: {
        loadEvents() {
            this.store.commit('httpdump/clearEvents')
            this.store.commit('httpdump/pushEvent', EventFactory.create(this.$page.props.event.data))
            this.store.commit('httpdump/openEvent', EventFactory.create(this.$page.props.event.data))
        }
    },
    computed: {
        decodedUri() {
            return decodeURIComponent(this.event.event.request.uri)
        },
        hasPost() {
            return Object.keys(this.event.event.request.post).length > 0
        },
        hasBody() {
            return this.httpEvent.request.body.length > 0 && this.httpEvent.request.body !== '{}'
        }
    }
}
</script>
