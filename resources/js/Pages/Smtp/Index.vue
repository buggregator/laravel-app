<template>
    <MainLayout title="SMTP" class="smtp">
        <div ref="header" class="smtp__nav">
            <div class="text-muted">Mailbox</div>
        </div>
        <main class="smtp__main">
            <div class="smtp__events-list">
                <PerfectScrollbar :style="{height: menuHeight}" v-if="events.length > 0">
                    <NavItem v-for="event in events" :event="event" class="smtp__events-item"/>
                </PerfectScrollbar>

                <div v-else class="smtp__empty">
                    <svg class="smtp__empty-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 36"><path fill-rule="evenodd" clip-rule="evenodd" d="M26 4h-4V2h4v2Zm1 2h-5v2h5a8 8 0 0 1 8 8v11a3 3 0 0 1-3 3H20v5c0 .6-.4 1-1 1h-4a1 1 0 0 1-1-1v-5H3a3 3 0 0 1-3-3V16a8 8 0 0 1 8-8h12V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1Zm-7 8v-4h-6.7a8 8 0 0 1 2.7 6v12h16c.6 0 1-.4 1-1V16a6 6 0 0 0-6-6h-5v4a1 1 0 1 1-2 0Zm-4 16h2v4h-2v-4Zm-2-14v12H3a1 1 0 0 1-1-1V16a6 6 0 0 1 12 0Zm-9 3a1 1 0 1 0 0 2h5.5a1 1 0 1 0 0-2H5Z"/></svg>
                    <h3 class="smtp__empty-text">Your inbox is empty</h3>
                </div>
            </div>

            <div class="smtp__settings">
                <h2 class="smtp__settings-title">SMTP Settings</h2>
                <p class="smtp__settings-text">Use these settings to send messages directly from your email client or mail transfer agent.</p>

                <div v-if="events.length > 0" class="my-3">
                    <button @click="clearEvents" class="smtp__btn-clear">Clear events</button>
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
        clearEvents() {
            this.store.dispatch('clearEvents', 'smtp')
        },
        loadEvents() {
            this.store.commit('smtp/clearEvents')
            this.$page.props.events.data.forEach((e) => {
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
