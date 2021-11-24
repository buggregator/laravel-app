<template>
    <MainLayout title="Inspector">
        <nav ref="header" class="border-b flex items-center gap-x-2 text-xs font-semibold px-4 py-2">
            <Link class="text-gray-600" :href="event.route.index">Inspector</Link>
            <div class="h-1 w-1">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 330 330">
                    <path
                        d="M251 154 101 4a15 15 0 1 0-22 22l140 139L79 304a15 15 0 0 0 22 22l150-150a15 15 0 0 0 0-22z"/>
                </svg>
            </div>
            <span class="text-gray-800">Event - {{ event.id }}</span>
        </nav>
        <main class="flex flex-col flex-grow">
            <header class="bg-gray-50 py-5 px-4 md:px-6 lg:px-8 border-b">

                <div class="flex flex-col md:flex-row justify-between">
                    <h1 class="text-sm sm:text-base md:text-lg lg:text-2xl font-bold flex items-center break-all sm:break-normal">
                        {{ event.process.name }}
                    </h1>

                    <div class="mt-5 sm:ml-5 sm:mt-0 flex justify-between sm:flex-none">
                        <a :href="event.route.json" target="_blank" class="text-sm text-blue-800 mr-5">[JSON]</a>
                        <button class="fill-current text-blue-500 h-5 w-5" @click="deleteEvent">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="m338 197-19 221c-1 10 14 11 15 1l19-221a8 8 0 0 0-15-1zM166 190c-4 0-7 4-7 8l19 221c1 10 16 9 15-1l-19-221c0-4-4-7-8-7zM249 197v222a7 7 0 1 0 15 0V197a7 7 0 1 0-15 0z"/>
                                <path d="M445 58H327V32c0-18-14-32-31-32h-80c-17 0-31 14-31 32v26H67a35 35 0 0 0 0 69h8l28 333c2 29 27 52 57 52h192c30 0 55-23 57-52l4-46a8 8 0 0 0-15-2l-4 46c-2 22-20 39-42 39H160c-22 0-40-17-42-39L90 127h22a7 7 0 1 0 0-15H67a20 20 0 0 1 0-39h378a20 20 0 0 1 0 39H147a7 7 0 1 0 0 15h275l-21 250a8 8 0 0 0 15 2l21-252h8a35 35 0 0 0 0-69zm-133 0H200V32c0-10 7-17 16-17h80c9 0 16 7 16 17v26z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <Cards :event="event"/>
            <TimelineChart :event="event" v-if="event && event.event.length > 0"/>
            <Url :event="event"/>
            <Request :event="event"/>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {computed} from 'vue';
import {useStore} from "vuex";
import NavItem from "@/Components/Inspector/NavItem";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'
import TimelineChart from "@/Components/Inspector/Show/Timeline";
import Cards from "@/Components/Inspector/Show/Cards";
import Request from "@/Components/Inspector/Show/Request";
import Url from "@/Components/Inspector/Show/Url";

export default {
    components: {
        Request, Url, MainLayout, NavItem, TimelineChart, Cards, Link
    },
    setup() {
        const store = useStore();
        const events = computed(() => store.state.inspector.events)
        const event = computed(() => store.state.inspector.event)
        return {
            events, event, store
        }
    },
    mounted() {
        this.loadEvents()


        console.log(this.event)
    },
    methods: {
        async deleteEvent() {
            try {
                await this.$inertia.delete(this.event.route.delete)
                window.location = route('inspector')
            } catch (e) {

            }
        },
        loadEvents() {
            this.store.commit('inspector/clearEvents')
            this.$page.props.events.data.forEach((e) => {
                this.store.commit('inspector/pushEvent', EventFactory.create(e))
            })

            this.store.commit('inspector/openEvent', EventFactory.create(this.$page.props.event.data))
        }
    }
}
</script>
