<template>
    <div class="flex flex-col md:flex-row px-4 py-2 space-y-2 md:space-y-0 md:justify-between items-center bg-gray-100">
        <div>
            <h3 class="text-gray-700 font-bold flex space-x-2 items-center" v-if="currentScreen">
                <span class="text-xs">{{ currentScreen }}</span>
            </h3>
        </div>

        <nav class="flex gap-2 items-center justify-center">
            <template v-for="screen in screens">
                <WsConnectionIcon class="h-5 w-5" v-if="currentScreen == screen"/>
                <div v-else class="w-4 h-4 cursor-pointer rounded-full border-2 border-blue-400"
                     @click="switchScreen(screen)"></div>
            </template>

            <button
                class="w-4 h-4 flex text-xs font-bold items-center justify-center cursor-pointer rounded-full border-2 border-gray-300 text-gray-300"
                @click="newScreen">
                +
            </button>
        </nav>

        <button
            @click="clearEvents"
            class="px-3 py-1 ring ring-red-300 text-xs bg-red-800 text-white rounded-sm hover:bg-red-700 transition transition-all duration-300"
        >
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
