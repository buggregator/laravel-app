<template>
    <div class="flex p-4 justify-between items-center bg-gray-100 border-b border-gray-200">
        <h3 class="text-gray-700 font-bold" v-if="currentScreen">
            {{ currentScreen }}
        </h3>
        <div class="flex gap-2 items-center justify-center">
            <template v-for="screen in screens">
                <svg v-if="currentScreen == screen" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#3b82f6" stroke-width="4"></circle>
                    <path class="opacity-75" fill="#3b82f6" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
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
export default {
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
