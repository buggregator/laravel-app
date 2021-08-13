<template>
    <div class="flex flex-col md:flex-row px-4 py-2 space-y-2 md:justify-between items-center bg-gray-100 border-b border-gray-200">
        <h3 class="text-gray-700 font-bold flex space-x-2 items-center" v-if="currentScreen">
            <span>{{ currentScreen }}</span>


            <button class="w-4 h-4" @click="newScreen">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
            </button>
        </h3>
        <div class="flex gap-2 items-center justify-center">
            <template v-for="screen in screens">
                <WsConnectionIcon class="h-5 w-5" v-if="currentScreen == screen"/>
                <div v-else class="w-3 h-3 cursor-pointer rounded-full border border-blue-500" @click="switchScreen(screen)"></div>
            </template>
        </div>

        <button
            @click="clearEvents"
            class="px-3 py-1 text-sm bg-red-800 text-white rounded-sm hover:bg-red-700 transition-all duration-300"
        >
            Close screen
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
            this.store.commit('switchScreen', screen);
        },
        clearEvents() {
            this.store.commit('clearEvents');
        }
    },

    setup() {
        const store = useStore();

        let screens = computed(function () {
            return store.state.screens
        });

        let currentScreen = computed(function () {
            return store.state.currentScreen
        });

        let totalEvents = computed(function () {
            return _.size(store.state.events[store.state.currentScreen] || [])
        });

        return {
            store, screens, currentScreen, totalEvents
        }
    }
}
</script>
