<template>
    <div class="screens">
        <h3 class="screens__title" v-if="currentScreen">
            <span class="text-xs">{{ currentScreen }}</span>
        </h3>

        <nav class="screens__nav">
            <template v-for="screen in screens">
                <WsConnectionIcon class="screens__screen-current" v-if="currentScreen == screen"/>
                <div v-else class="screens__screen" @click="switchScreen(screen)">
                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 0a4 4 0 0 0-4 4v12a4 4 0 0 0 4 4h12a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4H4ZM2 4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Zm8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg>
                </div>
            </template>

            <button @click="newScreen" class="screens__btn-new">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 4a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4H4a4 4 0 0 1-4-4V4Zm4-2a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H4Zm6 2c.6 0 1 .4 1 1v4h4a1 1 0 1 1 0 2h-4v4a1 1 0 1 1-2 0v-4H5a1 1 0 1 1 0-2h4V5c0-.6.4-1 1-1Z" /></svg>
            </button>
        </nav>

        <button @click="clearEvents" class="screens__btn-clear">
            Clear screen
        </button>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import WsConnectionIcon from "../UI/WsConnectionIcon";

export default {
    components: {WsConnectionIcon},
    methods: {
        newScreen() {
            this.store.commit('switchScreen');
        },
        switchScreen(screen) {
            this.store.commit('switchScreen', screen)
        },
        clearEvents() {
            this.store.dispatch('clearEvents')
        }
    },

    setup() {
        const store = useStore();

        const screens = computed(() => store.getters.screens)
        const currentScreen = computed(() => store.state.currentScreen)
        const totalEvents = computed(() => store.getters.totalEvents)

        return {
            store, screens, currentScreen, totalEvents
        }
    }
}
</script>
