<template>
    <MainLayout title="SMTP">
        <main class="flex flex-grow w-full">
            <div class="w-0 md:w-72 lg:w-96 flex-none border-r">
                <PerfectScrollbar :style="{height: menuHeight}" v-if="events.length > 0">
                    <NavItem v-for="event in events" :event="event"/>
                </PerfectScrollbar>

                <div v-else class="h-full flex flex-col items-center justify-center">
                    <svg class="w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 469.33 469.33"
                         style="enable-background:new 0 0 469.333 469.333" xml:space="preserve"><path d="m467.65 260.9-96-149.34a10.7 10.7 0 0 0-8.98-4.9h-85.34a10.66 10.66 0 1 0 0 21.34h79.5l82.3 128h-65.8a42.72 42.72 0 0 0-42.66 42.67A21.35 21.35 0 0 1 309.33 320H160a21.35 21.35 0 0 1-21.33-21.33A42.72 42.72 0 0 0 96 256H30.2l82.3-128H192a10.66 10.66 0 1 0 0-21.33h-85.33c-3.63 0-7 1.84-8.98 4.9l-96 149.32A10.69 10.69 0 0 0 0 266.67v160a42.72 42.72 0 0 0 42.67 42.66h384a42.72 42.72 0 0 0 42.66-42.66v-160c0-2.05-.58-4.06-1.68-5.77zM448 426.67A21.35 21.35 0 0 1 426.67 448h-384a21.35 21.35 0 0 1-21.34-21.33V277.33H96a21.35 21.35 0 0 1 21.33 21.34A42.72 42.72 0 0 0 160 341.33h149.33A42.72 42.72 0 0 0 352 298.67a21.35 21.35 0 0 1 21.33-21.34H448v149.34z"/>
                        <path
                            d="M163.13 195.13a10.66 10.66 0 0 0 0 15.08l64 64a10.63 10.63 0 0 0 15.08 0l64-64a10.66 10.66 0 1 0-15.08-15.09l-45.8 45.8V10.67a10.66 10.66 0 1 0-21.33 0v230.25l-45.8-45.8a10.66 10.66 0 0 0-15.07 0z"/></svg>
                    <h3 class="font-semibold text-xl mt-2">Your inbox is empty</h3>
                </div>
            </div>

            <div class="flex flex-col flex-grow py-5 px-8">
                <h2 class="text-2xl font-bold">SMTP Settings</h2>
                <p class="text-gray-700 font-semibold text-sm">Use these settings to send messages directly from your email client or mail transfer agent.</p>

                <div>
                    ...
                </div>
            </div>
        </main>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Components/Layout/Main"
import {PerfectScrollbar} from 'vue3-perfect-scrollbar'
import {computed} from 'vue';
import {useStore} from "vuex";
import NavItem from "@/Components/Smtp/NavItem";
import Info from "@/Components/Smtp/Info";
import EventFactory from "@/EventFactory";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {
        MainLayout, NavItem, PerfectScrollbar, Info, Link
    },
    data() {
        return {
            menuHeight: 0
        }
    },
    setup() {
        const store = useStore();

        const events = computed(() => store.state.smtp.events)
        return {
            events, store
        }
    },
    mounted() {
        this.loadEvents()
        this.calculateMenuHeight()
        window.addEventListener('resize', (event) => {
            this.calculateMenuHeight()
        });
    },
    methods: {
        loadEvents() {
            this.store.commit('smtp/clearEvents')
            this.$page.props.events.forEach((e) => {
                this.store.commit('smtp/pushEvent', EventFactory.create(e))
            })
        },
        calculateMenuHeight() {
            const headerHeight = this.$refs.header ? parseInt(this.$refs.header.offsetHeight) : 0
            this.menuHeight = (window.innerHeight - headerHeight) + 'px'
        }
    }
}
</script>
