<template>
    <div class="flex p-4 justify-between items-center bg-gray-100 border-b border-gray-200">
        <h3 class="text-gray-700 font-bold" v-if="currentScreen">
            {{ currentScreen }}
        </h3>
        <div class="flex gap-2 items-center justify-center">
            <template v-for="screen in screens">
                <WsConnectionIcon class="h-5 w-5" v-if="currentScreen == screen"/>
                <div v-else class="w-3 h-3 cursor-pointer rounded-full border border-blue-500" @click="switchScreen(screen)"></div>
            </template>
        </div>

        <div>
            <button
                @click="clearEvents"
                class="px-3 py-1 text-sm border border-gray-300 text-gray-400 rounded-sm hover:border-gray-500 hover:text-gray-600"
            >
                Clear events [{{ totalEvents }}]
            </button>
        </div>
    </div>
</template>

<script>
import {computed} from 'vue';
import {useStore} from "vuex";
import WsConnectionIcon from "../UI/WsConnectionIcon";
export default {
    components: {WsConnectionIcon},
    methods: {
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
